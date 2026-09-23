<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSchedule;
use App\Models\OrderAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderAttendanceController extends Controller
{
    public function index(Order $order)
    {
        $order->load(['customer', 'assignments.cleaner', 'schedules.attendances']);
        
        $user = Auth::user();
        $isPicOrAdmin = $user->hasPermission('manage_orders') || $user->hasPermission('create_orders') || ($order->assignments->first() && $order->assignments->first()->user_id == $user->id);
        
        \Log::info("User ID: {$user->id}");
        \Log::info("First Assignment User ID: " . ($order->assignments->first() ? $order->assignments->first()->user_id : 'null'));
        \Log::info("isPicOrAdmin: " . ($isPicOrAdmin ? 'TRUE' : 'FALSE'));

        return view('admin.orders.absensi', compact('order', 'isPicOrAdmin', 'user'));
    }

    public function storeSchedule(Request $request, Order $order)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'cleaners' => 'required|array',
            'cleaners.*' => 'exists:users,id',
        ]);

        $schedule = $order->schedules()->firstOrCreate([
            'tanggal' => $request->tanggal
        ]);

        // Pre-create attendance records for selected cleaners
        foreach ($request->cleaners as $userId) {
            $schedule->attendances()->firstOrCreate([
                'user_id' => $userId
            ], [
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroySchedule(Order $order, OrderSchedule $schedule)
    {
        if ($schedule->attendances()->where('status', 'approved')->exists()) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah ada absensi yang disetujui.');
        }
        
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function storeAttendance(Request $request, Order $order, OrderSchedule $schedule)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();

        // Check if schedule date is today
        if (!$schedule->tanggal->isToday()) {
            return back()->with('error', 'Absensi hanya dapat dilakukan pada tanggal jadwal (hari H).');
        }

        // Check if user is assigned to this schedule
        $attendance = $schedule->attendances()->where('user_id', $user->id)->first();
        if (!$attendance) {
            return back()->with('error', 'Anda tidak terdaftar pada jadwal ini.');
        }

        // Check if already checked in
        if ($attendance->waktu_absen) {
            return back()->with('error', 'Anda sudah melakukan absensi pada jadwal ini.');
        }

        $attendance->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'waktu_absen' => now(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Absensi berhasil dikirim dan menunggu persetujuan (ACC).');
    }

    public function updateStatus(Request $request, Order $order, OrderAttendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $user = Auth::user();
        $isPicOrAdmin = $user->hasPermission('manage_orders') || ($order->assignments->first() && $order->assignments->first()->user_id == $user->id);

        if (!$isPicOrAdmin) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah status absensi.');
        }

        $attendance->update([
            'status' => $request->status
        ]);

        $statusText = $request->status == 'approved' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Absensi berhasil $statusText.");
    }
}
