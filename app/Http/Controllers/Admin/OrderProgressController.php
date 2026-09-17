<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProgressRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderProgressController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['customer', 'progressRooms']);

        return view('admin.progress.show', compact('order'));
    }

    public function storeRoom(Request $request, Order $order)
    {
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

    public function updateRoom(Request $request, Order $order, OrderProgressRoom $room)
    {
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
        abort_unless($room->order_id === $order->id, 404);

        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';
        $data = [];

        if ($request->hasFile('bukti')) {
            if ($room->bukti && !str_starts_with($room->bukti, 'http') && file_exists(public_path($room->bukti))) {
                @unlink(public_path($room->bukti));
            }

            $file = $request->file('bukti');
            $filename = 'progress_' . $room->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            if ($gdriveConnected) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $result = $driveService->uploadFile($file->getRealPath(), $filename, $order->order_number);
                    $data['bukti'] = $result['web_content_link'];
                } catch (\Exception $e) {
                    \Log::error("Failed to upload progress bukti to GDrive: " . $e->getMessage());
                    $file->move(public_path('uploads/progress'), $filename);
                    $data['bukti'] = 'uploads/progress/' . $filename;
                }
            } else {
                $file->move(public_path('uploads/progress'), $filename);
                $data['bukti'] = 'uploads/progress/' . $filename;
            }
        }

        if (!empty($data)) {
            $room->update($data);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bukti pekerjaan berhasil diunggah.',
                'data' => $data,
            ]);
        }

        return back()->with('success', 'Bukti pekerjaan berhasil diunggah.');
    }

    public function deleteBukti(Order $order, OrderProgressRoom $room)
    {
        abort_unless($room->order_id === $order->id, 404);

        $path = $room->bukti;
        if ($path && !str_starts_with($path, 'http') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        $room->update(['bukti' => null]);

        return back()->with('success', 'Bukti pekerjaan berhasil dihapus.');
    }

    public function viewBukti(Order $order, OrderProgressRoom $room)
    {
        abort_unless($room->order_id === $order->id, 404);

        $photoUrl = $room->bukti;
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

            return abort(404, 'Gagal mengambil gambar dari Google Drive.');
        } catch (\Exception $e) {
            \Log::error("Progress Bukti Proxy error: " . $e->getMessage());
            return abort(500);
        }
    }

    public function destroyRoom(Order $order, OrderProgressRoom $room)
    {
        abort_unless($room->order_id === $order->id, 404);
        $room->delete();

        return back()->with('success', 'Ruangan berhasil dihapus dari progress.');
    }

    public function generateLink(Order $order)
    {
        if (!$order->progress_token) {
            $order->update(['progress_token' => Str::random(32)]);
        }

        return back()->with('success', 'Link progress berhasil dibuat.')->with('progress_link', url('/progress/' . $order->progress_token));
    }
}
