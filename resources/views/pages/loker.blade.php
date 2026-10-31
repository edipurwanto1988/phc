@extends('layouts.public')

@section('title', 'Lowongan Kerja (Loker) - PHC Pekanbaru')

@section('content')
<div class="page-hero">
    <div class="absolute inset-0 bg-dots mask-fade-b pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 pt-10 pb-12 md:pt-14 md:pb-16">
        <!-- Breadcrumb -->
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold">Loker</span>
        </nav>
        <div class="max-w-2xl">
            <span class="eyebrow">Karier</span>
            <h1 class="section-title">Lowongan Kerja</h1>
            <p class="section-lead">
                Bergabunglah bersama tim PHC Pekanbaru. Isi formulir lamaran di bawah ini, tim kami akan menghubungi Anda melalui WhatsApp.
            </p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 md:py-14">
    <!-- Header -->
    <div class="text-center">
        @if($periodeAktif)
        <div class="hidden mt-4 mb-6 inline-flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
            <i class="ri-calendar-check-line"></i>
            Pendaftaran sedang dibuka: {{ $periodeAktif->nama }}
            @if($periodeAktif->tanggal_mulai || $periodeAktif->tanggal_selesai)
            <span class="font-normal text-green-600">
                ({{ $periodeAktif->tanggal_mulai ? $periodeAktif->tanggal_mulai->translatedFormat('d M Y') : '...' }} - {{ $periodeAktif->tanggal_selesai ? $periodeAktif->tanggal_selesai->translatedFormat('d M Y') : '...' }})
            </span>
            @endif
        </div>
        @else
        <div class="hidden mt-4 mb-6 inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-medium px-4 py-2 rounded-full">
            <i class="ri-information-line"></i>
            Loker sementara masih belum tersedia. Silakan cek kembali nanti.
        </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-2">
        <i class="ri-checkbox-circle-line text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-2">
        <i class="ri-error-warning-line text-lg"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    @if($periodeAktif)
    <div class="card-soft p-6 md:p-10">
        <form method="POST" action="{{ route('public.loker.submit') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nama" class="block text-sm font-semibold text-text-primary mb-1.5">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Nama lengkap" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-text-primary mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="email@contoh.com">
                </div>
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-sm font-semibold text-text-primary mb-1.5">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="2" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-text-primary mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm bg-white" required>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="no_wa" class="block text-sm font-semibold text-text-primary mb-1.5">No. WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" value="{{ old('no_wa') }}" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="08xxxxxxxxxx" required>
                    <p id="wa_error" class="hidden text-xs text-red-600 mt-1"><i class="ri-error-warning-line"></i> Nomor WhatsApp ini sudah terdaftar.</p>
                </div>
            </div>

            <div class="mb-4">
                <label for="pengalaman" class="block text-sm font-semibold text-text-primary mb-1.5">Pengalaman</label>
                <textarea name="pengalaman" id="pengalaman" rows="2" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Pengalaman kerja terkait (jika ada)">{{ old('pengalaman') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="keahlian_khusus" class="block text-sm font-semibold text-text-primary mb-1.5">Keahlian Khusus</label>
                <input type="text" name="keahlian_khusus" id="keahlian_khusus" value="{{ old('keahlian_khusus') }}" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Contoh: kaca, poles lantai, dll">
            </div>

            <div class="mb-4">
                <label for="cerita" class="block text-sm font-semibold text-text-primary mb-1.5">Cerita</label>
                <textarea name="cerita" id="cerita" rows="3" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Ceritakan tentang diri Anda">{{ old('cerita') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="ktp" class="block text-sm font-semibold text-text-primary mb-1.5">Upload KTP</label>
                <input type="file" name="ktp" id="ktp" accept="image/*" class="w-full text-sm text-text-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/15 file:transition-colors">
            </div>

            <div class="mb-6">
                <label for="captcha_answer" class="block text-sm font-semibold text-text-primary mb-1.5">Captcha <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-3">
                    <input type="text" name="captcha_answer" id="captcha_answer" class="w-40 px-4 py-3 bg-white border border-border rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="Jawaban" required autocomplete="off">
                    <div class="px-4 py-3 bg-primary/5 border border-primary/20 rounded-xl text-sm font-bold text-primary select-none" id="captcha-question">
                        {{ $captchaQuestion }}
                    </div>
                </div>
                <p class="text-xs text-text-secondary mt-1.5">Jawab pertanyaan matematika di atas.</p>
            </div>

            <div class="pt-6 border-t border-border/60">
                <button type="submit" id="submit_btn" class="btn-pill btn-pill-primary w-full md:w-auto !px-8 !py-3.5">
                    <i class="ri-send-plane-line"></i> Kirim Lamaran
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noWaInput = document.getElementById('no_wa');
            const submitBtn = document.getElementById('submit_btn');
            const waError = document.getElementById('wa_error');

            let checkTimeout;

            noWaInput.addEventListener('input', function() {
                clearTimeout(checkTimeout);
                waError.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                const noWa = this.value.trim();
                if (noWa.length < 8) return;

                checkTimeout = setTimeout(() => {
                    fetch(`{{ route('public.loker.check-wa') }}?no_wa=${encodeURIComponent(noWa)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                waError.classList.remove('hidden');
                                submitBtn.disabled = true;
                                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            }
                        })
                        .catch(err => console.error('Error checking WA:', err));
                }, 500);
            });
        });
    </script>
    @endpush
    @else
    <div class="card-soft p-10 md:p-14 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-surface border border-border/70 flex items-center justify-center text-text-secondary text-3xl mb-5"><i class="ri-calendar-close-line"></i></div>
        <h3 class="text-lg font-bold text-text-primary">Loker Sementara Belum Tersedia</h3>
        <p class="mt-2 text-sm text-text-secondary max-w-md mx-auto leading-relaxed">
            Saat ini belum ada periode pendaftaran yang aktif. Silakan kembali lagi pada periode pendaftaran berikutnya.
        </p>
    </div>
    @endif
</div>
@endsection
