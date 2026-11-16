@extends('layouts.admin')

@section('title', 'Detail User - ' . $user->name)
@section('header')
<i class="ri-user-line"></i> Detail User
@endsection
@section('content')
<div class="space-y-6">
    {{-- Data cleaner / user --}}
    <div class="card">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold">{{ $user->name }}</h2>
            <span class="px-2 py-1 text-xs rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
            <div class="flex justify-between md:block"><span class="text-gray-500">Username</span><p class="font-medium">{{ $user->username }}</p></div>
            <div class="flex justify-between md:block"><span class="text-gray-500">Email</span><p class="font-medium">{{ $user->email }}</p></div>
            <div class="flex justify-between md:block"><span class="text-gray-500">Role</span><p class="font-medium">{{ $user->role->name ?? '-' }}</p></div>
            <div class="flex justify-between md:block"><span class="text-gray-500">PHC ID</span><p class="font-medium">{{ $user->phc_id ?? '-' }}</p></div>
            <div class="flex justify-between md:block"><span class="text-gray-500">Jenis</span><p class="font-medium">{{ $user->jenis ?? '-' }}</p></div>
            <div class="flex justify-between md:block"><span class="text-gray-500">Level</span><p class="font-medium">{{ $user->level ?? '-' }}</p></div>
            <div class="md:col-span-2"><span class="text-gray-500">Keahlian</span><p class="font-medium whitespace-pre-line">{{ $user->keahlian ?: '-' }}</p></div>
        </div>
    </div>

    {{-- Ringkasan gaji --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-6">
            <p class="text-sm text-gray-500">Total Gaji</p>
            <p class="text-xl font-semibold">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-500">Sudah Diterima</p>
            <p class="text-xl font-semibold text-green-700">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-500">Sisa Belum Dibayar</p>
            <p class="text-xl font-semibold text-red-600">Rp {{ number_format($totalKurang, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Riwayat order & gaji --}}
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-semibold">Riwayat Order & Gaji</h3>
        </div>
        <div class="p-6 space-y-6">
            @forelse($assignments as $assignment)
            @php
                $dibayar = $assignment->totalPaid();
                $sisa = max(0, (float) $assignment->gaji - $dibayar);
            @endphp
            <div class="border border-gray-200 rounded-lg">
                <div class="p-4 bg-gray-50 flex flex-wrap justify-between gap-2 rounded-t-lg">
                    <div>
                        <p class="font-medium">{{ $assignment->order->order_number ?? '-' }} &middot; {{ $assignment->order->customer->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">
                            Jadwal: {{ optional($assignment->order->tanggal_jadwal)->format('d M Y H:i') ?? '-' }}
                            &middot; Status order: {{ $assignment->order->status ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right text-sm">
                        <p>Gaji: <span class="font-medium">Rp {{ number_format((float) $assignment->gaji, 0, ',', '.') }}</span></p>
                        <p>Diterima: <span class="font-medium text-green-700">Rp {{ number_format($dibayar, 0, ',', '.') }}</span></p>
                        <p>Sisa:
                            @if($sisa > 0)
                                <span class="font-medium {{ $assignment->order->status === 'completed' ? 'text-red-600' : 'text-gray-500' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Lunas</span>
                            @endif
                        </p>
                        @if($sisa > 0 && $assignment->order->status !== 'completed')
                            <p class="text-xs text-gray-500">Order belum completed, belum masuk peringatan</p>
                        @endif
                    </div>
                </div>

                @if($assignment->payments->isNotEmpty())
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500">
                            <th class="text-left py-2 px-4">Tanggal</th>
                            <th class="text-left py-2 px-4">Jenis</th>
                            <th class="text-right py-2 px-4">Jumlah</th>
                            <th class="text-left py-2 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignment->payments as $payment)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 px-4">{{ optional($payment->payment_date)->format('d M Y') ?? '-' }}</td>
                            <td class="py-2 px-4 capitalize">{{ str_replace('_', ' ', $payment->type ?? '-') }}</td>
                            <td class="py-2 px-4 text-right">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4">{{ $payment->catatan ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="py-4 px-4 text-sm text-gray-500">Belum ada pembayaran gaji.</p>
                @endif
            </div>
            @empty
            <p class="py-8 text-center text-gray-500">Belum ada riwayat order untuk user ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
