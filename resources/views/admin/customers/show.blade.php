@extends('layouts.admin')

@section('title', 'Detail Customer')
@section('header')
<i class="ri-user-line"></i> Profil Customer: {{ $customer->nama }}
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Customer Info card (1/3 width) -->
    <div class="card p-6 bg-white border border-gray-200 rounded-xl flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-2xl">
                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $customer->nama }}</h3>
                    <p class="text-xs text-gray-400">ID Customer: #{{ $customer->id }}</p>
                    <span class="mt-1 inline-block px-2 py-0.5 text-xs font-semibold rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $customer->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <!-- No WA -->
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">No. WhatsApp</span>
                    <a href="https://wa.me/{{ $customer->no_wa }}" target="_blank" class="text-sm font-semibold text-blue-600 hover:underline flex items-center gap-1 mt-0.5">
                        <i class="ri-whatsapp-line text-green-500"></i> {{ $customer->no_wa }}
                    </a>
                </div>
                <!-- Email -->
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Alamat Email</span>
                    <span class="text-sm font-medium text-gray-700">{{ $customer->email ?? '-' }}</span>
                </div>
                <!-- Alamat -->
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Alamat Lengkap</span>
                    <span class="text-sm font-medium text-gray-700 block leading-relaxed">{{ $customer->alamat }}</span>
                    @if($customer->kelurahan || $customer->kecamatan || $customer->kota)
                    <span class="text-xs text-gray-500 block mt-1">
                        {{ $customer->kelurahan ? $customer->kelurahan . ', ' : '' }}
                        {{ $customer->kecamatan ? $customer->kecamatan . ', ' : '' }}
                        {{ $customer->kota }}
                    </span>
                    @endif
                </div>
                <!-- Koordinat -->
                @if($customer->latitude && $customer->longitude)
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Koordinat Lokasi</span>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $customer->latitude }},{{ $customer->longitude }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-0.5">
                        <i class="ri-map-pin-line text-red-500"></i> Lat: {{ $customer->latitude }}, Lng: {{ $customer->longitude }}
                    </a>
                </div>
                @endif
                <!-- Sumber Info -->
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Sumber Informasi</span>
                    <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full">{{ $customer->sumber_info ?? 'Tidak diketahui' }}</span>
                </div>
                <!-- Catatan -->
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Catatan Khusus</span>
                    <p class="text-xs text-gray-600 bg-yellow-50 border border-yellow-100 p-2.5 rounded-lg mt-1 italic leading-relaxed">
                        {{ $customer->catatan ?? 'Tidak ada catatan khusus.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-100 flex gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="flex-1 btn border border-gray-300 hover:bg-gray-50 text-gray-700 text-center py-2 rounded-lg text-sm transition-all font-semibold">
                <i class="ri-edit-line"></i> Edit Profil
            </a>
        </div>
    </div>

    <!-- Order History list (2/3 width) -->
    <div class="lg:col-span-2 card p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <i class="ri-history-line text-blue-600"></i> Riwayat Transaksi & Order
        </h3>
        
        <div class="overflow-y-auto max-h-[500px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">No. Order</th>
                        <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Tanggal Order</th>
                        <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Jadwal</th>
                        <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Grand Total</th>
                        <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-sm font-semibold text-blue-600">
                            <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $order->tanggal_order->format('d M Y') }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $order->tanggal_jadwal->format('d M Y, H:i') }}</td>
                        <td class="py-3 px-4 text-sm font-bold text-gray-800 text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-700
                                @elseif($order->status === 'in_progress') bg-purple-100 text-purple-700
                                @elseif($order->status === 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-sm text-gray-400 font-medium">Customer ini belum pernah memesan layanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
