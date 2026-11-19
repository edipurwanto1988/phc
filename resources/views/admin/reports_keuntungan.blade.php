@extends('layouts.admin')

@section('title', 'Laporan Keuntungan')
@section('header')
<i class="ri-line-chart-line"></i> Laporan Keuntungan
@endsection

@php
    $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');
@endphp

@section('content')
<!-- Filter -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.reports.keuntungan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label for="start_date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $startDate }}">
        </div>
        <div>
            <label for="end_date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $endDate }}">
        </div>
        <div>
            <label for="service_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Jasa</label>
            <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">Semua Jasa</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @selected($selectedService && $selectedService->id === $service->id)>{{ $service->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-filter-2-line mr-1"></i> Terapkan Filter
            </button>
            <a href="{{ route('admin.reports.keuntungan') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card p-6 bg-emerald-600 text-white rounded-xl shadow-sm border-none">
        <p class="text-sm opacity-90 font-medium">Uang Masuk</p>
        <p class="text-3xl font-extrabold mt-1">{{ $rp($totalMasuk) }}</p>
    </div>
    <div class="card p-6 bg-rose-600 text-white rounded-xl shadow-sm border-none">
        <p class="text-sm opacity-90 font-medium">Pengeluaran</p>
        <p class="text-3xl font-extrabold mt-1">{{ $rp($totalKeluar) }}</p>
        <p class="text-xs opacity-90 mt-2">
            Gaji {{ $rp($totalGaji) }} · Operasional {{ $rp($totalOperasional) }}
        </p>
    </div>
    <div class="card p-6 {{ $laba >= 0 ? 'bg-blue-600' : 'bg-orange-600' }} text-white rounded-xl shadow-sm border-none">
        <p class="text-sm opacity-90 font-medium">{{ $laba >= 0 ? 'Keuntungan' : 'Kerugian' }}</p>
        <p class="text-3xl font-extrabold mt-1">{{ $rp($laba) }}</p>
        @if($margin !== null)
        <p class="text-xs opacity-90 mt-2">Margin {{ number_format($margin, 1, ',', '.') }}%</p>
        @endif
    </div>
</div>

<!-- Rincian per Order -->
<div class="card bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-200 flex flex-wrap gap-2 justify-between items-center">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="ri-table-line text-blue-600 text-lg"></i> Rincian per Order
            @if($selectedService)<span class="text-sm font-normal text-gray-500">— {{ $selectedService->nama }}</span>@endif
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
                    <th class="p-4">Tgl Jadwal</th>
                    <th class="p-4 text-right">Uang Masuk</th>
                    <th class="p-4 text-right">Gaji Cleaner</th>
                    <th class="p-4 text-right">Operasional</th>
                    <th class="p-4 text-right">Laba</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $row)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-500">{{ $i + 1 }}</td>
                    <td class="p-4 font-medium">
                        <a href="{{ route('admin.orders.show', $row['order']) }}" class="text-blue-600 hover:underline">{{ $row['order']->order_number }}</a>
                    </td>
                    <td class="p-4">{{ $row['order']->customer->nama ?? '-' }}</td>
                    <td class="p-4">{{ $row['order']->tanggal_jadwal ? \Carbon\Carbon::parse($row['order']->tanggal_jadwal)->translatedFormat('d M Y') : '-' }}</td>
                    <td class="p-4 text-right text-emerald-700">{{ $rp($row['masuk']) }}</td>
                    <td class="p-4 text-right text-rose-700">{{ $rp($row['gaji']) }}</td>
                    <td class="p-4 text-right text-rose-700">{{ $rp($row['operasional']) }}</td>
                    <td class="p-4 text-right font-semibold">
                        <a href="{{ route('admin.orders.profit', $row['order']) }}" class="hover:underline {{ $row['laba'] >= 0 ? 'text-gray-900' : 'text-orange-600' }}" title="Lihat profit order">{{ $rp($row['laba']) }}</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">Tidak ada order pada periode ini</td>
                </tr>
                @endforelse
            </tbody>
            @if($rows->isNotEmpty())
            <tfoot>
                <tr class="bg-gray-50 font-bold border-t border-gray-200">
                    <td colspan="4" class="p-4 text-right">Total</td>
                    <td class="p-4 text-right">{{ $rp($totalMasuk) }}</td>
                    <td class="p-4 text-right">{{ $rp($totalGaji) }}</td>
                    <td class="p-4 text-right">{{ $rp($totalOperasional) }}</td>
                    <td class="p-4 text-right">{{ $rp($laba) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
