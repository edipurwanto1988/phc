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
            ->with([
                'customer',
                'progressRooms.buktiPhotos',
                'assignments' => function ($q) {
                    $q->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                },
                'assignments.cleaner',
            ])
            ->firstOrFail();

        // Kelompokkan ruangan berdasarkan lantai
        $grouped = $order->progressRooms->groupBy(function ($room) {
            return $room->lantai ?: 'Tanpa Lantai';
        })->sortKeysUsing(fn($a, $b) => strnatcasecmp($a, $b));

        return view('pages.progress', compact('order', 'grouped'));
    }

    public function viewBukti($token, OrderProgressBukti $bukti)
    {
        $room = $bukti->room;
        $order = Order::where('progress_token', $token)->firstOrFail();
        abort_unless($room && $room->order_id === $order->id, 404);

        return $this->proxyImage($bukti->path);
    }

    public function viewCleanerFoto($token, \App\Models\User $cleaner)
    {
        $order = Order::where('progress_token', $token)->firstOrFail();
        // Pastikan cleaner ini bagian dari order
        $assigned = $order->assignments()->where('user_id', $cleaner->id)->exists();
        abort_unless($assigned, 404);

        return $this->proxyImage($cleaner->foto);
    }

    protected function proxyImage(?string $photoUrl)
    {
        return \App\Services\ImageProxy::respond($photoUrl);
    }
}
