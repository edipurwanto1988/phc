@extends('layouts.admin')

@section('title', 'Laporan Ringkas')
@section('header')
<i class="ri-file-chart-line"></i> Laporan Ringkas Pendapatan & Jasa
@endsection

@section('content')
<!-- Filter controls -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-4">
        <div>
            <label for="year" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pilih Tahun Laporan</label>
            <select name="year" id="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
        </div>
    </form>
</div>

<!-- Year Summary Totals -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="card p-6 bg-blue-600 text-white rounded-xl shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Total Pendapatan Selesai ({{ $year }})</p>
                <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-white/10 rounded-lg text-white">
                <i class="ri-wallet-3-line text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="card p-6 bg-emerald-600 text-white rounded-xl shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 font-medium">Total Order Sukses ({{ $year }})</p>
                <p class="text-3xl font-extrabold mt-1">{{ $totalOrdersYear }} Order</p>
            </div>
            <div class="p-3 bg-white/10 rounded-lg text-white">
                <i class="ri-checkbox-circle-line text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detailed Monthly Breakdown Table (2/3 width) -->
    <div class="lg:col-span-2 card">
        <div class="p-6 border-b border-gray-200 bg-white rounded-t-xl">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="ri-calendar-line text-blue-600"></i> Rincian Pendapatan Bulanan
            </h3>
        </div>
        <div class="overflow-x-auto bg-white rounded-b-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-3 px-6 text-sm font-semibold text-gray-600">Bulan</th>
                        <th class="py-3 px-6 text-sm font-semibold text-gray-600 text-center w-32">Jumlah Order</th>
                        <th class="py-3 px-6 text-sm font-semibold text-gray-600 text-right w-44">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $monthNum => $data)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 text-sm font-semibold text-gray-800">{{ $data['month_name'] }}</td>
                        <td class="py-3 px-6 text-sm text-gray-600 text-center font-medium">{{ $data['orders'] }}</td>
                        <td class="py-3 px-6 text-sm font-bold text-gray-800 text-right">
                            Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Service Popularity Breakdown (1/3 width) -->
    <div class="card p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <i class="ri-fire-line text-blue-600"></i> Jasa Paling Populer
        </h3>
        
        <div class="space-y-4">
            @forelse($serviceBreakdown as $index => $service)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="overflow-hidden pr-2">
                    <span class="text-xs font-bold text-blue-600 block">#{{ $index + 1 }}</span>
                    <span class="text-sm font-semibold text-gray-850 truncate block">{{ $service->service_name }}</span>
                    <span class="text-[10px] text-gray-400 block">{{ $service->total_qty }} unit terpesan</span>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-xs font-bold text-gray-800 block">Rp {{ number_format($service->total_sales, 0, ',', '.') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-sm text-gray-400 font-medium">Belum ada data penjualan jasa tahun ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
