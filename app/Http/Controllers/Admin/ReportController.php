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

        // Uang masuk diambil dari tabel order_payments (mencakup DP, cicilan/partial, dan pelunasan)
        $inflow = \App\Models\OrderPayment::with(['order.customer'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        $outflow = Expense::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $totalInflow = $inflow->sum('amount');
        $totalOutflow = $outflow->sum('jumlah');
        $balance = $totalInflow - $totalOutflow;

        // Calculate lifetime balance prior to startDate to determine beginning balance (saldo awal)
        $previousInflow = \App\Models\OrderPayment::where('payment_date', '<', $startDate)
            ->sum('amount');

        $previousOutflow = Expense::where('tanggal', '<', $startDate)
            ->sum('jumlah');

        $beginningBalance = $previousInflow - $previousOutflow;

        // Combine into one collection, sort chronologically, and calculate cumulative balance
        $ledger = collect();

        foreach ($inflow as $in) {
            $typeLabel = match($in->type) {
                'down_payment' => 'Down Payment (DP)',
                'pelunasan' => 'Pelunasan',
                default => 'Cicilan / Partial',
            };
            $ledger->push([
                'tanggal' => \Carbon\Carbon::parse($in->payment_date),
                'tipe' => 'uang_masuk',
                'keterangan' => "Order #{$in->order->order_number} - {$in->order->customer->nama} ({$typeLabel})",
                'ref' => route('admin.orders.show', $in->order),
                'penerima_pelaksana' => $in->order->customer->nama,
                'masuk' => (float)$in->amount,
                'keluar' => 0.0
            ]);
        }

        foreach ($outflow as $out) {
            $ledger->push([
                'tanggal' => \Carbon\Carbon::parse($out->tanggal),
                'tipe' => 'uang_keluar',
                'keterangan' => "{$out->kategori_biaya} - " . ($out->keterangan ?: 'Tanpa catatan'),
                'ref' => route('admin.expenses.show', $out),
                'penerima_pelaksana' => $out->user->name ?? '-',
                'masuk' => 0.0,
                'keluar' => (float)$out->jumlah
            ]);
        }

        $ledger = $ledger->sortBy('tanggal')->values();

        // Add cumulative cash balance logic to each row
        $runningBalance = $beginningBalance;
        $ledger = $ledger->map(function($item) use (&$runningBalance) {
            $runningBalance += ($item['masuk'] - $item['keluar']);
            $item['saldo'] = $runningBalance;
            return $item;
        });

        // Lifetime balance (untuk statistik header box tetap konsisten)
        $cashIn = \App\Models\OrderPayment::sum('amount');
        $cashOut = Expense::sum('jumlah');
        $cashBalance = $cashIn - $cashOut;

        return view('admin.reports_detail', compact(
            'ledger',
            'beginningBalance',
            'totalInflow',
            'totalOutflow',
            'balance',
            'cashBalance',
            'startDate',
            'endDate'
        ));
    }
}
