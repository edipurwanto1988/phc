@extends('layouts.admin')

@section('title', 'Daftar Pesanan')
@section('header')
<i class="ri-calendar-todo-line"></i> Manajemen Pesanan (Orders)
@endsection

@section('content')
<!-- Search & Filter Control -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Search -->
        <div>
            <label for="search" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Pesanan</label>
            <input type="text" name="search" id="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="No. Order / Nama Customer..." value="{{ request('search') }}">
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Pengerjaan</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Jadwal</label>
            <input type="date" name="date" id="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ request('date') }}">
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-filter-2-line mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Order PHC</h3>
        @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('create_orders'))
        <a href="{{ route('admin.orders.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Input Order Baru
        </a>
        @endif
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">No. Order</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Customer</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Jadwal Pengerjaan</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-right">{{ auth()->user()->role->name === 'Cleaner' ? 'Gaji Diterima' : 'Grand Total' }}</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center">Status</th>
                    @if(auth()->user()->role->name !== 'Cleaner')
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center">Pembayaran</th>
                    @endif
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Cleaner Ditugaskan</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-20">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-bold text-blue-600">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer->nama }}</div>
                        <div class="text-xs text-gray-500">{{ $order->customer->no_wa }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                        {{ $order->tanggal_jadwal->translatedFormat('d M Y, H:i') }} WIB
                    </td>
                    <td class="py-4 px-6 text-sm font-bold text-gray-850 text-right">
                        @if(auth()->user()->role->name === 'Cleaner')
                            @php
                                $myAssign = $order->assignments->where('user_id', auth()->id())->first();
                            @endphp
                            Rp {{ number_format($myAssign->gaji ?? 0, 0, ',', '.') }}
                            <div class="text-[9px] {{ ($myAssign->status_gaji ?? '') === 'sudah_dibayar' ? 'text-green-600' : 'text-red-500' }} font-bold">
                                {{ ($myAssign->status_gaji ?? '') === 'sudah_dibayar' ? 'Sudah' : 'Belum' }} Dibayar
                            </div>
                        @else
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                            @elseif($order->status === 'in_progress') bg-purple-100 text-purple-700
                            @elseif($order->status === 'completed') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    @if(auth()->user()->role->name !== 'Cleaner')
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            @if($order->status_bayar === 'paid') bg-green-100 text-green-700
                            @elseif($order->status_bayar === 'partial') bg-blue-100 text-blue-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($order->status_bayar) }}
                        </span>
                    </td>
                    @endif
                    <td class="py-4 px-6 text-sm text-gray-650">
                        @php 
                            // Sorted assignments (by sort_order then id)
                            $assignments = $order->assignments;
                            $pic = $assignments->first();
                            $totalCleaners = $assignments->count();
                        @endphp
                        @if($pic)
                            <div class="font-bold text-gray-800 flex items-center gap-1.5">
                                <span>{{ $pic->cleaner->name }}</span>
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-md tracking-wider">PIC</span>
                            </div>
                            @if($totalCleaners > 1)
                                <div class="text-[10px] text-blue-600 font-bold mt-0.5 flex items-center gap-1">
                                    <i class="ri-group-line"></i> +{{ $totalCleaners - 1 }} Team
                                </div>
                            @endif
                        @else
                            <span class="text-xs text-red-500 font-semibold flex items-center gap-1">
                                <i class="ri-alert-line"></i> Belum ditugaskan
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-500 hover:text-gray-750 transition-colors" title="Lihat Detail">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders'))
                            <a href="{{ route('admin.orders.download-invoice', $order) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Download Invoice">
                                <i class="ri-download-2-line text-lg"></i>
                            </a>
                            @endif
                            @if((auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('delete_orders')) && $order->status_bayar !== 'paid')
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data order ini?')">
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
                    <td colspan="8" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
