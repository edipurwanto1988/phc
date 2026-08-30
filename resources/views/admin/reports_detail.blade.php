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

<!-- Combined Ledger Log Table -->
<div class="card bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-gray-200 bg-white flex justify-between items-center">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="ri-swap-box-line text-blue-600 text-lg"></i> Jurnal Buku Besar Gabungan (Arus Kas Masuk & Keluar)
        </h3>
        <div class="text-xs text-gray-500">
            Saldo Awal Periode: <span class="font-bold text-gray-800">Rp {{ number_format($beginningBalance, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold">
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Jenis Transaksi</th>
                    <th class="p-4">Keterangan / Deskripsi</th>
                    <th class="p-4">Pelaksana / Penanggung Jawab</th>
                    <th class="p-4 text-right text-emerald-600">Uang Masuk (+)</th>
                    <th class="p-4 text-right text-red-500">Uang Keluar (-)</th>
                    <th class="p-4 text-right">Saldo Kas</th>
                </tr>
            </thead>
            <tbody>
                <!-- Beginning Balance row -->
                <tr class="border-b border-gray-100 bg-gray-50 font-semibold italic text-gray-500">
                    <td class="p-4">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</td>
                    <td class="p-4">Saldo Awal</td>
                    <td class="p-4">Saldo kumulatif sebelum tanggal {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</td>
                    <td class="p-4">-</td>
                    <td class="p-4 text-right">-</td>
                    <td class="p-4 text-right">-</td>
                    <td class="p-4 text-right text-gray-700 font-bold">Rp {{ number_format($beginningBalance, 0, ',', '.') }}</td>
                </tr>

                @forelse($ledger as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-gray-700 font-semibold">{{ $item['tanggal']->translatedFormat('d M Y') }}</td>
                    <td class="p-4">
                        @if($item['tipe'] === 'uang_masuk')
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-md">Uang Masuk</span>
                        @else
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-700 rounded-md">Uang Keluar</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($item['tipe'] === 'uang_masuk')
                        <a href="{{ $item['ref'] }}" class="font-bold text-blue-600 hover:underline">{{ $item['keterangan'] }}</a>
                        @else
                        <a href="{{ $item['ref'] }}" class="font-semibold text-gray-700 hover:underline">{{ $item['keterangan'] }}</a>
                        @endif
                    </td>
                    <td class="p-4 text-gray-650">{{ $item['penerima_pelaksana'] }}</td>
                    <td class="p-4 font-bold text-emerald-600 text-right">
                        @if($item['masuk'] > 0)
                        Rp {{ number_format($item['masuk'], 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-4 font-bold text-red-500 text-right">
                        @if($item['keluar'] > 0)
                        Rp {{ number_format($item['keluar'], 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-4 font-bold text-right text-gray-800">Rp {{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-400 italic">Tidak ada transaksi arus kas masuk atau keluar pada periode ini.</td>
                </tr>
                @endforelse

                <!-- Final balance row -->
                @if($ledger->count() > 0)
                <tr class="bg-blue-50 border-t-2 border-blue-200 font-bold text-sm text-blue-900">
                    <td class="p-4" colspan="4">Total Akumulasi Periode (Saldo Akhir Kas)</td>
                    <td class="p-4 text-right text-emerald-600">Rp {{ number_format($totalInflow, 0, ',', '.') }}</td>
                    <td class="p-4 text-right text-red-600">Rp {{ number_format($totalOutflow, 0, ',', '.') }}</td>
                    <td class="p-4 text-right text-blue-700 text-base">Rp {{ number_format($ledger->last()['saldo'], 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection