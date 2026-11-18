<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="kode_alat" class="block text-sm font-semibold text-gray-700 mb-1">Kode Alat</label>
        <input type="text" name="kode_alat" id="kode_alat" value="{{ old('kode_alat', $asset->kode_alat ?? $suggestedKode ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-gray-50" placeholder="Otomatis, contoh: ALT-0001">
        <p class="text-xs text-gray-500 mt-1">Terisi otomatis dengan nomor urut berikutnya. Boleh diubah manual.</p>
    </div>
    <div>
        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Alat <span class="text-red-500">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $asset->nama ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Vacuum Cleaner" required>
    </div>
    <div x-data="kategoriAlat(@js($categories->map(fn ($c) => ['id' => (string) $c->id, 'nama' => $c->nama])->values()), @js((string) old('kategori_id', $asset->kategori_id ?? '')), @js(route('admin.master-alat.kategori.store')))">
        <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
        <div class="flex items-stretch gap-2">
            <select name="kategori_id" id="kategori_id" @change="selectedId = $event.target.value" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                <option value="">— Tanpa kategori —</option>
                <template x-for="o in options" :key="o.id">
                    <option :value="o.id" :selected="String(o.id) === String(selectedId)" x-text="o.nama"></option>
                </template>
            </select>
            <button type="button" @click="openModal()" class="px-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm" title="Tambah kategori baru">
                <i class="ri-add-line text-lg"></i>
            </button>
        </div>

        <!-- Popup tambah kategori -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @keydown.escape.window="showModal = false">
            <div @click.outside="showModal = false" class="w-full max-w-md bg-white rounded-xl shadow-xl border border-gray-200">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-800">Tambah Kategori Alat</h4>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-700"><i class="ri-close-line text-xl"></i></button>
                </div>
                <div class="px-5 py-4">
                    <label for="kategori_baru" class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="kategori_baru" x-ref="namaBaru" x-model="namaBaru" @keydown.enter.prevent="simpan()" maxlength="100" placeholder="Contoh: Alat Pertukangan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p x-show="error" x-text="error" class="text-xs text-red-600 mt-1"></p>
                </div>
                <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200">
                    <button type="button" @click="showModal = false" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">Batal</button>
                    <button type="button" @click="simpan()" :disabled="saving" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-medium">
                        <span x-text="saving ? 'Menyimpan...' : 'Simpan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label for="merk" class="block text-sm font-semibold text-gray-700 mb-1">Merk / Tipe</label>
        <input type="text" name="merk" id="merk" value="{{ old('merk', $asset->merk ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
    </div>
    <div>
        <label for="expense_search" class="block text-sm font-semibold text-gray-700 mb-1">Pengeluaran Pembelian</label>
        @php
            $selectedExpenseId = (string) old('expense_id', $asset->expense_id ?? '');
            $selectedExpense = collect($expenseOptions)->firstWhere('id', $selectedExpenseId);
        @endphp
        <div x-data="expenseAutocomplete(@js($expenseOptions), @js($selectedExpenseId), @js($selectedExpense['label'] ?? ''))" class="relative" @click.outside="open = false">
            <input type="hidden" name="expense_id" :value="selectedId">
            <div class="relative">
                <input type="text" id="expense_search" x-model="query" @focus="open = true" @input="onInput()" @keydown.escape="open = false" @keydown.enter.prevent="pickFirst()" @keydown.arrow-down.prevent="open = true" autocomplete="off" placeholder="Ketik kategori, tanggal, atau nominal..." class="w-full px-3 py-2 pr-9 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <button type="button" x-show="selectedId" x-cloak @click="clear()" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600" title="Hapus pilihan">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
            <ul x-show="open" x-cloak class="absolute z-20 mt-1 w-full max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg text-sm">
                <template x-for="item in results" :key="item.id">
                    <li @mousedown.prevent="select(item)" class="px-3 py-2 cursor-pointer hover:bg-blue-50" :class="item.id === selectedId ? 'bg-blue-50 font-semibold' : ''" x-text="item.label"></li>
                </template>
                <li x-show="results.length === 0" class="px-3 py-2 text-gray-500">Tidak ada pengeluaran yang cocok.</li>
            </ul>
        </div>
        <p class="text-xs text-gray-500 mt-1">Ketik untuk mencari. Jika harga kosong, harga diambil dari nominal pengeluaran.</p>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kategoriAlat', (options, initialId, storeUrl) => ({
                options,
                selectedId: initialId || '',
                showModal: false,
                namaBaru: '',
                saving: false,
                error: '',
                openModal() {
                    this.namaBaru = '';
                    this.error = '';
                    this.showModal = true;
                    this.$nextTick(() => this.$refs.namaBaru.focus());
                },
                async simpan() {
                    const nama = this.namaBaru.trim();
                    if (!nama) {
                        this.error = 'Nama kategori wajib diisi.';
                        return;
                    }
                    this.saving = true;
                    this.error = '';
                    try {
                        const res = await fetch(storeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({ nama }),
                        });
                        const json = await res.json();
                        if (!res.ok) {
                            this.error = (json.errors && json.errors.nama && json.errors.nama[0]) || 'Gagal menyimpan kategori.';
                            return;
                        }
                        const id = String(json.id);
                        if (!this.options.some(o => o.id === id)) {
                            this.options.push({ id, nama: json.nama });
                            this.options.sort((a, b) => a.nama.localeCompare(b.nama));
                        }
                        this.selectedId = id;
                        this.showModal = false;
                    } catch (e) {
                        this.error = 'Koneksi gagal. Coba lagi.';
                    } finally {
                        this.saving = false;
                    }
                },
            }));

            Alpine.data('expenseAutocomplete'
, (options, initialId, initialLabel) => ({
                options,
                selectedId: initialId || '',
                query: initialLabel || '',
                open: false,
                results: [],
                init() {
                    this.results = this.filter('');
                },
                filter(term) {
                    const words = term.toLowerCase().trim().split(/\s+/).filter(Boolean);
                    return this.options
                        .filter(o => words.every(w => o.search.includes(w)))
                        .slice(0, 50);
                },
                onInput() {
                    this.open = true;
                    if (this.selectedId && this.query !== this.selectedLabel()) {
                        this.selectedId = '';
                    }
                    this.results = this.filter(this.query);
                },
                selectedLabel() {
                    return (this.options.find(o => o.id === this.selectedId) || {}).label || '';
                },
                select(item) {
                    this.selectedId = item.id;
                    this.query = item.label;
                    this.open = false;
                },
                pickFirst() {
                    if (this.results.length) this.select(this.results[0]);
                },
                clear() {
                    this.selectedId = '';
                    this.query = '';
                    this.results = this.filter('');
                },
            }));
        });
    </script>
    <div>
        <label for="tanggal_beli" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Beli</label>
        <input type="date" name="tanggal_beli" id="tanggal_beli" value="{{ old('tanggal_beli', isset($asset) && $asset->tanggal_beli ? $asset->tanggal_beli->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
    </div>
    <div>
        <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">Nilai Perolehan (Rp)</label>
        <input type="number" name="harga" id="harga" min="0" step="0.01" value="{{ old('harga', $asset->harga ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Kosongkan jika ambil dari pengeluaran">
    </div>
    <div>
        <label for="kondisi" class="block text-sm font-semibold text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
        <select name="kondisi" id="kondisi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
            @foreach($kondisiOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('kondisi', $asset->kondisi ?? 'baik') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi / Penanggung Jawab</label>
        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $asset->lokasi ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Gudang Utama">
    </div>
    <div class="md:col-span-2">
        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
        <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('keterangan', $asset->keterangan ?? '') }}</textarea>
    </div>
</div>
