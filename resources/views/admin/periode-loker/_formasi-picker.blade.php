{{-- Partial: pilih formasi (multi) + modal tambah formasi via AJAX.
     Variabel: $formasis (Collection<Formasi>), $selectedIds (array<int>) --}}
<div class="mb-4"
     x-data="formasiPicker({
         formasis: @js($formasis->map(fn ($f) => ['id' => $f->id, 'nama' => $f->nama])->values()),
         selected: @js(array_map('intval', $selectedIds)),
         storeUrl: @js(route('admin.formasi.store')),
     })"
     @keydown.escape.window="closeModal()">
    <div class="flex items-center justify-between mb-1">
        <label class="block text-sm font-semibold text-gray-700">Formasi</label>
        <button type="button" @click="openModal()" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
            <i class="ri-add-line"></i> Tambah Formasi
        </button>
    </div>

    <template x-if="formasis.length === 0">
        <div class="px-3 py-3 border border-dashed border-gray-300 rounded-lg text-sm text-gray-500">
            Belum ada formasi. Klik <span class="font-semibold">Tambah Formasi</span> untuk membuat formasi baru.
        </div>
    </template>

    <template x-if="formasis.length > 0">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-3 border border-gray-300 rounded-lg bg-white max-h-60 overflow-y-auto">
            <template x-for="f in formasis" :key="f.id">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="formasi_ids[]" :value="f.id" x-model.number="selected" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span x-text="f.nama"></span>
                </label>
            </template>
        </div>
    </template>
    <p class="text-xs text-gray-400 mt-2">Pilih satu atau lebih formasi yang dibuka pada periode ini.</p>
    @error('formasi_ids')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
    @error('formasi_ids.*')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror

    {{-- Modal tambah formasi --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>

        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6" role="dialog" aria-modal="true" aria-labelledby="modal-formasi-title">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-formasi-title" class="text-base font-semibold text-gray-800">Tambah Formasi</h3>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600" title="Tutup">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <label for="modal_formasi_nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Formasi <span class="text-red-500">*</span></label>
            <input type="text" id="modal_formasi_nama" x-model="namaBaru" maxlength="255" x-ref="namaInput"
                   @keydown.enter.prevent="saveFormasi()"
                   placeholder="Contoh: Cleaning Service"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <p x-show="error" x-text="error" class="text-xs text-red-600 mt-1"></p>

            <div class="flex items-center gap-3 mt-6">
                <button type="button" @click="saveFormasi()" :disabled="saving"
                        class="btn bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all shadow-sm">
                    <span x-text="saving ? 'Menyimpan...' : 'Simpan Formasi'"></span>
                </button>
                <button type="button" @click="closeModal()" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>
<script>
    function formasiPicker({ formasis, selected, storeUrl }) {
        return {
            formasis,
            selected: selected.map(Number),
            storeUrl,
            showModal: false,
            namaBaru: '',
            error: '',
            saving: false,

            openModal() {
                this.showModal = true;
                this.namaBaru = '';
                this.error = '';
                this.$nextTick(() => this.$refs.namaInput.focus());
            },

            closeModal() {
                this.showModal = false;
            },

            async saveFormasi() {
                const nama = this.namaBaru.trim();
                if (!nama) {
                    this.error = 'Nama formasi wajib diisi.';
                    return;
                }

                this.saving = true;
                this.error = '';

                try {
                    const res = await fetch(this.storeUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ nama }),
                    });
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok) {
                        this.error = data.errors?.nama?.[0] ?? data.message ?? 'Gagal menyimpan formasi.';
                        return;
                    }

                    if (!this.formasis.some(f => f.id === data.id)) {
                        this.formasis.push({ id: data.id, nama: data.nama });
                        this.formasis.sort((a, b) => a.nama.localeCompare(b.nama));
                    }
                    if (!this.selected.includes(data.id)) {
                        this.selected.push(data.id);
                    }
                    this.closeModal();
                } catch (e) {
                    this.error = 'Terjadi kesalahan jaringan. Coba lagi.';
                } finally {
                    this.saving = false;
                }
            },
        };
    }
</script>
