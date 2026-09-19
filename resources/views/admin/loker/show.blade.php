@extends('layouts.admin')

@section('title', 'Detail Pelamar Loker')
@section('header')
<i class="ri-eye-line"></i> Detail Pelamar Loker
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Pelamar -->
    <div class="lg:col-span-2 card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 text-lg mb-4">Informasi Pelamar</h3>

        <dl class="space-y-3">
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Periode</dt>
                <dd class="text-sm text-gray-800">{{ $loker->periode ? $loker->periode->nama . ' (' . $loker->periode->status . ')' : '-' }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Nama</dt>
                <dd class="text-sm text-gray-800 font-medium">{{ $loker->nama }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Email</dt>
                <dd class="text-sm text-gray-800">{{ $loker->email ?: '-' }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Alamat</dt>
                <dd class="text-sm text-gray-800">{{ $loker->alamat }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Jenis Kelamin</dt>
                <dd class="text-sm text-gray-800">{{ $loker->jenis_kelamin }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">No. WhatsApp</dt>
                <dd class="text-sm text-gray-800">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $loker->no_wa) }}" target="_blank" class="text-green-600 hover:underline font-medium">
                        <i class="ri-whatsapp-line"></i> {{ $loker->no_wa }}
                    </a>
                </dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Pengalaman</dt>
                <dd class="text-sm text-gray-800 whitespace-pre-line">{{ $loker->pengalaman ?: '-' }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Keahlian Khusus</dt>
                <dd class="text-sm text-gray-800">{{ $loker->keahlian_khusus ?: '-' }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Cerita</dt>
                <dd class="text-sm text-gray-800 whitespace-pre-line">{{ $loker->cerita ?: '-' }}</dd>
            </div>
            <div class="flex border-b border-gray-100 pb-2">
                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-600">Tanggal Daftar</dt>
                <dd class="text-sm text-gray-800">{{ $loker->created_at->format('d M Y H:i') }}</dd>
            </div>
        </dl>

        <div class="flex items-center gap-3 pt-4">
            <a href="{{ route('admin.loker.edit', $loker) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-edit-line"></i> Edit
            </a>
            <a href="{{ route('admin.loker.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition-all">
                Kembali
            </a>
        </div>
    </div>

    <!-- KTP -->
    <div class="card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 text-lg mb-4">KTP Pelamar</h3>
        @if($loker->ktp)
        <a href="{{ route('admin.loker.ktp.view', $loker) }}" target="_blank" class="block">
            <img src="{{ route('admin.loker.ktp.view', $loker) }}" alt="KTP {{ $loker->nama }}" class="w-full rounded-lg border border-gray-200 object-contain bg-gray-50">
        </a>
        <p class="text-xs text-gray-400 mt-2 text-center">Klik gambar untuk membuka ukuran penuh.</p>
        @else
        <div class="text-center py-10 text-sm text-gray-400">Belum ada KTP diunggah.</div>
        @endif
    </div>
</div>
@endsection
