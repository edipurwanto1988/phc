@extends('layouts.admin')

@section('title', auth()->user()->role->name === 'Cleaner' ? 'Riwayat Gaji' : 'Manajemen Pengeluaran')
@section('header')
<i class="ri-money-dollar-circle-line"></i> {{ auth()->user()->role->name === 'Cleaner' ? 'Riwayat Gaji Diterima' : 'Manajemen Pengeluaran' }}
@endsection

@section('content')
<!-- Search & Filter Control -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.expenses.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <!-- Search -->
        <div>
            <label for="search" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Pengeluaran</label>
            <input type="text" name="search" id="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Kategori, Keterangan, Pelaksana..." value="{{ request('search') }}">
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal</label>
            <input type="date" name="date" id="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ request('date') }}">
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-filter-2-line mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.expenses.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Expenses Table -->
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">{{ auth()->user()->role->name === 'Cleaner' ? 'Daftar Gaji yang Diterima' : 'Daftar Pengeluaran Operasional' }}</h3>
        @if(auth()->user()->hasPermission('manage_expenses'))
        <a href="{{ route('admin.expenses.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Pengeluaran
        </a>
        @endif
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Tanggal</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Kategori Biaya</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Pelaksana</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Keterangan</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-right">Jumlah</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSemua = 0; @endphp
                @forelse($expenses as $expense)
                @php $totalSemua += $expense->jumlah; @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-800 font-semibold">
                        {{ $expense->tanggal->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-lg">
                            {{ $expense->kategori_biaya }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-medium text-gray-800">{{ $expense->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $expense->user->role->name ?? 'User' }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $expense->keterangan ?? '-' }}
                    </td>
                    <td class="py-4 px-6 text-sm font-bold text-gray-800 text-right">
                        Rp {{ number_format($expense->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Lihat Detail">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            @if($expense->is_gaji)
                            <a href="{{ route('admin.expenses.download-slip', $expense) }}" class="text-emerald-600 hover:text-emerald-800 transition-colors" title="Download Slip Gaji">
                                <i class="ri-download-2-line text-lg"></i>
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('manage_expenses'))
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Ubah">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data pengeluaran masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($expenses->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $expenses->links() }}
    </div>
    @endif
</div>
@endsection