<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Exports\OrdersExport;
use App\Exports\FinancialReportExport;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function index()
    {
        return view('admin.exports.index');
    }

    public function customers(Request $request)
    {
        $filename = 'Data_Pelanggan_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new CustomersExport($request->start_date, $request->end_date), $filename);
    }

    public function orders(Request $request)
    {
        $filename = 'Data_Transaksi_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new OrdersExport($request->start_date, $request->end_date, $request->status), $filename);
    }

    public function financials(Request $request)
    {
        $filename = 'Laporan_Keuangan_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new FinancialReportExport($request->start_date, $request->end_date), $filename);
    }
}
