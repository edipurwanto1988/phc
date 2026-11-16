@extends('layouts.admin')

@section('title', 'Laporan Penggajian')
@section('header')
<i class="ri-hand-coin-line"></i> Laporan Penggajian
@endsection

@section('content')
<!-- Filter -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.reports.penggajian') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
            <a href="{{ route('admin.reports.penggajian') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="card p-6 bg-blue-600 text-white rounded-xl shadow-sm border-none">
        <p class="text-sm opacity-90 font-medium">Total Gaji</p>
        <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
    </div>
    <div class="card p-6 bg-emerald-600 text-white rounded-xl shadow-sm border-none">
        <p class="text-sm opacity-90 font-medium">Total Sudah Dibayar</p>
        <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Tabel -->
<div class="card bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="ri-table-line text-blue-600 text-lg"></i> Rincian Penggajian
        </h3>
        <div class="text-xs text-gray-500">
            Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold">
                    <th class="p-4">No</th>
                    <th class="p-4">No. Order</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Cleaner</th>
                    <th class="p-4">Tanggal Penggajian</th>
                    <th class="p-4 text-right">Gaji</th>
                    <th class="p-4 text-right">Sudah Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $row)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500">{{ $i + 1 }}</td>
                    <td class="p-4 font-medium">{{ $row['order_number'] }}</td>
                    <td class="p-4">{{ $row['customer'] }}</td>
                    <td class="p-4">{{ $row['cleaner'] }}</td>
                    <td class="p-4">{{ $row['tanggal_penggajian']?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td class="p-4 text-right">Rp {{ number_format($row['gaji'], 0, ',', '.') }}</td>
                    <td class="p-4 text-right text-emerald-700 font-medium">Rp {{ number_format($row['sudah_dibayar'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500">Tidak ada pembayaran gaji pada periode ini</td>
                </tr>
                @endforelse
            </tbody>
            @if($rows->isNotEmpty())
            <tfoot>
                <tr class="bg-gray-50 font-bold border-t border-gray-200">
                    <td colspan="5" class="p-4 text-right">Total</td>
                    <td class="p-4 text-right">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                    <td class="p-4 text-right">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
