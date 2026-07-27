@extends('layouts.public')

@section('title', 'Riwayat Pesanan Pelanggan - PHC Pekanbaru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left: Customer Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-xl p-6 space-y-6 shadow-sm">
                <!-- Profile snapshot -->
                <div class="text-center pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center font-extrabold text-2xl mx-auto mb-3 shadow-md">
                        {{ strtoupper(substr($orders->first()->customer->nama ?? 'C', 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-gray-800 text-base leading-none">{{ $orders->first()->customer->nama ?? 'Pelanggan' }}</h3>
                    <span class="text-xs text-gray-500 block mt-1.5">Customer Portal</span>
                </div>

                <!-- Navigation links -->
                <nav class="flex flex-col gap-2.5">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-dashboard-line text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-600 transition-colors">
                        <i class="ri-calendar-todo-line text-lg"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-user-settings-line text-lg"></i> Pengaturan Profil
                    </a>
                </nav>
            </div>
        </div>

        <!-- Right: Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white border border-border rounded-xl shadow-sm">
                <div class="p-5 border-b border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                        <i class="ri-calendar-todo-line text-blue-600"></i> Riwayat Pesanan
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">No. Order</th>
                                <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Tanggal Jadwal</th>
                                <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Total</th>
                                <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-center w-28">Status</th>
                                <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="py-3.5 px-4 text-sm font-bold text-blue-600">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-600 font-medium">
                                    {{ $order->tanggal_jadwal->translatedFormat('d M Y, H:i') }} WIB
                                </td>
                                <td class="py-3.5 px-4 text-sm font-bold text-gray-800 text-right">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                                        @elseif($order->status === 'in_progress') bg-purple-100 text-purple-700
                                        @elseif($order->status === 'completed') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="text-gray-500 hover:text-gray-700 text-xs font-semibold" title="Detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-xs text-gray-450 font-medium">Belum ada pemesanan masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                <div class="p-5 border-t border-gray-50 bg-white rounded-b-xl">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
