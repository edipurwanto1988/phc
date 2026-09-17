@extends('layouts.public')

@section('title', 'Progress Pekerjaan ' . $order->order_number . ' - PHC Pekanbaru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-6 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold">Progress Pekerjaan</span>
    </nav>

    <div class="bg-white border border-border rounded-2xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-primary text-white px-6 md:px-10 py-8">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white/70">
                <i class="ri-file-list-3-line"></i> Laporan Progress Pekerjaan
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2 tracking-tight">
                {{ $order->order_number }}
            </h1>
            <p class="text-sm text-white/80 mt-1">
                {{ $order->customer->nama }} — {{ $order->alamat_pengerjaan }}
            </p>
        </div>

        <!-- Ringkasan -->
        <div class="px-6 md:px-10 py-6 border-b border-border">
            @php
                $totalRooms = $order->progressRooms->count();
                $doneRooms = $order->progressRooms->where('status', 'selesai')->count();
                $percent = $totalRooms > 0 ? round(($doneRooms / $totalRooms) * 100) : 0;
            @endphp
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-gray-700">
                        Progres Keseluruhan
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        {{ $doneRooms }} dari {{ $totalRooms }} ruangan selesai
                    </div>
                </div>
                <div class="text-3xl font-extrabold {{ $percent === 100 ? 'text-green-600' : 'text-primary' }}">
                    {{ $percent }}%
                </div>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full mt-3 overflow-hidden">
                <div class="h-full {{ $percent === 100 ? 'bg-green-500' : 'bg-primary' }} rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <!-- Daftar Lantai & Ruangan -->
        <div class="px-6 md:px-10 py-6 space-y-8">
            @forelse($grouped as $lantai => $rooms)
                <div>
                    <h2 class="flex items-center gap-2 text-base font-bold text-gray-800 mb-4">
                        <i class="ri-building-2-line text-primary"></i> {{ $lantai }}
                    </h2>
                    <div class="space-y-3">
                        @foreach($rooms as $room)
                            @php
                                $done = $room->status === 'selesai';
                                $proses = $room->status === 'proses';
                            @endphp
                            <div class="flex items-start gap-4 p-4 rounded-xl border {{ $done ? 'border-green-200 bg-green-50' : ($proses ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-gray-50') }}">
                                <div class="shrink-0 mt-0.5">
                                    @if($done)
                                        <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center">
                                            <i class="ri-check-line text-lg"></i>
                                        </span>
                                    @elseif($proses)
                                        <span class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center">
                                            <i class="ri-loader-4-line text-lg animate-spin"></i>
                                        </span>
                                    @else
                                        <span class="w-8 h-8 rounded-full bg-gray-300 text-white flex items-center justify-center">
                                            <i class="ri-time-line text-lg"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-gray-800">{{ $room->ruangan }}</span>
                                        <div class="flex items-center gap-1.5">
                                            @if($room->luas)
                                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-500">{{ rtrim(rtrim(number_format($room->luas, 2, ',', '.'), '0'), ',') }} m²</span>
                                            @endif
                                            <span class="shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full {{ $done ? 'bg-green-100 text-green-700' : ($proses ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                                {{ $done ? 'Selesai' : ($proses ? 'Progress Pengerjaan' : 'Belum Selesai') }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($room->catatan)
                                        <div class="mt-2 flex items-start gap-1.5">
                                            <i class="ri-sticky-note-line text-gray-400 text-sm mt-0.5"></i>
                                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $room->catatan }}</p>
                                        </div>
                                    @endif
                                    @if($room->masalah)
                                        <div class="mt-2 flex items-start gap-1.5 p-2.5 rounded-lg bg-red-50 border border-red-100">
                                            <i class="ri-alert-line text-red-500 text-sm mt-0.5"></i>
                                            <p class="text-sm text-red-600 leading-relaxed whitespace-pre-line">{{ $room->masalah }}</p>
                                        </div>
                                    @endif
                                    @if($room->bukti)
                                        <a href="{{ route('public.progress.bukti.view', [$order->progress_token, $room]) }}" target="_blank" class="mt-2 inline-block">
                                            <img src="{{ route('public.progress.bukti.view', [$order->progress_token, $room]) }}"
                                                class="h-20 w-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity" alt="Bukti {{ $room->ruangan }}">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-500">
                    <i class="ri-inbox-line text-5xl text-gray-300"></i>
                    <p class="mt-4 font-semibold">Belum ada data progress pekerjaan.</p>
                    <p class="text-sm mt-1">Silakan hubungi tim PHC untuk informasi lebih lanjut.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-6 md:px-10 py-5 bg-gray-50 border-t border-border text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ $order->updated_at->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>
</div>
@endsection
