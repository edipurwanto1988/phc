@extends('layouts.admin')

@section('title', 'Simulasi Profit - ' . $order->order_number)
@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.orders.index') }}" class="btn bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg text-sm shadow-sm transition-all">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
    <span><i class="ri-money-dollar-circle-line"></i> Simulasi Profit Order: <span class="font-bold text-blue-600">{{ $order->order_number }}</span></span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Ringkasan Kolom Kiri -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Info Order -->
        <div class="card p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                <i class="ri-information-line text-blue-500"></i> Informasi Order
            </h4>
            <div class="space-y-3">
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Pelanggan</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->customer->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Jadwal Pengerjaan</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->tanggal_jadwal ? $order->tanggal_jadwal->translatedFormat('d M Y, H:i') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Status</span>
                    <span class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                </div>
            </div>
        </div>

        <!-- Ringkasan Profit -->
        <div class="card p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                <i class="ri-wallet-3-line text-green-500"></i> Ringkasan Profit
            </h4>
            
            <div class="flex justify-between items-center py-2">
                <span class="text-sm font-semibold text-gray-600">Uang Masuk</span>
                <span class="text-sm font-bold text-green-600">Rp {{ number_format($uangMasuk, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2">
                <span class="text-sm font-semibold text-gray-600">Total Pengeluaran</span>
                <span class="text-sm font-bold text-red-600">- Rp {{ number_format($uangKeluar, 0, ',', '.') }}</span>
            </div>
            
            <div class="border-t border-gray-100 mt-2 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-base font-extrabold text-gray-800">PROFIT BERSIH</span>
                    <span class="text-xl font-extrabold {{ $profitFinal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($profitFinal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Kolom Kanan -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Rincian Gaji Cleaner -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-user-star-line text-blue-500"></i> Rincian Gaji Cleaner
                </h4>
                <span class="text-sm font-bold text-gray-700">Total: Rp {{ number_format($uangGaji, 0, ',', '.') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-2 px-4 font-semibold text-gray-600 w-12 text-center">No</th>
                            <th class="py-2 px-4 font-semibold text-gray-600 w-32">Tanggal</th>
                            <th class="py-2 px-4 font-semibold text-gray-600">Nama Penerima Gaji</th>
                            <th class="py-2 px-4 font-semibold text-gray-600 text-right">Nominal Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->assignments as $index => $assignment)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $assignment->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800">
                                {{ $assignment->cleaner->name ?? 'Unknown' }}
                                @if($index === 0)
                                <span class="ml-2 px-1.5 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-md tracking-wider">PIC</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-gray-700">Rp {{ number_format($assignment->gaji, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500 italic">Belum ada cleaner yang ditugaskan / di-set gajinya.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Pengeluaran Operasional -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-tools-line text-orange-500"></i> Pengeluaran Operasional
                </h4>
                <span class="text-sm font-bold text-gray-700">Total: Rp {{ number_format($uangOperasional, 0, ',', '.') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-2 px-4 font-semibold text-gray-600 w-12 text-center">No</th>
                            <th class="py-2 px-4 font-semibold text-gray-600 w-32">Tanggal</th>
                            <th class="py-2 px-4 font-semibold text-gray-600">Untuk Apa / Keterangan</th>
                            <th class="py-2 px-4 font-semibold text-gray-600 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->expenses as $index => $expense)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $expense->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-800">{{ $expense->kategori_biaya }}</div>
                                @if($expense->keterangan)
                                <div class="text-xs text-gray-500 mt-1">{{ $expense->keterangan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-gray-700">Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500 italic">Belum ada pengeluaran operasional yang ditautkan ke order ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="{{ route('admin.expenses.create') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800">
                    <i class="ri-add-line"></i> Tambah Pengeluaran Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
