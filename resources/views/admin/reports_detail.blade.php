@extends('layouts.admin')

@section('title', 'Laporan Detail Keuangan')
@section('header')
<i class="ri-file-list-3-line"></i> Laporan Detail Arus Kas
@endsection

@section('content')
<!-- Filter controls -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.reports.detail') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $startDate }}">
        </div>
        <div>
            <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $endDate }}">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-filter-2-line mr-1"></i> Terapkan Filter
            </button>
            <a href="{{ route('admin.reports.detail') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Periode Summary Card -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card p-6 bg-emerald-600 text-white rounded-xl shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Uang Masuk Periode Ini</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalInflow, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-white/10 rounded-lg text-white">
                <i class="ri-login-box-line text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="card p-6 bg-red-650 text-white rounded-xl shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Uang Keluar Periode Ini</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalOutflow, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-white/10 rounded-lg text-white">
                <i class="ri-logout-box-line text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-6 bg-blue-600 text-white rounded-xl shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Selisih Kas Periode Ini</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($balance, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-white/10 rounded-lg text-white">
                <i class="ri-wallet-3-line text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs / Side by Side Logs -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Uang Masuk Log -->
    <div class="card bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200 bg-white">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-checkbox-circle-fill text-emerald-600 text-lg"></i> Rincian Uang Masuk (Paid Orders)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold">
                        <th class="p-4">Tanggal Kerja</th>
                        <th class="p-4">No. Order</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inflow as $in)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-gray-700 font-semibold">{{ $in->tanggal_jadwal->translatedFormat('d M Y, H:i') }}</td>
                        <td class="p-4 font-bold text-blue-600">
                            <a href="{{ route('admin.orders.show', $in) }}" class="hover:underline">{{ $in->order_number }}</a>
                        </td>
                        <td class="p-4 text-gray-800 font-medium">{{ $in->customer->nama }}</td>
                        <td class="p-4 font-bold text-emerald-600 text-right">Rp {{ number_format($in->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-400 italic">Tidak ada transaksi uang masuk pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Uang Keluar Log -->
    <div class="card bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200 bg-white">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-error-warning-fill text-red-500 text-lg"></i> Rincian Uang Keluar (Expenses)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold">
                        <th class="p-4">Tanggal Pengeluaran</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Keterangan</th>
                        <th class="p-4">Pelaksana / Penerima</th>
                        <th class="p-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($outflow as $out)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-gray-700 font-semibold">{{ $out->tanggal->translatedFormat('d M Y') }}</td>
                        <td class="p-4"><span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 rounded-md">{{ $out->kategori_biaya }}</span></td>
                        <td class="p-4 text-gray-650">{{ $out->keterangan ?? '-' }}</td>
                        <td class="p-4 text-gray-800 font-medium">{{ $out->user->name ?? '-' }}</td>
                        <td class="p-4 font-bold text-red-500 text-right">Rp {{ number_format($out->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-400 italic">Tidak ada transaksi uang keluar pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection