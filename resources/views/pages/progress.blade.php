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

    <div class="bg-white border border-border rounded-2xl shadow-sm overflow-hidden" x-data="{ tab: 'progress' }">
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

        <!-- Tabs -->
        <div class="flex border-b border-border bg-gray-50">
            <button type="button" @click="tab = 'progress'"
                class="flex-1 py-3.5 px-4 text-sm font-semibold transition-colors border-b-2"
                :class="tab === 'progress' ? 'text-primary border-primary bg-white' : 'text-gray-500 border-transparent hover:text-gray-700'">
                <i class="ri-bar-chart-box-line mr-1.5"></i> Progress Pekerjaan
            </button>
            <button type="button" @click="tab = 'cleaner'"
                class="flex-1 py-3.5 px-4 text-sm font-semibold transition-colors border-b-2"
                :class="tab === 'cleaner' ? 'text-primary border-primary bg-white' : 'text-gray-500 border-transparent hover:text-gray-700'">
                <i class="ri-team-line mr-1.5"></i> Profil Cleaner
            </button>
        </div>

        <!-- Tab 1: Progress -->
        <div x-show="tab === 'progress'">
        <!-- Ringkasan -->
        <div class="px-6 md:px-10 py-6 border-b border-border">
            @php
                $totalRooms = $order->progressRooms->count();
                $doneRooms = $order->progressRooms->where('status', 'selesai')->count();
                $prosesRooms = $order->progressRooms->where('status', 'proses')->count();
                $belumRooms = $order->progressRooms->where('status', 'belum')->count();
                $totalLuas = $order->progressRooms->sum('luas');
                $percent = $totalRooms > 0 ? round(($doneRooms / $totalRooms) * 100) : 0;
            @endphp

            {{-- Ringkasan Project --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-full">
                    <i class="ri-door-line text-gray-500 text-sm"></i>
                    <span class="text-sm font-bold text-gray-800">{{ $totalRooms }}</span>
                    <span class="text-xs text-gray-500">Ruangan</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full">
                    <i class="ri-checkbox-circle-line text-green-600 text-sm"></i>
                    <span class="text-sm font-bold text-green-700">{{ $doneRooms }}</span>
                    <span class="text-xs text-green-600">Selesai</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full">
                    <i class="ri-loader-4-line text-blue-600 text-sm"></i>
                    <span class="text-sm font-bold text-blue-700">{{ $prosesRooms }}</span>
                    <span class="text-xs text-blue-600">Progress</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full">
                    <i class="ri-time-line text-amber-600 text-sm"></i>
                    <span class="text-sm font-bold text-amber-700">{{ $belumRooms }}</span>
                    <span class="text-xs text-amber-600">Belum</span>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 border border-violet-200 rounded-full">
                    <i class="ri-ruler-line text-violet-600 text-sm"></i>
                    <span class="text-sm font-bold text-violet-700">{{ rtrim(rtrim(number_format($totalLuas, 2, ',', '.'), '0'), ',') }} m²</span>
                    <span class="text-xs text-violet-600">Luas</span>
                </div>
            </div>

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
                <div class="border border-border rounded-xl p-4" x-data="{ open: false }">
                    <div class="flex items-center justify-between gap-2 cursor-pointer select-none" @click="open = !open">
                        <h2 class="flex items-center gap-2 text-base font-bold text-gray-800">
                            <i class="ri-building-2-line text-primary"></i> {{ $lantai }}
                            @if($rooms->sum('luas'))
                            <span class="text-xs font-semibold text-gray-400">· {{ rtrim(rtrim(number_format($rooms->sum('luas'), 2, ',', '.'), '0'), ',') }} m²</span>
                            @endif
                        </h2>
                        <button type="button" class="shrink-0 p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Minimize / Expand">
                            <i class="ri-arrow-up-s-line transition-transform" :class="open ? '' : 'rotate-180'"></i>
                        </button>
                    </div>
                    <div class="space-y-3 mt-4" x-show="open" x-cloak>
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
                                    @if($room->buktiPhotos->count() > 0)
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach($room->buktiPhotos as $bukti)
                                                <a href="{{ route('public.progress.bukti.view', [$order->progress_token, $bukti]) }}" target="_blank">
                                                    <img src="{{ route('public.progress.bukti.view', [$order->progress_token, $bukti]) }}"
                                                        class="h-20 w-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity" alt="Bukti {{ $room->ruangan }}">
                                                </a>
                                            @endforeach
                                        </div>
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
        </div>

        <!-- Tab 2: Profil Cleaner -->
        <div x-show="tab === 'cleaner'" x-cloak>
            <div class="px-6 md:px-10 py-8">
                @php
                    $assignments = $order->assignments;
                @endphp
                @if($assignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignments as $index => $assignment)
                            @php $cleaner = $assignment->cleaner; @endphp
                            @if(!$cleaner) @continue @endif
                            <div class="flex items-center gap-4 p-4 rounded-xl border {{ $index === 0 ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                                <div class="shrink-0 relative">
                                    @if($cleaner->foto)
                                        <img src="{{ route('public.progress.cleaner.foto', [$order->progress_token, $cleaner]) }}"
                                            alt="{{ $cleaner->name }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 {{ $index === 0 ? 'border-blue-400' : 'border-gray-200' }} shadow-sm">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl border-2 {{ $index === 0 ? 'border-blue-400' : 'border-gray-200' }} shadow-sm">
                                            {{ strtoupper(substr($cleaner->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    @if($index === 0)
                                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 px-2 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-md tracking-wider whitespace-nowrap">PIC</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-800">{{ $cleaner->name }}</span>
                                        @if($cleaner->phc_id)
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-gray-200 text-gray-600 tracking-wide">{{ $cleaner->phc_id }}</span>
                                        @endif
                                    </div>
                                    @if($cleaner->jenis)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $cleaner->jenis === 'Tetap' ? 'Cleaner Tetap' : 'Cleaner Mitra' }}
                                        </div>
                                    @endif
                                    @if($cleaner->keahlian)
                                        <div class="mt-2 flex items-start gap-1.5">
                                            <i class="ri-award-line text-primary text-sm mt-0.5"></i>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $cleaner->keahlian }}</p>
                                        </div>
                                    @endif
                                </div>
                                @if($index === 0)
                                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">PIC / Leader</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 text-gray-500">
                        <i class="ri-team-line text-5xl text-gray-300"></i>
                        <p class="mt-4 font-semibold">Belum ada cleaner yang ditugaskan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 md:px-10 py-5 bg-gray-50 border-t border-border text-center text-xs text-gray-500">
            Terakhir diperbarui: {{ $order->updated_at->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>
</div>
@endsection
