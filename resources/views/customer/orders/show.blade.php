@extends('layouts.public')

@section('title', 'Detail Pesanan Pelanggan - PHC Pekanbaru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left: Customer Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-xl p-6 space-y-6 shadow-sm">
                <!-- Profile snapshot -->
                <div class="text-center pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center font-extrabold text-2xl mx-auto mb-3 shadow-md">
                        {{ strtoupper(substr($order->customer->nama, 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-gray-800 text-base leading-none">{{ $order->customer->nama }}</h3>
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

        <!-- Right: Detail Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Back Link -->
            <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-primary transition-colors">
                <i class="ri-arrow-left-line"></i> Kembali ke Daftar Pesanan
            </a>

            <!-- Header Order card -->
            <div class="card p-6 bg-white border border-border rounded-xl shadow-sm space-y-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Nomor Order</span>
                        <h2 class="text-lg font-bold text-gray-855">{{ $order->order_number }}</h2>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 text-xs font-bold rounded-full 
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                            @elseif($order->status === 'in_progress') bg-purple-100 text-purple-700
                            @elseif($order->status === 'completed') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            Pengerjaan: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-full 
                            @if($order->status_bayar === 'paid') bg-green-100 text-green-700
                            @elseif($order->status_bayar === 'partial') bg-blue-100 text-blue-700
                            @else bg-red-100 text-red-700 @endif">
                            Pembayaran: {{ ucfirst($order->status_bayar) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-gray-600">
                    <div>
                        <span class="block font-semibold text-gray-400 uppercase">Jadwal Pengerjaan</span>
                        <span class="block font-bold text-gray-800 text-sm mt-0.5">{{ $order->tanggal_jadwal->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="block font-semibold text-gray-400 uppercase">Metode Pembayaran</span>
                        <span class="block font-bold text-gray-800 text-sm mt-0.5">{{ ucfirst($order->metode_bayar) }}</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Alamat Pengerjaan</span>
                    <p class="text-xs text-gray-700 font-medium mt-1 leading-relaxed">{{ $order->alamat_pengerjaan }}</p>
                </div>
            </div>

            <!-- Items Ordered list -->
            <div class="card p-6 bg-white border border-border rounded-xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2.5 mb-4 flex items-center gap-1.5">
                    <i class="ri-file-list-3-line text-blue-600"></i> Detail Jasa Dipesan
                </h3>
                
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-150">
                            <th class="py-2.5 px-4 font-bold text-gray-500 uppercase">Layanan Jasa</th>
                            <th class="py-2.5 px-4 font-bold text-gray-500 uppercase text-center w-16">Qty</th>
                            <th class="py-2.5 px-4 font-bold text-gray-500 uppercase text-right">Harga Satuan</th>
                            <th class="py-2.5 px-4 font-bold text-gray-500 uppercase text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b border-gray-50">
                            <td class="py-3 px-4 font-bold text-gray-800">
                                {{ $item->service->nama }}
                                @if($item->catatan)
                                <div class="text-[10px] text-gray-400 font-normal italic">catatan: {{ $item->catatan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-medium text-gray-650">{{ $item->qty }} {{ $item->satuan }}</td>
                            <td class="py-3 px-4 text-right font-medium text-gray-650">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                    <div class="w-full md:w-64 space-y-2 text-xs text-gray-600 text-right">
                        <div class="flex justify-between">
                            <span>Total Jasa:</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>Potongan Harga:</span>
                            <span>- Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-900 border-t border-gray-200 pt-2">
                            <span>Grand Total:</span>
                            <span class="text-blue-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active assignments -->
            <div class="card p-6 bg-white border border-border rounded-xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2.5 mb-4 flex items-center gap-1.5">
                    <i class="ri-user-star-line text-blue-600"></i> Informasi Cleaner Ditugaskan
                </h3>
                
                @forelse($order->assignments as $assignment)
                <div class="p-4 border border-gray-100 rounded-lg bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-bold text-gray-800">{{ $assignment->cleaner->name }}</div>
                        <p class="text-[10px] text-gray-400 mt-1">Cleaner akan sampai di alamat Anda sesuai jadwal pengerjaan.</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold rounded-full shrink-0 self-start md:self-auto
                        @if($assignment->status === 'assigned') bg-blue-100 text-blue-700
                        @elseif($assignment->status === 'working') bg-purple-100 text-purple-700
                        @else bg-green-100 text-green-700 @endif">
                        Status Cleaner: {{ ucfirst($assignment->status) }}
                    </span>
                </div>
                @empty
                <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-lg text-center text-xs text-yellow-750 font-semibold">
                    <i class="ri-alert-line mr-1"></i> Cleaner belum ditugaskan. Tim admin sedang memproses pesanan Anda.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
