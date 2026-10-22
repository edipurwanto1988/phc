<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProgressRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderProgressController extends Controller
{
    public function show(Order $order)
    {
        $this->authorizeOrderAccess($order);
        $order->load(['customer', 'progressRooms']);

        return view('admin.progress.show', compact('order'));
    }

    public function storeRoom(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);
        $request->validate([
            'lantai' => 'nullable|string|max:100',
            'ruangan' => 'required|string|max:200',
            'luas' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:2000',
            'masalah' => 'nullable|string|max:2000',
        ]);

        $maxSort = $order->progressRooms()->max('sort_order') ?? 0;

        $room = $order->progressRooms()->create([
            'lantai' => $request->lantai,
            'ruangan' => $request->ruangan,
            'luas' => $request->luas,
            'status' => 'belum',
            'catatan' => $request->catatan,
            'masalah' => $request->masalah,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', "Ruangan \"{$room->ruangan}\" berhasil ditambahkan ke progress.");
    }

    public function importRooms(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);
        $request->validate([
            'json' => 'required_without:file|nullable|string',
            'file' => 'nullable|file|mimes:json,txt|max:2048',
        ]);

        // Ambil isi JSON dari textarea atau file
        $raw = null;
        if ($request->filled('json')) {
            $raw = $request->json;
        } elseif ($request->hasFile('file')) {
            $raw = file_get_contents($request->file('file')->getRealPath());
        }

        if (!$raw) {
            return back()->with('error', 'Harap tempel JSON atau pilih file JSON.');
        }

        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Format JSON tidak valid: ' . json_last_error_msg());
        }

        // Normalisasi: bisa berupa array langsung atau dibungkus object {"rooms": [...]}
        if (isset($data['rooms']) && is_array($data['rooms'])) {
            $data = $data['rooms'];
        }

        if (!is_array($data)) {
            return back()->with('error', 'JSON harus berupa array (daftar ruangan).');
        }

        $maxSort = $order->progressRooms()->max('sort_order') ?? 0;
        $imported = 0;

        foreach ($data as $i => $item) {
            if (!is_array($item)) {
                continue;
            }

            $ruangan = trim((string) ($item['ruangan'] ?? ''));
            if ($ruangan === '') {
                continue;
            }

            $order->progressRooms()->create([
                'lantai' => isset($item['lantai']) ? trim((string) $item['lantai']) : null,
                'ruangan' => $ruangan,
                'luas' => isset($item['luas']) && $item['luas'] !== '' ? (float) $item['luas'] : null,
                'status' => in_array($item['status'] ?? 'belum', ['belum', 'proses', 'selesai'], true) ? ($item['status'] ?? 'belum') : 'belum',
                'catatan' => isset($item['catatan']) ? (string) $item['catatan'] : null,
                'masalah' => isset($item['masalah']) ? (string) $item['masalah'] : null,
                'sort_order' => $maxSort + $imported + 1,
            ]);

            $imported++;
        }

        if ($imported === 0) {
            return back()->with('error', 'Tidak ada ruangan valid yang bisa diimport. Periksa format JSON.');
        }

        return back()->with('success', "Berhasil mengimport {$imported} ruangan secara massal.");
    }

    public function updateLantai(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);
        $request->validate([
            'old_lantai' => 'nullable|string|max:100',
            'new_lantai' => 'nullable|string|max:100',
        ]);

        $oldLantai = $request->old_lantai;
        $newLantai = trim((string) $request->new_lantai);
        $newLantai = ($newLantai === '' || $newLantai === 'Tanpa Lantai') ? null : $newLantai;

        $query = $order->progressRooms();
        if ($oldLantai === null || $oldLantai === '' || $oldLantai === 'Tanpa Lantai') {
            $query->where(function ($q) {
                $q->whereNull('lantai')->orWhere('lantai', '');
            });
        } else {
            $query->where('lantai', $oldLantai);
        }

        $affected = $query->update(['lantai' => $newLantai]);

        $displayTitle = $newLantai ?? 'Tanpa Lantai';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Nama lantai berhasil diubah menjadi \"{$displayTitle}\".",
                'affected' => $affected,
            ]);
        }

        return back()->with('success', "Nama lantai berhasil diubah menjadi \"{$displayTitle}\".");
    }

    public function destroyLantai(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);
        $request->validate([
            'old_lantai' => 'nullable|string|max:100',
            'password' => 'required|string',
        ]);

        if (!$request->user()->password || !Hash::check($request->password, $request->user()->password)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password login yang Anda masukkan salah.',
                ], 422);
            }

            return back()->with('error', 'Password login yang Anda masukkan salah.');
        }

        $oldLantai = $request->old_lantai;
        $query = $order->progressRooms();
        if ($oldLantai === null || $oldLantai === '' || $oldLantai === 'Tanpa Lantai') {
            $query->where(function ($q) {
                $q->whereNull('lantai')->orWhere('lantai', '');
            });
        } else {
            $query->where('lantai', $oldLantai);
        }

        $rooms = $query->get();
        $count = $rooms->count();

        foreach ($rooms as $room) {
            foreach ($room->buktiPhotos as $bukti) {
                if ($bukti->path && !str_starts_with($bukti->path, 'http') && file_exists(public_path($bukti->path))) {
                    @unlink(public_path($bukti->path));
                }
            }
            $room->delete();
        }

        $displayTitle = $oldLantai && $oldLantai !== 'Tanpa Lantai' ? $oldLantai : 'Tanpa Lantai';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Kelompok \"{$displayTitle}\" ({$count} ruangan) berhasil dihapus.",
            ]);
        }

        return back()->with('success', "Kelompok \"{$displayTitle}\" ({$count} ruangan) berhasil dihapus.");
    }

    public function updateRoom(Request $request, Order $order, OrderProgressRoom $room)
    {
        $this->authorizeOrderAccess($order);
        abort_unless($room->order_id === $order->id, 404);

        $request->validate([
            'lantai' => 'nullable|string|max:100',
            'ruangan' => 'nullable|string|max:200',
            'luas' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:belum,proses,selesai',
            'catatan' => 'nullable|string|max:2000',
            'masalah' => 'nullable|string|max:2000',
        ]);

        $room->update($request->only(['lantai', 'ruangan', 'luas', 'status', 'catatan', 'masalah']));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Progress ruangan berhasil diperbarui.',
                'data' => $room,
            ]);
        }

        return back()->with('success', 'Progress ruangan berhasil diperbarui.');
    }

    public function uploadBukti(Request $request, Order $order, OrderProgressRoom $room)
    {
        $this->authorizeOrderAccess($order);
        abort_unless($room->order_id === $order->id, 404);

        $request->validate([
            'bukti' => 'required|array|min:1',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';
        $uploaded = [];

        foreach ($request->file('bukti') as $file) {
            $filename = 'progress_' . $room->id . '_' . time() . '_' . uniqid() . '.' . $file->extension();

            if ($gdriveConnected) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $result = $driveService->uploadFile($file->getRealPath(), $filename, $order->order_number);
                    $path = $result['web_content_link'];
                } catch (\Exception $e) {
                    \Log::error("Failed to upload progress bukti to GDrive: " . $e->getMessage());
                    $dir = public_path('uploads/progress');
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0755, true);
                    }
                    $file->move($dir, $filename);
                    $path = 'uploads/progress/' . $filename;
                }
            } else {
                $dir = public_path('uploads/progress');
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $file->move($dir, $filename);
                $path = 'uploads/progress/' . $filename;
            }

            $room->buktiPhotos()->create(['path' => $path]);
            $uploaded[] = $path;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bukti pekerjaan berhasil diunggah.',
                'data' => $uploaded,
            ]);
        }

        return back()->with('success', count($uploaded) . ' bukti pekerjaan berhasil diunggah.');
    }

    public function deleteBukti(Order $order, OrderProgressRoom $room, \App\Models\OrderProgressBukti $bukti)
    {
        $this->authorizeOrderAccess($order);
        abort_unless($room->order_id === $order->id, 404);
        abort_unless($bukti->order_progress_room_id === $room->id, 404);

        $path = $bukti->path;

        // Hapus file lokal (jika disimpan lokal)
        if ($path && !str_starts_with($path, 'http') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        // Hapus file dari Google Drive (jika tersimpan di Drive)
        if ($path && (str_contains($path, 'drive.google.com') || str_contains($path, 'drive.usercontent.google.com'))) {
            // Ekstrak file ID dari URL Drive
            if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $path, $m)) {
                $fileId = $m[1];
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $driveService->deleteFile($fileId);
                } catch (\Exception $e) {
                    \Log::error("Failed to delete progress bukti from GDrive: " . $e->getMessage());
                }
            }
        }

        $bukti->delete();

        return back()->with('success', 'Bukti pekerjaan berhasil dihapus.');
    }

    public function viewBukti(Order $order, OrderProgressRoom $room, \App\Models\OrderProgressBukti $bukti)
    {
        $this->authorizeOrderAccess($order);
        abort_unless($room->order_id === $order->id, 404);
        abort_unless($bukti->order_progress_room_id === $room->id, 404);

        $photoUrl = $bukti->path;

        return \App\Services\ImageProxy::respond($photoUrl);
    }

    public function destroyRoom(Request $request, Order $order, OrderProgressRoom $room)
    {
        $this->authorizeOrderAccess($order);
        abort_unless($room->order_id === $order->id, 404);

        if ($request->filled('password')) {
            if (!$request->user()->password || !Hash::check($request->password, $request->user()->password)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password login yang Anda masukkan salah.',
                    ], 422);
                }

                return back()->with('error', 'Password login yang Anda masukkan salah.');
            }
        }

        foreach ($room->buktiPhotos as $bukti) {
            if ($bukti->path && !str_starts_with($bukti->path, 'http') && file_exists(public_path($bukti->path))) {
                @unlink(public_path($bukti->path));
            }
        }

        $roomName = $room->ruangan;
        $room->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Ruangan \"{$roomName}\" berhasil dihapus dari progress.",
            ]);
        }

        return back()->with('success', "Ruangan \"{$roomName}\" berhasil dihapus dari progress.");
    }

    public function generateLink(Order $order)
    {
        $this->authorizeOrderAccess($order);
        if (!$order->progress_token) {
            $order->update(['progress_token' => Str::random(32)]);
        }

        return back()->with('success', 'Link progress berhasil dibuat.')->with('progress_link', url('/progress/' . $order->progress_token));
    }
}
