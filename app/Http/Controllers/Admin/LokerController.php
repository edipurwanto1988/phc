<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loker;
use App\Models\PeriodeLoker;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class LokerController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = $request->get('periode_id', '');
        $nama = trim($request->get('nama', ''));

        $lokeres = Loker::with('periode')
            ->when($periodeId !== '' && $periodeId !== null, function ($q) use ($periodeId) {
                return $q->where('periode_id', $periodeId);
            })
            ->when($nama !== '', function ($q) use ($nama) {
                return $q->where('nama', 'like', "%{$nama}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['periode_id' => $periodeId, 'nama' => $nama]);

        $periodes = PeriodeLoker::orderBy('created_at', 'desc')->get();

        return view('admin.loker.index', compact('lokeres', 'periodes', 'periodeId', 'nama'));
    }

    public function create()
    {
        $periodes = PeriodeLoker::orderBy('created_at', 'desc')->get();
        return view('admin.loker.create', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'nullable|exists:periode_lokeres,id',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pengalaman' => 'nullable|string',
            'keahlian_khusus' => 'nullable|string',
            'cerita' => 'nullable|string',
            'no_wa' => 'required|string|max:20',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->only([
            'periode_id', 'nama', 'email', 'alamat', 'jenis_kelamin',
            'pengalaman', 'keahlian_khusus', 'cerita', 'no_wa',
        ]);

        // Upload KTP (rename sesuai no WA) — ke Google Drive jika terhubung, fallback lokal
        if ($request->hasFile('ktp')) {
            $this->uploadKtp($request->file('ktp'), $request->no_wa, $data);
        }

        Loker::create($data);

        return redirect()->route('admin.loker.index')->with('success', 'Data pelamar Loker berhasil ditambahkan.');
    }

    public function show(Loker $loker)
    {
        return view('admin.loker.show', compact('loker'));
    }

    public function edit(Loker $loker)
    {
        $periodes = PeriodeLoker::orderBy('created_at', 'desc')->get();
        return view('admin.loker.edit', compact('loker', 'periodes'));
    }

    public function update(Request $request, Loker $loker)
    {
        $request->validate([
            'periode_id' => 'nullable|exists:periode_lokeres,id',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pengalaman' => 'nullable|string',
            'keahlian_khusus' => 'nullable|string',
            'cerita' => 'nullable|string',
            'no_wa' => 'required|string|max:20',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->only([
            'periode_id', 'nama', 'email', 'alamat', 'jenis_kelamin',
            'pengalaman', 'keahlian_khusus', 'cerita', 'no_wa',
        ]);

        if ($request->hasFile('ktp')) {
            $this->deleteKtp($loker);
            $this->uploadKtp($request->file('ktp'), $request->no_wa, $data);
        }

        $loker->update($data);

        return redirect()->route('admin.loker.index')->with('success', 'Data pelamar Loker berhasil diperbarui.');
    }

    public function destroy(Loker $loker)
    {
        $this->deleteKtp($loker);
        $loker->delete();
        return redirect()->route('admin.loker.index')->with('success', 'Data pelamar Loker berhasil dihapus.');
    }

    public function viewKtp(Loker $loker)
    {
        $ktp = $loker->ktp;
        if (!$ktp) {
            return abort(404);
        }

        // Jika tersimpan lokal
        if (!str_starts_with($ktp, 'http') && file_exists(public_path($ktp))) {
            return response()->file(public_path($ktp));
        }

        // Jika tersimpan di Google Drive (proxy agar tetap terlihat walau link dibatasi)
        if (str_starts_with($ktp, 'http')) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $ktp);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');

                $gdriveConnected = Setting::get('gdrive_connected') === 'true';
                if ($gdriveConnected) {
                    $tokenJson = Setting::get('gdrive_access_token');
                    if ($tokenJson) {
                        $accessToken = json_decode($tokenJson, true);
                        if (isset($accessToken['access_token'])) {
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $accessToken['access_token']
                            ]);
                        }
                    }
                }

                $data = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                curl_close($ch);

                if ($httpCode === 200 && $data) {
                    return response($data)->header('Content-Type', $contentType ?: 'image/jpeg');
                }
            } catch (\Exception $e) {
                \Log::error("Loker KTP proxy error: " . $e->getMessage());
            }
        }

        return abort(404, 'Gagal mengambil gambar KTP.');
    }

    /**
     * Upload KTP, file di-rename sesuai no WA.
     */
    protected function uploadKtp($file, string $noWa, array &$data)
    {
        $safeNoWa = preg_replace('/[^0-9]/', '', $noWa);
        $filename = 'ktp_' . $safeNoWa . '.' . $file->getClientOriginalExtension();

        $gdriveConnected = Setting::get('gdrive_connected') === 'true';
        if ($gdriveConnected) {
            try {
                $driveService = new GoogleDriveService();
                $result = $driveService->uploadFile($file->getRealPath(), $filename, 'PHC_Loker');
                $data['ktp'] = $result['web_content_link'];
                $data['ktp_drive_id'] = $result['id'];
                return;
            } catch (\Exception $e) {
                \Log::error("Failed to upload Loker KTP to GDrive: " . $e->getMessage());
                // fallback ke lokal
            }
        }

        $dir = public_path('uploads/loker');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file->move($dir, $filename);
        $data['ktp'] = 'uploads/loker/' . $filename;
    }

    /**
     * Hapus file KTP lama (lokal / Google Drive).
     */
    protected function deleteKtp(Loker $loker)
    {
        $ktp = $loker->ktp;
        if (!$ktp) {
            return;
        }

        // Hapus dari Google Drive jika ada drive id / URL drive
        if ($loker->ktp_drive_id || str_contains($ktp, 'drive.google.com') || str_contains($ktp, 'drive.usercontent.google.com')) {
            $fileId = $loker->ktp_drive_id;
            if (!$fileId && preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $ktp, $m)) {
                $fileId = $m[1];
            }
            if ($fileId) {
                try {
                    $driveService = new GoogleDriveService();
                    $driveService->deleteFile($fileId);
                } catch (\Exception $e) {
                    \Log::error("Failed to delete Loker KTP from GDrive: " . $e->getMessage());
                }
            }
        }

        // Hapus file lokal jika ada
        if (!str_starts_with($ktp, 'http') && file_exists(public_path($ktp))) {
            @unlink(public_path($ktp));
        }
    }
}
