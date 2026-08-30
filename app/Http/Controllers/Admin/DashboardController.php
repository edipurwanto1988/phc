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
        $user = \Auth::user();
        $isCleaner = $user->role && $user->role->name === 'Cleaner';

        // 1. Get statistics
        if ($isCleaner) {
            $stats = [
                'total_orders' => Order::whereHas('assignments', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count(),
                'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'in_progress'])
                    ->whereHas('assignments', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->count(),
                'completed_orders' => Order::where('status', 'completed')
                    ->whereHas('assignments', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->count(),
                'total_customers' => Customer::where('status', 'active')
                    ->whereHas('orders.assignments', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->count(),
                'total_services' => Service::where('is_active', true)->count(),
                'total_revenue' => \App\Models\OrderAssignment::where('user_id', $user->id)
                    ->where('status_gaji', 'sudah_dibayar')
                    ->sum('gaji'),
                'cleaners_count' => 1,
            ];
        } else {
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
        }

        // 2. Get recent orders
        $recentOrdersQuery = Order::with(['customer', 'creator'])
            ->orderBy('created_at', 'desc');

        if ($isCleaner) {
            $recentOrdersQuery->whereHas('assignments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $recentOrders = $recentOrdersQuery->limit(5)->get();

        // 3. Get monthly revenue statistics for chart/trend
        if ($isCleaner) {
            $revenuePerMonth = \App\Models\OrderAssignment::select(
                    DB::raw("DATE_FORMAT(finished_at, '%Y-%m') as month"),
                    DB::raw('SUM(gaji) as total')
                )
                ->where('user_id', $user->id)
                ->where('status_gaji', 'sudah_dibayar')
                ->where('finished_at', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $expensePerMonth = collect(); // Cleaners don't have expenses
        } else {
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

            $expensePerMonth = \App\Models\Expense::select(
                    DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month"),
                    DB::raw('SUM(jumlah) as total')
                )
                ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');
        }

        $months = [];
        $revenueData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $months[] = $date->translatedFormat('F Y');
            
            if ($isCleaner) {
                $revenueData[] = $revenuePerMonth->has($key) ? (float) $revenuePerMonth[$key]->total : 0.0;
                $expenseData[] = 0.0;
            } else {
                $revenueData[] = $revenuePerMonth->has($key) ? (float) $revenuePerMonth[$key]->total : 0.0;
                $expenseData[] = $expensePerMonth->has($key) ? (float) $expensePerMonth[$key]->total : 0.0;
            }
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'months', 'revenueData', 'expenseData', 'isCleaner'));
    }
}