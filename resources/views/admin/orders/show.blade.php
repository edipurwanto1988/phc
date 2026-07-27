@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('header')
<i class="ri-calendar-todo-line"></i> Pesanan: {{ $order->order_number }}
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Order Details & Services List (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Details Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-information-line text-blue-600"></i> Detail Pesanan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Pelanggan</span>
                    <a href="{{ route('admin.customers.show', $order->customer) }}" class="text-sm font-semibold text-blue-600 hover:underline block mt-0.5">
                        {{ $order->customer->nama }}
                    </a>
                    <span class="text-xs text-gray-500 block">WA: {{ $order->customer->no_wa }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Jadwal Pengerjaan</span>
                    <span class="text-sm font-semibold text-gray-800 block mt-0.5">
                        {{ $order->tanggal_jadwal->translatedFormat('d F Y, H:i') }} WIB
                    </span>
                    <span class="text-xs text-gray-500 block">Dibuat oleh: {{ $order->creator->name ?? 'Sistem' }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Alamat Pengerjaan</span>
                    <p class="text-sm font-medium text-gray-700 mt-1 leading-relaxed">{{ $order->alamat_pengerjaan }}</p>
                    @if($order->latitude && $order->longitude)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-1 font-semibold">
                        <i class="ri-map-pin-line text-red-500"></i> Lihat di Google Maps
                    </a>
                    @endif
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Catatan Order</span>
                    <p class="text-xs text-gray-600 bg-gray-50 border border-gray-100 p-2.5 rounded-lg mt-1 italic leading-relaxed">
                        {{ $order->catatan ?? 'Tidak ada catatan tambahan.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-file-list-3-line text-blue-600"></i> Layanan yang Dipesan
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Nama Jasa</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-center w-20">Qty</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Harga Satuan</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800">
                                {{ $item->service->nama }}
                                @if($item->catatan)
                                <div class="text-[11px] text-gray-400 italic font-normal">catatan: {{ $item->catatan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 text-center font-medium">
                                {{ $item->qty }} {{ $item->satuan }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 text-right font-medium">
                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-sm font-bold text-gray-800 text-right">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial summary -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                <div class="w-full md:w-64 space-y-2 text-sm text-gray-600 text-right">
                    <div class="flex justify-between">
                        <span>Total Jasa:</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-red-500">
                        <span>Potongan Harga:</span>
                        <span>- Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                        <span>Grand Total:</span>
                        <span class="text-blue-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Operational Controls & Cleaner Assignments (1/3 width) -->
    <div class="space-y-6">
        <!-- Status & Payment controls -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-settings-line text-blue-600"></i> Kontrol Status
            </h3>
            
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                @csrf
                
                <!-- Status Pengerjaan -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Order</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Status Pembayaran -->
                <div>
                    <label for="status_bayar" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Pembayaran</label>
                    <select name="status_bayar" id="status_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                        <option value="unpaid" {{ $order->status_bayar === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ $order->status_bayar === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ $order->status_bayar === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Metode Pembayaran</span>
                    <span class="text-sm font-semibold text-gray-700 block mt-0.5">{{ ucfirst($order->metode_bayar ?? 'Belum ditentukan') }}</span>
                </div>
            </form>
        </div>

        <!-- Cleaner Assignments Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-user-star-line text-blue-600"></i> Cleaner Ditugaskan
            </h3>
            
            <div class="space-y-4 mb-6">
                @forelse($order->assignments as $assignment)
                <div class="p-3 border border-gray-100 rounded-lg bg-gray-50 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-bold text-gray-800">{{ $assignment->cleaner->name }}</div>
                        <div class="text-[10px] text-gray-400">status: {{ ucfirst($assignment->status) }}</div>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                        @if($assignment->status === 'assigned') bg-blue-100 text-blue-700
                        @elseif($assignment->status === 'on_the_way') bg-yellow-100 text-yellow-700
                        @elseif($assignment->status === 'working') bg-purple-100 text-purple-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ $assignment->status }}
                    </span>
                </div>
                @empty
                <div class="text-xs text-red-500 font-semibold p-3 border border-red-50 bg-red-50 rounded-lg text-center">
                    <i class="ri-alert-line mr-1"></i> Belum ada cleaner yang ditugaskan ke order ini.
                </div>
                @endforelse
            </div>

            <!-- Assignment form -->
            <form method="POST" action="{{ route('admin.orders.assign', $order) }}" class="pt-4 border-t border-gray-100">
                @csrf
                <label for="cleaner_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tugaskan Cleaner Baru</label>
                <div class="flex gap-2">
                    <select name="cleaner_id" id="cleaner_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="">-- Pilih Cleaner --</option>
                        @foreach($cleaners as $cleaner)
                        <option value="{{ $cleaner->id }}">{{ $cleaner->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all shadow-sm">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
