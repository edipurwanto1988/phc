<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        
        // 1. Monthly Revenue breakdown for the selected year
        $monthlyRevenue = Order::select(
                DB::raw("MONTH(tanggal_order) as month"),
                DB::raw("COUNT(*) as total_orders"),
                DB::raw("SUM(grand_total) as revenue")
            )
            ->whereYear('tanggal_order', $year)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $reportData = [];
        $totalOrdersYear = 0;
        $totalRevenueYear = 0;

        for ($m = 1; $m <= 12; $m++) {
            $orders = $monthlyRevenue->has($m) ? $monthlyRevenue[$m]->total_orders : 0;
            $revenue = $monthlyRevenue->has($m) ? (float) $monthlyRevenue[$m]->revenue : 0.0;
            
            $totalOrdersYear += $orders;
            $totalRevenueYear += $revenue;

            $reportData[$m] = [
                'month_name' => date('F', mktime(0, 0, 0, $m, 1)),
                'orders' => $orders,
                'revenue' => $revenue,
            ];
        }

        // 2. Sales per Service item breakdown
        $serviceBreakdown = OrderItem::select(
                'services.nama as service_name',
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereYear('orders.tanggal_order', $year)
            ->where('orders.status', 'completed')
            ->groupBy('services.nama')
            ->orderBy('total_sales', 'desc')
            ->get();

        return view('admin.reports', compact('reportData', 'totalOrdersYear', 'totalRevenueYear', 'serviceBreakdown', 'year'));
    }
}
