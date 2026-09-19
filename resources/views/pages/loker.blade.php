@extends('layouts.public')

@section('title', 'Lowongan Kerja (Loker) - PHC Pekanbaru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-8 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold truncate">Loker</span>
    </nav>

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-text-primary tracking-tight">Lowongan Kerja</h1>
        <p class="mt-3 text-text-secondary max-w-lg mx-auto text-sm leading-relaxed">
            Bergabunglah bersama tim PHC Pekanbaru. Isi formulir lamaran di bawah ini, tim kami akan menghubungi Anda melalui WhatsApp.
        </p>
        @if($periodeAktif)
        <div class="mt-4 mb-6 inline-flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
            <i class="ri-calendar-check-line"></i>
            Pendaftaran sedang dibuka: {{ $periodeAktif->nama }}
            @if($periodeAktif->tanggal_mulai || $periodeAktif->tanggal_selesai)
            <span class="font-normal text-green-600">
                ({{ $periodeAktif->tanggal_mulai ? $periodeAktif->tanggal_mulai->translatedFormat('d M Y') : '...' }} - {{ $periodeAktif->tanggal_selesai ? $periodeAktif->tanggal_selesai->translatedFormat('d M Y') : '...' }})
            </span>
            @endif
        </div>
        @else
        <div class="mt-4 mb-6 inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-medium px-4 py-2 rounded-full">
            <i class="ri-information-line"></i>
            Loker sementara masih belum tersedia. Silakan cek kembali nanti.
        </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
        <i class="ri-checkbox-circle-line text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
        <i class="ri-error-warning-line text-lg"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    @if($periodeAktif)
    <div class="bg-white border border-border rounded-2xl shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('public.loker.submit') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Nama lengkap" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="email@contoh.com">
                </div>
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" value="{{ old('no_wa') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="pengalaman" class="block text-sm font-semibold text-gray-700 mb-1">Pengalaman</label>
                <textarea name="pengalaman" id="pengalaman" rows="2" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Pengalaman kerja terkait (jika ada)">{{ old('pengalaman') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="keahlian_khusus" class="block text-sm font-semibold text-gray-700 mb-1">Keahlian Khusus</label>
                <input type="text" name="keahlian_khusus" id="keahlian_khusus" value="{{ old('keahlian_khusus') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: kaca, poles lantai, dll">
            </div>

            <div class="mb-4">
                <label for="cerita" class="block text-sm font-semibold text-gray-700 mb-1">Cerita</label>
                <textarea name="cerita" id="cerita" rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Ceritakan tentang diri Anda">{{ old('cerita') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="ktp" class="block text-sm font-semibold text-gray-700 mb-1">Upload KTP</label>
                <input type="file" name="ktp" id="ktp" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="mb-6">
                <label for="captcha_answer" class="block text-sm font-semibold text-gray-700 mb-1">Captcha <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-3">
                    <input type="text" name="captcha_answer" id="captcha_answer" class="w-40 px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Jawaban" required autocomplete="off">
                    <div class="px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-lg text-sm font-bold text-blue-700 select-none" id="captcha-question">
                        {{ $captchaQuestion }}
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">Jawab pertanyaan matematika di atas.</p>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg text-sm transition-all shadow-sm">
                    <i class="ri-send-plane-line"></i> Kirim Lamaran
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white border border-border rounded-2xl shadow-sm p-10 text-center">
        <div class="text-gray-400 text-5xl mb-4"><i class="ri-calendar-close-line"></i></div>
        <h3 class="text-lg font-bold text-text-primary">Loker Sementara Belum Tersedia</h3>
        <p class="mt-2 text-sm text-text-secondary max-w-md mx-auto leading-relaxed">
            Saat ini belum ada periode pendaftaran yang aktif. Silakan kembali lagi pada periode pendaftaran berikutnya.
        </p>
    </div>
    @endif
</div>
@endsection
