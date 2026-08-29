@extends('layouts.admin')

@section('title', 'Detail Pengeluaran')
@section('header')
<i class="ri-money-dollar-circle-line"></i> Detail Pengeluaran #{{ $expense->id }}
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Expense Details (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-150">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-information-line text-blue-600"></i> Detail Informasi Pengeluaran
                </h3>
                @if($expense->is_gaji)
                <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full flex items-center gap-1">
                    <i class="ri-user-star-line"></i> Pengeluaran Gaji
                </span>
                @else
                <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full flex items-center gap-1">
                    <i class="ri-tools-line"></i> Operasional
                </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Kategori Pengeluaran</span>
                    <span class="text-sm font-semibold text-gray-800 block mt-0.5">{{ $expense->kategori_biaya }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Tanggal Pembayaran</span>
                    <span class="text-sm font-semibold text-gray-800 block mt-0.5">{{ $expense->tanggal->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Penerima / Pelaksana</span>
                    <span class="text-sm font-semibold text-gray-800 block mt-0.5">{{ $expense->user->name }}</span>
                    <span class="text-xs text-gray-500 block">Role: {{ $expense->user->role->name ?? 'User' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Jumlah Nominal</span>
                    <span class="text-lg font-bold text-blue-600 block mt-0.5">Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <span class="block text-xs font-semibold text-gray-400 uppercase">Catatan / Keterangan</span>
                <p class="text-sm text-gray-700 mt-1.5 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100 italic">
                    {{ $expense->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                </p>
            </div>
        </div>

        <!-- Rincian Gaji (Jika is_gaji true) -->
        @if($expense->is_gaji)
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-file-list-3-line text-blue-600"></i> Rincian Order & Pekerjaan Terkait
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">No. Order</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Pelanggan</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Tanggal Jadwal</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Gaji Jasa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expense->orderAssignments as $assignment)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm font-bold text-blue-600">
                                <a href="{{ route('admin.orders.show', $assignment->order) }}" class="hover:underline">{{ $assignment->order->order_number }}</a>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-750 font-medium">
                                {{ $assignment->order->customer->nama }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                {{ $assignment->order->tanggal_jadwal->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 text-sm font-bold text-gray-800 text-right">
                                Rp {{ number_format($assignment->gaji, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Grand Total Gaji -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                <div class="w-full md:w-64 text-right">
                    <span class="text-xs text-gray-400 font-bold uppercase block">Total Gaji Dibayarkan</span>
                    <span class="text-xl font-extrabold text-blue-650">
                        Rp {{ number_format($expense->jumlah, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Controls -->
    <div class="space-y-6">
        <div class="card p-6 bg-white border border-gray-200 rounded-xl space-y-4">
            <h3 class="text-base font-bold text-gray-800 pb-2 border-b border-gray-100">
                <i class="ri-settings-line text-blue-600"></i> Aksi Kontrol
            </h3>
            
            @if($expense->is_gaji)
            <a href="{{ route('admin.expenses.download-slip', $expense) }}" class="w-full btn bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-lg text-sm flex items-center justify-center gap-1.5 shadow-sm transition-all">
                <i class="ri-download-2-line text-lg"></i> Download Slip Gaji (PDF)
            </a>
            @endif

            <a href="{{ route('admin.expenses.index') }}" class="w-full btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 rounded-lg text-sm flex items-center justify-center gap-1.5 transition-all">
                Kembali ke Daftar
            </a>
        </div>
    </div>

</div>
@endsection