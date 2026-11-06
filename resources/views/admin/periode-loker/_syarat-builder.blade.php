{{-- Partial: builder daftar syarat (berkas upload) periode.
     Variabel: $syaratRows (array of ['id' => ?int, 'nama' => string, 'wajib' => bool]) --}}
<div class="mb-4"
     x-data="syaratBuilder(@js(array_values($syaratRows)))">
    <div class="flex items-center justify-between mb-1">
        <label class="block text-sm font-semibold text-gray-700">Syarat Upload</label>
        <button type="button" @click="add()" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
            <i class="ri-add-line"></i> Tambah Syarat
        </button>
    </div>

    <div class="space-y-2">
        <template x-for="(item, index) in items" :key="item.key">
            <div class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg bg-white">
                <span class="text-xs text-gray-400 w-5 text-right" x-text="index + 1 + '.'"></span>
                <input type="hidden" :name="`syarats[${index}][id]`" :value="item.id" x-bind:disabled="!item.id">
                <input type="text" :name="`syarats[${index}][nama]`" x-model="item.nama" maxlength="255" placeholder="Contoh: Scan KTP" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <label class="flex items-center gap-1.5 text-xs text-gray-700 whitespace-nowrap cursor-pointer">
                    <input type="checkbox" :name="`syarats[${index}][wajib]`" value="1" x-model="item.wajib" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Wajib
                </label>
                <button type="button" @click="remove(index)" class="text-red-600 hover:text-red-800" title="Hapus syarat">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
            </div>
        </template>
    </div>
    <p class="text-xs text-gray-400 mt-2">Pelamar mengupload berkas untuk tiap syarat. Syarat yang dicentang "Wajib" harus diisi. Format PDF, JPG, PNG, atau WEBP, maksimal 5 MB. Baris tanpa nama diabaikan.</p>
    @error('syarats')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<script>
    function syaratBuilder(initial) {
        let seq = 0;
        const make = (s = {}) => ({
            key: ++seq,
            id: s.id ?? null,
            nama: s.nama ?? '',
            wajib: !!s.wajib,
        });
        return {
            items: initial.map(make),
            add() { this.items.push(make()); },
            remove(index) { this.items.splice(index, 1); },
        };
    }
</script>
