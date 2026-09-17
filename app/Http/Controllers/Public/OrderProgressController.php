<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProgressBukti;
use App\Models\OrderProgressRoom;

class OrderProgressController extends Controller
{
    public function show($token)
    {
        $order = Order::where('progress_token', $token)
            ->with(['customer', 'progressRooms.buktiPhotos'])
            ->firstOrFail();

        // Kelompokkan ruangan berdasarkan lantai
        $grouped = $order->progressRooms->groupBy(function ($room) {
            return $room->lantai ?: 'Tanpa Lantai';
        });

        return view('pages.progress', compact('order', 'grouped'));
    }

    public function viewBukti($token, OrderProgressBukti $bukti)
    {
        $room = $bukti->room;
        $order = Order::where('progress_token', $token)->firstOrFail();
        abort_unless($room && $room->order_id === $order->id, 404);

        $photoUrl = $bukti->path;
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
            \Log::error("Public progress bukti proxy error: " . $e->getMessage());
            return abort(500);
        }
    }
}
