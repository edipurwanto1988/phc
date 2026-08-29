<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        
        $monthlyRevenue = Order::select(
                DB::raw("MONTH(tanggal_jadwal) as month"),
                DB::raw("COUNT(*) as total_orders"),
                DB::raw("SUM(grand_total) as revenue")
            )
            ->whereYear('tanggal_jadwal', $year)
            ->where('status_bayar', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyExpenses = Expense::select(
                DB::raw("MONTH(tanggal) as month"),
                DB::raw("SUM(jumlah) as expense")
            )
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $reportData = [];
        $totalOrdersYear = 0;
        $totalRevenueYear = 0;
        $totalExpenseYear = 0;

        for ($m = 1; $m <= 12; $m++) {
            $orders = $monthlyRevenue->has($m) ? $monthlyRevenue[$m]->total_orders : 0;
            $revenue = $monthlyRevenue->has($m) ? (float) $monthlyRevenue[$m]->revenue : 0.0;
            $expense = $monthlyExpenses->has($m) ? (float) $monthlyExpenses[$m]->expense : 0.0;
            
            $totalOrdersYear += $orders;
            $totalRevenueYear += $revenue;
            $totalExpenseYear += $expense;

            $reportData[$m] = [
                'month_name' => date('F', mktime(0, 0, 0, $m, 1)),
                'orders' => $orders,
                'revenue' => $revenue,
                'expense' => $expense,
                'profit' => $revenue - $expense,
            ];
        }

        $cashIn = Order::where('status_bayar', 'paid')->sum('grand_total');
        $cashOut = Expense::sum('jumlah');
        $cashBalance = $cashIn - $cashOut;

        $serviceBreakdown = OrderItem::select(
                'services.nama as service_name',
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereYear('orders.tanggal_jadwal', $year)
            ->where('orders.status_bayar', 'paid')
            ->groupBy('services.nama')
            ->orderBy('total_sales', 'desc')
            ->get();

        return view('admin.reports', compact(
            'reportData', 
            'totalOrdersYear', 
            'totalRevenueYear', 
            'totalExpenseYear', 
            'serviceBreakdown', 
            'year',
            'cashIn',
            'cashOut',
            'cashBalance'
        ));
    }

    public function detail(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $inflow = Order::with('customer')
            ->whereBetween('tanggal_jadwal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status_bayar', 'paid')
            ->orderBy('tanggal_jadwal', 'asc')
            ->get();

        $outflow = Expense::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalInflow = $inflow->sum('grand_total');
        $totalOutflow = $outflow->sum('jumlah');
        $balance = $totalInflow - $totalOutflow;

        return view('admin.reports_detail', compact(
            'inflow',
            'outflow',
            'totalInflow',
            'totalOutflow',
            'balance',
            'startDate',
            'endDate'
        ));
    }
}
