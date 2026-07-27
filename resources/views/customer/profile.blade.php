@extends('layouts.public')

@section('title', 'Profil Pelanggan - PHC Pekanbaru')

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
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-dashboard-line text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ri-calendar-todo-line text-lg"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('customer.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-bold bg-blue-50 text-blue-600 transition-colors">
                        <i class="ri-user-settings-line text-lg"></i> Pengaturan Profil
                    </a>
                </nav>
            </div>
        </div>

        <!-- Right: Detail Content -->
        <div class="lg:col-span-3 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs font-semibold flex items-center gap-2">
                <i class="ri-checkbox-circle-line text-lg"></i> {{ session('success') }}
            </div>
            @endif

            <!-- Edit profile card -->
            <div class="card p-6 bg-white border border-border rounded-xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2.5 mb-6 flex items-center gap-1.5">
                    <i class="ri-user-settings-line text-blue-600"></i> Ubah Data Profil Anda
                </h3>
                
                <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" value="{{ $customer->nama }}" required>
                    </div>

                    <!-- Email (readonly) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Email Bisnis (Dari Akun Gmail - Tidak Dapat Diubah)</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-250 bg-gray-50 rounded-lg text-xs text-gray-450 focus:outline-none" value="{{ $customer->email }}" readonly>
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label for="no_wa" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor WhatsApp Aktif (Format: 628xxx)</label>
                        <input type="text" name="no_wa" id="no_wa" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" value="{{ $customer->no_wa }}" required>
                        <p class="text-[10px] text-gray-400 mt-1">Gunakan kode negara 62 di depan untuk integrasi langsung ke chat cleaner.</p>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Alamat Pengerjaan Tetap (Sesuai Rumah/Kantor Anda)</label>
                        <textarea name="alamat" id="alamat" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..." required>{{ $customer->alamat }}</textarea>
                    </div>

                    <!-- Koordinat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Latitude Koordinat (opsional)</label>
                            <input type="text" name="latitude" id="latitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" value="{{ $customer->latitude }}">
                        </div>
                        <div>
                            <label for="longitude" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Longitude Koordinat (opsional)</label>
                            <input type="text" name="longitude" id="longitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" value="{{ $customer->longitude }}">
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg text-xs shadow-sm transition-all">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
