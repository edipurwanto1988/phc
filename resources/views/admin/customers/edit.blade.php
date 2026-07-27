@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('header')
<i class="ri-edit-box-line"></i> Edit Data Customer
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf
            @method('PUT')

            <!-- Section 1: Data Utama -->
            <h4 class="text-sm font-bold text-blue-600 mb-4 border-b border-gray-100 pb-2">Informasi Kontak Utama</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->nama }}" required>
                </div>
                <!-- Nomor WhatsApp -->
                <div>
                    <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp (format: 628xxx)</label>
                    <input type="text" name="no_wa" id="no_wa" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->no_wa }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email (opsional)</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->email }}" placeholder="Contoh: budi@gmail.com">
                </div>
                <!-- Link Akun User Login -->
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Hubungkan ke Akun Portal (opsional)</label>
                    <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Akun User --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $customer->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1">Hanya menampilkan user dengan role 'Customer' yang belum terhubung ke profil mana pun.</p>
                </div>
            </div>

            <!-- Section 2: Informasi Alamat -->
            <h4 class="text-sm font-bold text-blue-600 mt-6 mb-4 border-b border-gray-100 pb-2">Informasi Alamat & Lokasi</h4>

            <div class="mb-4">
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>{{ $customer->alamat }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Kelurahan -->
                <div>
                    <label for="kelurahan" class="block text-sm font-semibold text-gray-700 mb-1">Kelurahan</label>
                    <input type="text" name="kelurahan" id="kelurahan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->kelurahan }}">
                </div>
                <!-- Kecamatan -->
                <div>
                    <label for="kecamatan" class="block text-sm font-semibold text-gray-700 mb-1">Kecamatan</label>
                    <input type="text" name="kecamatan" id="kecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->kecamatan }}">
                </div>
                <!-- Kota -->
                <div>
                    <label for="kota" class="block text-sm font-semibold text-gray-700 mb-1">Kota / Kabupaten</label>
                    <input type="text" name="kota" id="kota" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->kota }}" required>
                </div>
            </div>

            <!-- Koordinat Maps -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Koordinat Lokasi (opsional)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-xs font-bold text-gray-400">LAT</span>
                            <input type="text" name="latitude" id="latitude" class="w-full pl-11 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->latitude }}" placeholder="Contoh: 0.46820000">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center gap-2">
                            <div class="relative flex-1 flex items-center">
                                <span class="absolute left-3 text-xs font-bold text-gray-400">LNG</span>
                                <input type="text" name="longitude" id="longitude" class="w-full pl-11 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $customer->longitude }}" placeholder="Contoh: 101.37890000">
                            </div>
                            <button type="button" onclick="getCurrentCoordinates()" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-blue-600 font-semibold px-3 py-2 rounded-lg text-sm flex items-center gap-1.5 shrink-0 shadow-sm" title="Deteksi Lokasi GPS">
                                <i class="ri-map-pin-line text-base"></i> Deteksi Koordinat
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">Gunakan tombol "Deteksi Koordinat" untuk mendapatkan titik koordinat GPS dari perangkat Anda saat ini.</p>
            </div>

            <!-- Section 3: Data Tambahan -->
            <h4 class="text-sm font-bold text-blue-600 mt-6 mb-4 border-b border-gray-100 pb-2">Informasi Tambahan</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Sumber Info -->
                <div>
                    <label for="sumber_info" class="block text-sm font-semibold text-gray-700 mb-1">Sumber Info</label>
                    <select name="sumber_info" id="sumber_info" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Sumber Info --</option>
                        <option value="Instagram" {{ $customer->sumber_info === 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="Facebook" {{ $customer->sumber_info === 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="Google Maps" {{ $customer->sumber_info === 'Google Maps' ? 'selected' : '' }}>Google Maps / Search</option>
                        <option value="Brosur / Spanduk" {{ $customer->sumber_info === 'Brosur / Spanduk' ? 'selected' : '' }}>Brosur / Spanduk</option>
                        <option value="Teman / Kerabat" {{ $customer->sumber_info === 'Teman / Kerabat' ? 'selected' : '' }}>Rekomendasi Teman / Kerabat</option>
                        <option value="Lainnya" {{ $customer->sumber_info === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <!-- Status Aktif -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status Keaktifan</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                        <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $customer->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Catatan Khusus -->
            <div class="mb-6">
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan" id="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Catatan khusus...">{{ $customer->catatan }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
function getCurrentCoordinates() {
    const btn = document.querySelector('[onclick="getCurrentCoordinates()"]');
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung fitur GPS / Geolocation.');
        return;
    }

    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-base"></i> Mendeteksi...';
    btn.disabled = true;
    btn.classList.add('opacity-60');

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
            document.getElementById('longitude').value = position.coords.longitude.toFixed(8);

            btn.innerHTML = '<i class="ri-checkbox-circle-line text-base text-green-600"></i> Berhasil!';
            btn.classList.remove('opacity-60');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 2000);
        },
        function (error) {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            btn.classList.remove('opacity-60');

            const messages = {
                1: 'Akses lokasi ditolak. Harap izinkan akses lokasi di browser Anda.',
                2: 'Lokasi tidak dapat ditentukan. Pastikan GPS aktif.',
                3: 'Waktu deteksi habis. Coba lagi.',
            };
            alert(messages[error.code] || 'Gagal mendeteksi koordinat.');
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}
</script>
@endsection
