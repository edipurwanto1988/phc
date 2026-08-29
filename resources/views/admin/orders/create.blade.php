@extends('layouts.admin')

@section('title', 'Input Order Baru')
@section('header')
<i class="ri-calendar-add-line"></i> Input Order Baru (Pemesanan)
@endsection

@section('content')
<div x-data="orderForm()">
    <div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            <!-- Section 1: Customer & Jadwal -->
            <h4 class="text-sm font-bold text-blue-600 mb-4 border-b border-gray-100 pb-2">Informasi Customer & Waktu</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Customer Selection -->
                <div>
                    <label for="customer_search" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Customer</label>
                    
                    <!-- Autocomplete Wrapper -->
                    <div class="relative" x-data="{
                        open: false,
                        search: '',
                        customers: [
                            @foreach($customers as $customer)
                            {
                                id: '{{ $customer->id }}',
                                nama: '{{ addslashes($customer->nama) }}',
                                no_wa: '{{ $customer->no_wa }}',
                                alamat: '{{ addslashes($customer->alamat) }}',
                                lat: '{{ $customer->latitude }}',
                                lng: '{{ $customer->longitude }}'
                            },
                            @endforeach
                        ],
                        get filteredCustomers() {
                            if (this.search === '') return this.customers;
                            return this.customers.filter(c => {
                                return c.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                                       c.no_wa.includes(this.search);
                            });
                        },
                        selectCustomer(customer) {
                            $data.customerId = customer.id;
                            $data.alamatPengerjaan = customer.alamat;
                            $data.latitude = customer.lat;
                            $data.longitude = customer.lng;
                            this.search = customer.nama + ' (' + customer.no_wa + ')';
                            this.open = false;
                        },
                        clearCustomer() {
                            $data.customerId = '';
                            $data.alamatPengerjaan = '';
                            $data.latitude = '';
                            $data.longitude = '';
                            this.search = '';
                            this.open = false;
                        }
                    }" @click.outside="open = false">
                        
                        <!-- Hidden Input to store real ID for the form -->
                        <input type="hidden" name="customer_id" :value="customerId" required>

                        <!-- Input search field -->
                        <div class="relative">
                            <input 
                                type="text" 
                                class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" 
                                placeholder="Ketik nama atau No. WA customer..." 
                                x-model="search"
                                @focus="open = true"
                                @input="open = true"
                                required
                            >
                            <!-- Clear button -->
                            <template x-if="search !== ''">
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" @click="clearCustomer()">
                                    <i class="ri-close-line text-lg"></i>
                                </button>
                            </template>
                            <!-- Dropdown indicator when empty -->
                            <template x-if="search === ''">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <i class="ri-arrow-down-s-line text-lg"></i>
                                </span>
                            </template>
                        </div>

                        <!-- Dropdown list -->
                        <ul 
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto" 
                            x-show="open" 
                            x-cloak
                            x-transition
                        >
                            <template x-for="c in filteredCustomers" :key="c.id">
                                <li 
                                    class="px-4 py-2 text-sm hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-0"
                                    @click="selectCustomer(c)"
                                >
                                    <div class="font-semibold text-gray-800" x-text="c.nama"></div>
                                    <div class="text-xs text-gray-500" x-text="'WA: ' + c.no_wa"></div>
                                </li>
                            </template>
                            <template x-if="filteredCustomers.length === 0">
                                <li class="px-4 py-3 text-sm text-gray-500 text-center italic">
                                    Customer tidak ditemukan
                                </li>
                            </template>
                        </ul>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-1">Customer tidak ada di daftar? <a href="{{ route('admin.customers.create') }}" class="text-blue-600 hover:underline">Tambah customer baru dulu</a>.</p>
                </div>

                <!-- Waktu Pengerjaan -->
                <div>
                    <label for="tanggal_jadwal" class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Pengerjaan</label>
                    <input type="datetime-local" name="tanggal_jadwal" id="tanggal_jadwal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>
            </div>

            <!-- Alamat Pengerjaan -->
            <div class="mb-4">
                <label for="alamat_pengerjaan" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Pengerjaan (Bisa disesuaikan jika berbeda dengan alamat profil)</label>
                <textarea name="alamat_pengerjaan" id="alamat_pengerjaan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" x-model="alamatPengerjaan" required></textarea>
            </div>

            <!-- Koordinat Pengerjaan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-1">Latitude Koordinat (opsional)</label>
                    <input type="text" name="latitude" id="latitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" x-model="latitude">
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-1">Longitude Koordinat (opsional)</label>
                    <input type="text" name="longitude" id="longitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" x-model="longitude">
                </div>
            </div>

            <!-- Section 2: Pilih Jasa & Multi-item -->
            <h4 class="text-sm font-bold text-blue-600 mt-6 mb-4 border-b border-gray-100 pb-2">Detail Jasa & Layanan yang Dipesan</h4>
            
            <div class="space-y-4 mb-6">
                <!-- Loop Alpine Items -->
                <template x-for="(item, index) in items" :key="index">
                    <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 flex flex-col md:flex-row gap-4 items-end">
                        
                        <!-- Jasa selection -->
                        <div class="w-full md:flex-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Layanan Jasa</label>
                            <select :name="'items['+index+'][service_id]'" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" x-model="item.service_id" @change="updateItemPrice(index)" required>
                                <option value="">-- Pilih Layanan Jasa --</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" data-harga="{{ $service->harga }}" data-satuan="{{ $service->satuan }}">{{ !empty($service->nama_invoice) ? $service->nama_invoice : $service->nama }} (Rp {{ number_format($service->harga, 0, ',', '.') }} / {{ $service->satuan }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Qty -->
                        <div class="w-full md:w-32">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Qty</label>
                            <input type="number" :name="'items['+index+'][qty]'" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white text-center" x-model.number="item.qty" min="1" required>
                        </div>

                        <!-- Satuan label display -->
                        <div class="w-full md:w-12 text-center pb-2 text-sm text-gray-500 font-semibold self-center">
                            / <span x-text="item.satuan || '-'"></span>
                        </div>

                        <!-- Harga Satuan -->
                        <div class="w-full md:w-32">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Harga (Rp)</label>
                            <input type="number" :name="'items['+index+'][harga]'" class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" x-model.number="item.harga" min="0" required>
                        </div>

                        <!-- Subtotal -->
                        <div class="w-full md:w-36 text-right">
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Subtotal</label>
                            <span class="block px-2 py-2 text-sm font-bold text-gray-800 bg-gray-100 rounded-lg border border-gray-200 truncate" :title="'Rp ' + formatRupiah(item.harga * item.qty)">
                                Rp <span x-text="formatRupiah(item.harga * item.qty)"></span>
                            </span>
                        </div>

                        <!-- Remove button -->
                        <div class="w-full md:w-auto text-center">
                            <button type="button" class="p-2 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" @click="removeItem(index)" :disabled="items.length === 1">
                                <i class="ri-delete-bin-line text-lg"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" class="btn border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-1" @click="addItem()">
                    <i class="ri-add-line"></i> Tambah Layanan Jasa
                </button>
            </div>

            <!-- Section 3: Pembayaran & Penugasan -->
            <h4 class="text-sm font-bold text-blue-600 mt-6 mb-4 border-b border-gray-100 pb-2">Informasi Pembayaran & Operasional</h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Metode Bayar -->
                <div>
                    <label for="metode_bayar" class="block text-sm font-semibold text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="metode_bayar" id="metode_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                        <option value="cash">Tunai (Cash)</option>
                        <option value="transfer" selected>Transfer Bank</option>
                        <option value="e-wallet">E-Wallet (OVO/Dana/Qris)</option>
                    </select>
                </div>
                
                <!-- Status Bayar -->
                <div>
                    <label for="status_bayar" class="block text-sm font-semibold text-gray-700 mb-1">Status Pembayaran</label>
                    <select name="status_bayar" id="status_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                        <option value="unpaid">Belum Lunas (Unpaid)</option>
                        <option value="partial">Bayar Sebagian (Partial)</option>
                        <option value="paid">Lunas (Paid)</option>
                    </select>
                </div>

                <!-- Status Pengerjaan -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status Order</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                        <option value="pending">Pending</option>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Assign Cleaner -->
                <div>
                    <label for="cleaner_id" class="block text-sm font-semibold text-gray-700 mb-1">Tugaskan Cleaner (opsional)</label>
                    <select name="cleaner_id" id="cleaner_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Cleaner --</option>
                        @foreach($cleaners as $cleaner)
                        <option value="{{ $cleaner->id }}">{{ $cleaner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Diskon -->
                <div>
                    <label for="diskon" class="block text-sm font-semibold text-gray-700 mb-1">Potongan Harga / Diskon (Rp)</label>
                    <input type="number" name="diskon" id="diskon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" x-model.number="diskon" placeholder="Contoh: 25000" min="0">
                </div>
            </div>

            <!-- Catatan Order -->
            <div class="mb-6">
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan Order</label>
                <textarea name="catatan" id="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Catatan instruksi pengerjaan khusus untuk cleaner..."></textarea>
            </div>

            <!-- Grand Totals Display Panel -->
            <div class="p-6 bg-blue-50 border border-blue-100 rounded-xl mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <span class="text-xs text-blue-600 font-bold uppercase block">Rincian Perhitungan</span>
                    <span class="text-sm text-gray-600 block">Total Jasa: <b>Rp <span x-text="formatRupiah(calculateTotalJasa())"></span></b></span>
                    <span class="text-sm text-gray-600 block">Diskon: <b>Rp <span x-text="formatRupiah(diskon)"></span></b></span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-blue-600 font-bold uppercase block">Grand Total Pembayaran</span>
                    <span class="text-3xl font-extrabold text-blue-800">
                        Rp <span x-text="formatRupiah(calculateGrandTotal())"></span>
                    </span>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Order Pesanan
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function orderForm() {
    return {
        customerId: '',
        alamatPengerjaan: '',
        latitude: '',
        longitude: '',
        diskon: 0,
        items: [
            { service_id: '', qty: 1, harga: 0, satuan: '' }
        ],
        addItem() {
            this.items.push({ service_id: '', qty: 1, harga: 0, satuan: '' });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        updateItemPrice(index) {
            const item = this.items[index];
            if (!item.service_id) {
                item.harga = 0;
                item.satuan = '';
                return;
            }
            
            // Find option and read price & unit from DOM option tags
            const options = document.querySelectorAll(`select[name^="items[${index}]"] option`);
            let found = false;
            options.forEach(opt => {
                if (opt.value === String(item.service_id)) {
                    item.harga = parseFloat(opt.getAttribute('data-harga')) || 0;
                    item.satuan = opt.getAttribute('data-satuan') || '';
                    found = true;
                }
            });
        },
        calculateTotalJasa() {
            return this.items.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        },
        calculateGrandTotal() {
            const total = this.calculateTotalJasa() - (this.diskon || 0);
            return total < 0 ? 0 : total;
        },
        formatRupiah(value) {
            return Number(value).toLocaleString('id-ID');
        }
    }
}
</script>
@endsection
