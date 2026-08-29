@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran / Gaji')
@section('header')
<i class="ri-money-dollar-circle-line"></i> Tambah Pengeluaran & Gaji
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200" x-data="{ tab: 'operasional' }">
    
    <!-- Tab Headers -->
    <div class="flex border-b border-gray-200 mb-6">
        <button 
            type="button" 
            class="px-5 py-2.5 text-sm font-semibold border-b-2 transition-all flex items-center gap-1.5 focus:outline-none"
            :class="tab === 'operasional' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
            @click="tab = 'operasional'"
        >
            <i class="ri-tools-line text-lg"></i> Pengeluaran Operasional
        </button>
        <button 
            type="button" 
            class="px-5 py-2.5 text-sm font-semibold border-b-2 transition-all flex items-center gap-1.5 focus:outline-none"
            :class="tab === 'gaji' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
            @click="tab = 'gaji'"
        >
            <i class="ri-user-star-line text-lg"></i> Pembayaran Gaji Cleaner
        </button>
    </div>

    <!-- TAB 1: OPERASIONAL -->
    <div x-show="tab === 'operasional'">
        <form method="POST" action="{{ route('admin.expenses.store') }}">
            @csrf
            <input type="hidden" name="tab_type" value="operasional">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Pelaksana -->
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Pelaksana / Penanggung Jawab</label>
                    <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="">-- Pilih Pelaksana --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role->name ?? 'User' }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kategori Biaya -->
                <div>
                    <label for="kategori_biaya" class="block text-sm font-semibold text-gray-700 mb-1">Kategori Biaya</label>
                    <input type="text" name="kategori_biaya" id="kategori_biaya" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Pembelian Bahan, Uang Makan, Transportasi" required>
                </div>

                <!-- Jumlah Uang -->
                <div>
                    <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Biaya (Rp)</label>
                    <input type="number" name="jumlah" id="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 75000" min="0" required>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Rincian detail pengeluaran..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Pengeluaran
                </button>
                <a href="{{ route('admin.expenses.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- TAB 2: GAJI CLEANER -->
    <div x-show="tab === 'gaji'" x-cloak x-data="{
        selectedCleaner: '',
        assignments: [],
        selectedAssignments: [],
        
        allUnpaid: {
            @foreach($unpaidAssignments as $cId => $list)
                '{{ $cId }}': [
                    @foreach($list as $assign)
                    {
                        id: '{{ $assign->id }}',
                        order_number: '{{ $assign->order->order_number }}',
                        customer_name: '{{ addslashes($assign->order->customer->nama) }}',
                        tanggal: '{{ \Carbon\Carbon::parse($assign->order->tanggal_jadwal)->translatedFormat('d M Y') }}',
                        gaji: {{ (int)$assign->gaji }}
                    },
                    @endforeach
                ],
            @endforeach
        },

        updateCleaner() {
            this.selectedAssignments = [];
            if (this.selectedCleaner && this.allUnpaid[this.selectedCleaner]) {
                this.assignments = this.allUnpaid[this.selectedCleaner];
            } else {
                this.assignments = [];
            }
        },

        toggleAll() {
            if (this.selectedAssignments.length === this.assignments.length) {
                this.selectedAssignments = [];
            } else {
                this.selectedAssignments = this.assignments.map(a => a.id);
            }
        },

        get totalGajiSelected() {
            let total = 0;
            this.assignments.forEach(a => {
                if (this.selectedAssignments.includes(a.id)) {
                    total += a.gaji;
                }
            });
            return total;
        },

        formatRupiah(value) {
            return Number(value).toLocaleString('id-ID');
        }
    }">
        <form method="POST" action="{{ route('admin.expenses.store') }}">
            @csrf
            <input type="hidden" name="tab_type" value="gaji">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal_gaji" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal" id="tanggal_gaji" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Pilih Cleaner -->
                <div>
                    <label for="cleaner_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Cleaner (Yang memiliki gaji belum dibayar)</label>
                    <select name="cleaner_id" id="cleaner_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" x-model="selectedCleaner" @change="updateCleaner()" required>
                        <option value="">-- Pilih Cleaner --</option>
                        @foreach($cleaners as $cleaner)
                            @if(isset($unpaidAssignments[$cleaner->id]))
                            <option value="{{ $cleaner->id }}">
                                {{ $cleaner->name }} ({{ $unpaidAssignments[$cleaner->id]->count() }} Order Belum Dibayar)
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- List Order Belum Dibayar -->
            <div class="mb-6" x-show="selectedCleaner !== ''">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Order untuk Dibayarkan</label>
                
                <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 font-bold text-gray-700">
                                <th class="p-3 w-10 text-center">
                                    <input type="checkbox" @click="toggleAll()" :checked="selectedAssignments.length === assignments.length && assignments.length > 0">
                                </th>
                                <th class="p-3">No. Order</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tanggal Kerja</th>
                                <th class="p-3 text-right">Gaji Jasa (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="a in assignments" :key="a.id">
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="assignment_ids[]" :value="a.id" x-model="selectedAssignments">
                                    </td>
                                    <td class="p-3 font-bold text-blue-600" x-text="a.order_number"></td>
                                    <td class="p-3 text-gray-800" x-text="a.customer_name"></td>
                                    <td class="p-3 text-gray-650" x-text="a.tanggal"></td>
                                    <td class="p-3 text-right font-bold text-gray-800" x-text="'Rp ' + formatRupiah(a.gaji)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total Gaji -->
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg mt-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-blue-600 font-bold uppercase block">Total Slip Gaji</span>
                        <span class="text-xs text-gray-500 font-semibold" x-text="selectedAssignments.length + ' Pekerjaan dipilih'"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-extrabold text-blue-800">
                            Rp <span x-text="formatRupiah(totalGajiSelected)"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Keterangan Slip -->
            <div class="mb-6" x-show="selectedCleaner !== ''">
                <label for="keterangan_gaji" class="block text-sm font-semibold text-gray-700 mb-1">Catatan / Keterangan Slip</label>
                <textarea name="keterangan" id="keterangan_gaji" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Pembayaran gaji periode pertengahan agustus..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm" :disabled="selectedAssignments.length === 0">
                    Proses & Bayar Gaji
                </button>
                <a href="{{ route('admin.expenses.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection