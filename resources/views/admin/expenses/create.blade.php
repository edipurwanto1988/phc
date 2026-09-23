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
            <div class="mb-6">
                <label for="order_id" class="block text-sm font-semibold text-gray-700 mb-1">Terkait Order (Opsional)</label>
                <select name="order_id" id="order_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="">-- Tidak Terkait Order --</option>
                    @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                        {{ $order->order_number }} - {{ $order->customer->nama ?? 'Unknown' }} ({{ \Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('d M Y') }})
                    </option>
                    @endforeach
                </select>
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
        amountInputs: {},
        
        allUnpaid: {
            @foreach($unpaidAssignments as $cId => $list)
                '{{ $cId }}': [
                    @foreach($list as $assign)
                    {
                        id: '{{ $assign->id }}',
                        order_number: '{{ $assign->order->order_number }}',
                        customer_name: '{{ addslashes($assign->order->customer->nama) }}',
                        tanggal: '{{ \Carbon\Carbon::parse($assign->order->tanggal_jadwal)->translatedFormat('d M Y') }}',
                        gaji: {{ (int)$assign->gaji }},
                        paid: {{ (int)$assign->totalPaid() }},
                        remaining: {{ (int)$assign->remaining() }}
                    },
                    @endforeach
                ],
            @endforeach
        },

        updateCleaner() {
            this.selectedAssignments = [];
            this.amountInputs = {};
            if (this.selectedCleaner && this.allUnpaid[this.selectedCleaner]) {
                this.assignments = this.allUnpaid[this.selectedCleaner];
                // Pre-fill each assignment's amount with its full remaining (pelunasan default)
                this.assignments.forEach(a => {
                    this.amountInputs[a.id] = a.remaining;
                });
                // Auto-select all orders so the user can pay immediately
                this.selectedAssignments = this.assignments.map(a => a.id);
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

        // Default amount to pay = full remaining (pelunasan), user can lower it for cashbon
        amountFor(a) {
            if (this.amountInputs[a.id] === undefined || this.amountInputs[a.id] === null || this.amountInputs[a.id] === '') {
                return a.remaining;
            }
            return Number(this.amountInputs[a.id]);
        },

        isLunas(a) {
            return this.amountFor(a) >= a.remaining;
        },

        get totalGajiSelected() {
            let total = 0;
            this.assignments.forEach(a => {
                if (this.selectedAssignments.includes(a.id)) {
                    total += this.amountFor(a);
                }
            });
            return total;
        },

        formatRupiah(value) {
            return Number(value || 0).toLocaleString('id-ID');
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
                    <label for="cleaner_id" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Cleaner (Yang memiliki gaji belum lunas)</label>
                    <select name="cleaner_id" id="cleaner_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" x-model="selectedCleaner" @change="updateCleaner()" required>
                        <option value="">-- Pilih Cleaner --</option>
                        @foreach($cleaners as $cleaner)
                            @if(isset($unpaidAssignments[$cleaner->id]))
                            <option value="{{ $cleaner->id }}">
                                {{ $cleaner->name }} ({{ $unpaidAssignments[$cleaner->id]->count() }} Order Belum Lunas)
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- List Order Belum Lunas -->
            <div class="mb-6" x-show="selectedCleaner !== ''">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Order & Masukkan Nominal Pembayaran (Cash Bon / Pelunasan)</label>
                
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
                                <th class="p-3 text-right">Gaji (Rp)</th>
                                <th class="p-3 text-right">Sudah Dibayar</th>
                                <th class="p-3 text-right">Sisa</th>
                                <th class="p-3 text-right">Bayar Sekarang (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="a in assignments" :key="a.id">
                                <tr class="border-b border-gray-100 hover:bg-gray-50" :class="{ 'opacity-60': !selectedAssignments.includes(a.id) }">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="assignment_ids[]" :value="a.id" x-model="selectedAssignments">
                                    </td>
                                    <td class="p-3 font-bold text-blue-600" x-text="a.order_number"></td>
                                    <td class="p-3 text-gray-800" x-text="a.customer_name"></td>
                                    <td class="p-3 text-gray-650" x-text="a.tanggal"></td>
                                    <td class="p-3 text-right font-bold text-gray-800" x-text="'Rp ' + formatRupiah(a.gaji)"></td>
                                    <td class="p-3 text-right text-gray-600" x-text="'Rp ' + formatRupiah(a.paid)"></td>
                                    <td class="p-3 text-right font-bold text-amber-600" x-text="'Rp ' + formatRupiah(a.remaining)"></td>
                                    <td class="p-3 text-right">
                                        <div class="flex items-center gap-1 justify-end">
                                            <span class="text-gray-400 text-[10px]">Rp</span>
                                            <input type="number" 
                                                   x-model.number="amountInputs[a.id]"
                                                   :max="a.remaining" min="0" step="1"
                                                   class="w-24 px-2 py-1 border border-gray-300 rounded-lg text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                   :class="{ 'border-green-400 bg-green-50': isLunas(a) }">
                                            <!-- Hidden input keyed by assignment id so controller maps amount correctly -->
                                            <input type="hidden" :name="'amounts[' + a.id + ']'" :value="amountFor(a)">
                                        </div>
                                        <span class="text-[9px]" :class="isLunas(a) ? 'text-green-600' : 'text-amber-600'" x-text="isLunas(a) ? 'Pelunasan' : 'Cash Bon'"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total Gaji -->
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg mt-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-blue-600 font-bold uppercase block">Total Pembayaran</span>
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
                <textarea name="keterangan" id="keterangan_gaji" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Cash bon ke-1 / pelunasan gaji periode..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm" :disabled="selectedAssignments.length === 0">
                    Proses Pembayaran Gaji
                </button>
                <a href="{{ route('admin.expenses.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('order_id')) {
            new TomSelect("#order_id", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "-- Tidak Terkait Order (Ketik untuk mencari) --"
            });
        }
    });
</script>
@endsection