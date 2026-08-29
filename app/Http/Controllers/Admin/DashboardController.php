<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get statistics
        $stats = [
            'total_orders' => Order::count(),
            'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'in_progress'])->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_customers' => Customer::where('status', 'active')->count(),
            'total_services' => Service::where('is_active', true)->count(),
            'total_revenue' => Order::where('status_bayar', 'paid')->sum('grand_total'),
            'cleaners_count' => User::whereHas('role', function($q) {
                $q->where('name', 'Cleaner');
            })->count(),
        ];

        // 2. Get recent 5 orders
        $recentOrders = Order::with(['customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Get monthly revenue statistics for chart/trend
        $revenuePerMonth = Order::select(
                DB::raw("DATE_FORMAT(tanggal_jadwal, '%Y-%m') as month"),
                DB::raw('SUM(grand_total) as total')
            )
            ->where('status_bayar', 'paid')
            ->where('tanggal_jadwal', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // 4. Get monthly expenses statistics for chart/trend
        $expensePerMonth = \App\Models\Expense::select(
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month"),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = [];
        $revenueData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->translatedFormat('F Y');
            $revenueData[] = $revenuePerMonth->has($key) ? (float) $revenuePerMonth[$key]->total : 0.0;
            $expenseData[] = $expensePerMonth->has($key) ? (float) $expensePerMonth[$key]->total : 0.0;
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'months', 'revenueData', 'expenseData'));
    }
}