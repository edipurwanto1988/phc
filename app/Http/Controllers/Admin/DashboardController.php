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
            $cleanerPaymentsTotal = (float) \App\Models\OrderAssignmentPayment::whereHas('assignment', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('amount');
            $cleanerLegacyTotal = (float) \App\Models\OrderAssignment::where('user_id', $user->id)
                ->where('status_gaji', 'sudah_dibayar')
                ->whereDoesntHave('payments')
                ->sum('gaji');

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
                'total_revenue' => $cleanerPaymentsTotal + $cleanerLegacyTotal,
                'cleaners_count' => 1,
            ];
        } else {
            // Real cash inflow: OrderPayment (DP, cicilan, pelunasan) + paid orders without separate order_payments
            $realPaymentsTotal = (float) \App\Models\OrderPayment::sum('amount');
            $legacyPaidTotal = (float) Order::whereDoesntHave('payments')->where('status_bayar', 'paid')->sum('grand_total');

            $stats = [
                'total_orders' => Order::count(),
                'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'in_progress'])->count(),
                'completed_orders' => Order::where('status', 'completed')->count(),
                'total_customers' => Customer::where('status', 'active')->count(),
                'total_services' => Service::where('is_active', true)->count(),
                'total_revenue' => $realPaymentsTotal + $legacyPaidTotal,
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

        $startDate = now()->startOfMonth()->subMonths(5);

        // 3. Get monthly revenue statistics for chart/trend
        if ($isCleaner) {
            $revenuePayments = \App\Models\OrderAssignmentPayment::select(
                    DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                    DB::raw('SUM(order_assignment_payments.amount) as total')
                )
                ->join('order_assignments', 'order_assignments.id', '=', 'order_assignment_payments.assignment_id')
                ->where('order_assignments.user_id', $user->id)
                ->where('payment_date', '>=', $startDate)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $revenueLegacy = \App\Models\OrderAssignment::where('user_id', $user->id)
                ->where('status_gaji', 'sudah_dibayar')
                ->whereDoesntHave('payments')
                ->where('updated_at', '>=', $startDate)
                ->select(
                    DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as month"),
                    DB::raw('SUM(gaji) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $allKeys = $revenuePayments->keys()->merge($revenueLegacy->keys())->unique();
            $revenuePerMonth = collect();
            foreach ($allKeys as $k) {
                $sum = (float) ($revenuePayments[$k]->total ?? 0) + (float) ($revenueLegacy[$k]->total ?? 0);
                $revenuePerMonth[$k] = (object) ['total' => $sum];
            }

            $expensePerMonth = collect(); // Cleaners don't have expenses
        } else {
            // Uang masuk riil dari OrderPayment (DP, Cicilan, Pelunasan berdasarkan tanggal pembayaran)
            $revenuePayments = \App\Models\OrderPayment::select(
                    DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                    DB::raw('SUM(amount) as total')
                )
                ->where('payment_date', '>=', $startDate)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            // Order lunas terdahulu yang belum tercatat di tabel order_payments
            $revenueLegacy = Order::whereDoesntHave('payments')
                ->where('status_bayar', 'paid')
                ->where(function($q) use ($startDate) {
                    $q->where('tanggal_order', '>=', $startDate)
                      ->orWhere('created_at', '>=', $startDate);
                })
                ->select(
                    DB::raw("DATE_FORMAT(COALESCE(tanggal_order, created_at), '%Y-%m') as month"),
                    DB::raw('SUM(grand_total) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $allKeys = $revenuePayments->keys()->merge($revenueLegacy->keys())->unique();
            $revenuePerMonth = collect();
            foreach ($allKeys as $k) {
                $sum = (float) ($revenuePayments[$k]->total ?? 0) + (float) ($revenueLegacy[$k]->total ?? 0);
                $revenuePerMonth[$k] = (object) ['total' => $sum];
            }

            $expensePerMonth = \App\Models\Expense::select(
                    DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month"),
                    DB::raw('SUM(jumlah) as total')
                )
                ->where('tanggal', '>=', $startDate)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');
        }

        $months = [];
        $revenueData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
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