@extends('layouts.public')

@section('title', 'Dashboard Pelanggan - PHC Pekanbaru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left: Customer Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-xl p-6 space-y-6 shadow-sm">
                <!-- Profile snapshot -->
                <div class="text-center pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center font-extrabold text-2xl mx-auto mb-3 shadow-md">
                        {{ strtoupper(substr($customer->nama, 0, 1)) }}
                    </div>
                    <h3 class="font-bold text-gray-800 text-base leading-none">{{ $customer->nama }}</h3>
                    <span class="text-xs text-gray-500 block mt-1.5">Customer Portal</span>
                </div>

                <!-- Navigation links -->
                <nav class="flex flex-col gap-2.5">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-600 transition-colors">
                        <i class="ri-dashboard-line text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-calendar-todo-line text-lg"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-user-settings-line text-lg"></i> Pengaturan Profil
                    </a>
                </nav>
            </div>
        </div>

        <!-- Right: Dashboard Contents -->
        <div class="lg:col-span-3 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold flex items-center gap-2">
                <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
            </div>
            @endif

            <!-- Welcome panel -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold">Halo, {{ $customer->nama }}!</h2>
                    <p class="text-xs text-blue-100 mt-1 leading-relaxed">Selamat datang kembali di PHC. Anda dapat melihat status pembersihan teratur Anda di sini.</p>
                </div>
                <a href="/#layanan" class="bg-white text-blue-600 hover:bg-blue-50 font-bold px-4 py-2 rounded-lg text-xs transition-all shadow-sm">
                    Pesan Jasa Sekarang
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white border border-border rounded-xl shadow-sm">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                        <i class="ri-time-line text-blue-600"></i> Pesanan Terakhir
                    </h4>
                    <a href="{{ route('customer.orders.index') }}" class="text-xs text-blue-600 hover:underline font-semibold">Lihat Semua</a>
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
            </div>

            <!-- Submit Testimonial Form -->
            <div class="bg-white border border-border rounded-xl p-6 shadow-sm">
                <h4 class="font-bold text-gray-800 text-sm mb-4 pb-2 border-b border-gray-100 flex items-center gap-1.5">
                    <i class="ri-feedback-line text-blue-600"></i> Berikan Ulasan (Testimoni)
                </h4>
                
                <form method="POST" action="{{ route('customer.testimonials.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rating" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Berikan Rating Bintang</label>
                        <select name="rating" id="rating" class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white" required>
                            <option value="5">★ ★ ★ ★ ★ (5 Bintang)</option>
                            <option value="4">★ ★ ★ ★ ☆ (4 Bintang)</option>
                            <option value="3">★ ★ ★ ☆ ☆ (3 Bintang)</option>
                            <option value="2">★ ★ ☆ ☆ ☆ (2 Bintang)</option>
                            <option value="1">★ ☆ ☆ ☆ ☆ (1 Bintang)</option>
                        </select>
                    </div>
                    <div>
                        <label for="konten" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pesan Ulasan Anda</label>
                        <textarea name="konten" id="konten" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Tuliskan pengalaman Anda menggunakan layanan PHC di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-xs shadow-sm transition-all">
                        Kirim Ulasan
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
