<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->withCount(['createdOrders', 'assignments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'jenis' => 'nullable|in:Tetap,Mitra',
            'keahlian' => 'nullable|string|max:2000',
            'phc_id' => 'nullable|string|max:10',
        ]);

        $role = Role::find($request->role_id);
        $isCleaner = $role && strtolower($role->name) === 'cleaner';

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
            'jenis' => $isCleaner ? $request->jenis : null,
            'keahlian' => $isCleaner ? $request->keahlian : null,
            'phc_id' => $request->phc_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('role');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'jenis' => 'nullable|in:Tetap,Mitra',
            'keahlian' => 'nullable|string|max:2000',
            'phc_id' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $role = Role::find($request->role_id);
        $isCleaner = $role && strtolower($role->name) === 'cleaner';

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
            'jenis' => $isCleaner ? $request->jenis : null,
            'keahlian' => $isCleaner ? $request->keahlian : null,
            'phc_id' => $request->phc_id,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload foto profil (ke Google Drive jika terhubung, fallback lokal)
        if ($request->hasFile('foto')) {
            // Hapus foto lama (lokal) jika ada
            if ($user->foto && !str_starts_with($user->foto, 'http') && file_exists(public_path($user->foto))) {
                @unlink(public_path($user->foto));
            }

            $file = $request->file('foto');
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';
            if ($gdriveConnected) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $result = $driveService->uploadFile($file->getRealPath(), $filename, 'User_' . $user->id . '_' . Str::slug($user->name));
                    $data['foto'] = $result['web_content_link'];
                } catch (\Exception $e) {
                    \Log::error("Failed to upload user foto to GDrive: " . $e->getMessage());
                    $dir = public_path('uploads/users');
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0755, true);
                    }
                    $file->move($dir, $filename);
                    $data['foto'] = 'uploads/users/' . $filename;
                }
            } else {
                $dir = public_path('uploads/users');
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $file->move($dir, $filename);
                $data['foto'] = 'uploads/users/' . $filename;
            }
        }

        $user->update($data);

        return redirect()->route('admin.users.edit', $user)->with('success', 'User updated successfully');
    }

    public function deleteFoto(User $user)
    {
        $path = $user->foto;
        if ($path && !str_starts_with($path, 'http') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        // Hapus dari Google Drive jika tersimpan di Drive
        if ($path && (str_contains($path, 'drive.google.com') || str_contains($path, 'drive.usercontent.google.com'))) {
            if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $path, $m)) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $driveService->deleteFile($m[1]);
                } catch (\Exception $e) {
                    \Log::error("Failed to delete user foto from GDrive: " . $e->getMessage());
                }
            }
        }

        $user->update(['foto' => null]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Foto profil berhasil dihapus.');
    }

    public function viewFoto(User $user)
    {
        $photoUrl = $user->foto;
        if (!$photoUrl) {
            return abort(404);
        }

        if (!str_starts_with($photoUrl, 'http')) {
            return response()->file(public_path($photoUrl));
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $photoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

            $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';
            if ($gdriveConnected) {
                $tokenJson = \App\Models\Setting::get('gdrive_access_token');
                if ($tokenJson) {
                    $accessToken = json_decode($tokenJson, true);
                    if (isset($accessToken['access_token'])) {
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $accessToken['access_token']
                        ]);
                    }
                }
            }

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);

            if ($httpCode === 200 && $imageData) {
                return response($imageData)->header('Content-Type', $contentType ?: 'image/jpeg');
            }

            return abort(404, 'Gagal mengambil gambar.');
        } catch (\Exception $e) {
            \Log::error("User foto proxy error: " . $e->getMessage());
            return abort(500);
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->user()->id) {
            return redirect()->back()->with('error', 'Cannot delete yourself');
        }
        if ($user->createdOrders()->exists() || $user->assignments()->exists()) {
            return redirect()->back()->with('error', 'User masih memiliki order terkait, tidak dapat dihapus');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}