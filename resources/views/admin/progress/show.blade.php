@extends('layouts.admin')

@section('title', 'Progress Pekerjaan')
@section('header')
<i class="ri-bar-chart-box-line"></i> Progress Pekerjaan: {{ $order->order_number }}
@endsection

@section('content')
@php
    $totalRooms = $order->progressRooms->count();
    $doneRooms = $order->progressRooms->where('status', 'selesai')->count();
    $prosesRooms = $order->progressRooms->where('status', 'proses')->count();
    $belumRooms = $order->progressRooms->where('status', 'belum')->count();
    $progressPercent = $totalRooms > 0 ? round(($doneRooms / $totalRooms) * 100) : 0;
    $grouped = $order->progressRooms->groupBy(fn($r) => $r->lantai ?: 'Tanpa Lantai');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Ringkasan & Daftar Ruangan (2/3) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ringkasan -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-pie-chart-line text-blue-600"></i> Ringkasan Progress
                </h3>
                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-semibold text-blue-600 hover:underline flex items-center gap-1">
                    <i class="ri-arrow-left-line"></i> Kembali ke Order
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-extrabold text-gray-800">{{ $totalRooms }}</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase mt-1">Total Ruangan</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-extrabold text-green-600">{{ $doneRooms }}</div>
                    <div class="text-xs font-semibold text-green-700 uppercase mt-1">Selesai</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-extrabold text-blue-600">{{ $prosesRooms }}</div>
                    <div class="text-xs font-semibold text-blue-700 uppercase mt-1">Progress</div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-extrabold text-amber-600">{{ $belumRooms }}</div>
                    <div class="text-xs font-semibold text-amber-700 uppercase mt-1">Belum</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm mb-1.5">
                <span class="text-gray-600 font-medium">Progres Keseluruhan</span>
                <span class="font-bold {{ $progressPercent === 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $progressPercent }}%</span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full {{ $progressPercent === 100 ? 'bg-green-500' : 'bg-blue-600' }} rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <!-- Daftar Ruangan per Lantai -->
        @forelse($grouped as $lantai => $rooms)
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-building-2-line text-blue-600"></i> {{ $lantai }}
                <span class="ml-1 text-xs font-semibold text-gray-400">({{ $rooms->where('status', 'selesai')->count() }}/{{ $rooms->count() }} selesai)</span>
            </h3>

            <div class="space-y-3">
                @foreach($rooms as $room)
                <div class="p-4 rounded-xl border"
                     :class="status === 'selesai' ? 'border-green-200 bg-green-50' : (status === 'proses' ? 'border-blue-200 bg-blue-50' : 'border-gray-200 bg-white')"
                     x-data="{
                        status: '{{ $room->status }}',
                        catatan: {{ json_encode($room->catatan ?? '') }},
                        masalah: {{ json_encode($room->masalah ?? '') }},
                        editingCatatan: false,
                        editingMasalah: false,
                        saving: false,
                        saved: false,
                        async save(extra = {}) {
                            this.saving = true;
                            this.saved = false;
                            try {
                                const res = await fetch('{{ route('admin.progress.rooms.update', [$order, $room]) }}', {
                                    method: 'PUT',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({ status: this.status, catatan: this.catatan, masalah: this.masalah, ...extra })
                                });
                                await res.json();
                                this.saved = true;
                                setTimeout(() => this.saved = false, 2000);
                            } catch (e) {}
                            this.saving = false;
                        },
                        uploadBukti(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            const fd = new FormData();
                            fd.append('bukti', file);
                            fd.append('_token', '{{ csrf_token() }}');
                            fetch('{{ route('admin.progress.rooms.bukti.upload', [$order, $room]) }}', {
                                method: 'POST',
                                headers: { 'Accept': 'application/json' },
                                body: fd
                            }).then(r => r.json()).then(() => setTimeout(() => window.location.reload(), 500));
                        }
                     }">
                    <div class="flex items-start gap-4">
                        {{-- Status icon --}}
                        <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                            :class="status === 'selesai' ? 'bg-green-500 text-white' : (status === 'proses' ? 'bg-blue-500 text-white' : 'bg-gray-300 text-white')">
                            <i :class="status === 'selesai' ? 'ri-check-line' : (status === 'proses' ? 'ri-loader-4-line animate-spin' : 'ri-time-line')" class="text-lg"></i>
                        </div>

                        <div class="flex-1 min-w-0 space-y-3">
                            {{-- Nama ruangan + status badge + aksi --}}
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-800" :class="status === 'selesai' ? 'line-through text-gray-500' : ''">{{ $room->ruangan }}</span>
                                    @if($room->luas)
                                    <span class="shrink-0 px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-500">{{ rtrim(rtrim(number_format($room->luas, 2, ',', '.'), '0'), ',') }} m²</span>
                                    @endif
                                </div>
                                <div class="shrink-0 flex items-center gap-1">
                                    <span class="text-[11px] text-green-600 font-bold flex items-center gap-1" x-show="saved"><i class="ri-checkbox-circle-line"></i> Tersimpan</span>
                                    <span class="text-[11px] text-gray-400 flex items-center gap-1" x-show="saving"><i class="ri-loader-4-line animate-spin"></i></span>
                                    <form method="POST" action="{{ route('admin.progress.rooms.destroy', [$order, $room]) }}" onsubmit="return confirm('Hapus ruangan ini dari progress?')" class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition-colors" title="Hapus Ruangan">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Pilihan status (dropdown minimalis) --}}
                            <div class="flex items-center gap-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase shrink-0">Status</label>
                                <select x-model="status" @change="save()"
                                    class="px-2.5 py-1.5 text-xs font-semibold rounded-lg border bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="status === 'selesai' ? 'border-green-300 text-green-700' : (status === 'proses' ? 'border-blue-300 text-blue-700' : 'border-amber-300 text-amber-700')">
                                    <option value="belum">Belum Selesai</option>
                                    <option value="proses">Proses Pengerjaan</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>

                            {{-- Keterangan (editable inline) --}}
                            <div class="flex items-start gap-2">
                                <i class="ri-sticky-note-line text-gray-400 mt-1"></i>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Keterangan</span>
                                        <button type="button" @click="editingCatatan = !editingCatatan" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                            <span x-show="!editingCatatan">Edit</span><span x-show="editingCatatan" x-cloak>Batal</span>
                                        </button>
                                    </div>
                                    <div x-show="!editingCatatan">
                                        <p class="text-sm text-gray-700 whitespace-pre-line" x-text="catatan || '—'"></p>
                                    </div>
                                    <div x-show="editingCatatan" x-cloak class="space-y-1.5 mt-1">
                                        <textarea x-model="catatan" rows="2" class="w-full px-3 py-1.5 border border-blue-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis keterangan..."></textarea>
                                        <div class="flex gap-2">
                                            <button type="button" @click="save(); editingCatatan = false" class="px-2.5 py-1 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Masalah (editable inline) --}}
                            <div class="flex items-start gap-2">
                                <i class="ri-alert-line text-amber-500 mt-1"></i>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Masalah (jika ada)</span>
                                        <button type="button" @click="editingMasalah = !editingMasalah" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                            <span x-show="!editingMasalah">Edit</span><span x-show="editingMasalah" x-cloak>Batal</span>
                                        </button>
                                    </div>
                                    <div x-show="!editingMasalah">
                                        <p class="text-sm whitespace-pre-line" :class="masalah ? 'text-red-600 font-medium' : 'text-gray-400'" x-text="masalah || 'Tidak ada masalah'"></p>
                                    </div>
                                    <div x-show="editingMasalah" x-cloak class="space-y-1.5 mt-1">
                                        <textarea x-model="masalah" rows="2" class="w-full px-3 py-1.5 border border-red-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Tulis masalah/kendala..."></textarea>
                                        <div class="flex gap-2">
                                            <button type="button" @click="save(); editingMasalah = false" class="px-2.5 py-1 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bukti foto --}}
                            <div class="flex items-start gap-2">
                                <i class="ri-camera-line text-gray-400 mt-0.5"></i>
                                <div class="flex-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Bukti Pekerjaan</span>
                                    @if($room->bukti)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ route('admin.progress.rooms.bukti.view', [$order, $room]) }}"
                                                class="h-16 w-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                                @click="window.open('{{ route('admin.progress.rooms.bukti.view', [$order, $room]) }}', '_blank')">
                                            <div class="space-y-1">
                                                <label class="cursor-pointer text-[11px] font-semibold text-blue-600 hover:underline flex items-center gap-1">
                                                    <i class="ri-refresh-line"></i> Ganti
                                                    <input type="file" accept="image/*" class="hidden" @change="uploadBukti($event)">
                                                </label>
                                                <form method="POST" action="{{ route('admin.progress.rooms.bukti.destroy', [$order, $room]) }}" onsubmit="return confirm('Hapus foto bukti ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[11px] font-semibold text-red-500 hover:underline flex items-center gap-1">
                                                        <i class="ri-delete-bin-line"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg cursor-pointer">
                                            <i class="ri-upload-2-line"></i> Upload Bukti
                                            <input type="file" accept="image/*" class="hidden" @change="uploadBukti($event)">
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="card p-10 bg-white border border-gray-200 rounded-xl text-center text-gray-500">
            <i class="ri-inbox-line text-5xl text-gray-300 block mb-4"></i>
            <p class="font-semibold">Belum ada data ruangan.</p>
            <p class="text-sm mt-1">Tambahkan lantai & ruangan pada form di sebelah kanan.</p>
        </div>
        @endforelse
    </div>

    <!-- Right: Form Tambah & Share Link (1/3) -->
    <div class="space-y-6">
        <!-- Form Tambah Ruangan -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-add-circle-line text-blue-600"></i> Tambah Lantai & Ruangan
            </h3>

            <form method="POST" action="{{ route('admin.progress.rooms.store', $order) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Lantai</label>
                    <input type="text" name="lantai" placeholder="contoh: Lantai 1" list="lantai-list"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <datalist id="lantai-list">
                        @foreach($grouped->keys() as $l)
                            <option value="{{ $l }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Ruangan <span class="text-red-500">*</span></label>
                    <input type="text" name="ruangan" placeholder="contoh: Kamar Tidur Utama" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Luas Ruangan (m²)</label>
                    <input type="number" name="luas" step="0.01" min="0" placeholder="contoh: 12.5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Opsional: catatan untuk ruangan ini..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-y"></textarea>
                </div>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                    <i class="ri-add-line"></i> Tambah Ruangan
                </button>
            </form>

            {{-- Import JSON massal --}}
            <div class="pt-4 mt-4 border-t border-gray-100" x-data="{ openImport: false }">
                <button type="button" @click="openImport = true"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-all">
                    <i class="ri-file-upload-line"></i> Import JSON (Massal)
                </button>

                {{-- Modal Import --}}
                <div x-show="openImport" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @click.self="openImport = false">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden" @click.stop>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <i class="ri-file-upload-line text-blue-600"></i> Import Lantai & Ruangan (JSON)
                            </h4>
                            <button type="button" @click="openImport = false" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>

                        <div class="px-6 py-4 overflow-y-auto space-y-4">
                            {{-- Penjelasan format --}}
                            <div>
                                <h5 class="text-xs font-bold text-gray-600 uppercase mb-2">Format JSON (array ruangan)</h5>
                                <p class="text-xs text-gray-500 leading-relaxed mb-2">
                                    Setiap objek mewakili satu ruangan. Field <code class="bg-gray-100 px-1 rounded">ruangan</code> wajib, sisanya opsional.
                                </p>
                                <pre class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-[11px] leading-relaxed text-gray-700 overflow-x-auto"><code>[
  {
    "lantai": "Lantai 1",
    "ruangan": "Kamar Tidur Utama",
    "luas": 12.5,
    "status": "belum",
    "catatan": "Keterangan opsional",
    "masalah": "Masalah jika ada (opsional)"
  },
  {
    "lantai": "Lantai 1",
    "ruangan": "Kamar Mandi",
    "luas": 4,
    "status": "proses",
    "catatan": null,
    "masalah": null
  }
]</code></pre>
                                <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                                    <strong>status</strong>: <code>belum</code>, <code>proses</code>, atau <code>selesai</code> (default <code>belum</code>).<br>
                                    Bisa juga dibungkus: <code>{"rooms": [ ... ]}</code>.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('admin.progress.rooms.import', $order) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tempel JSON di sini</label>
                                    <textarea name="json" rows="8" placeholder='[{"lantai":"Lantai 1","ruangan":"Dapur","luas":9}]'
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-mono resize-y"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Atau upload file JSON</label>
                                    <input type="file" name="file" accept=".json,.txt"
                                        class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>

                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                                    <i class="ri-file-upload-line"></i> Import Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Share Link untuk Client -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-link text-blue-600"></i> Link untuk Client
            </h3>

            @if($order->progress_token)
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ url('/progress/' . $order->progress_token) }}"
                            class="flex-1 px-3 py-2 text-xs border border-gray-200 rounded-lg bg-gray-50 text-gray-600 focus:outline-none">
                        <button type="button" onclick="copyProgressLink(this)"
                            class="shrink-0 inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all"
                            title="Salin Link">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                    <a href="{{ url('/progress/' . $order->progress_token) }}" target="_blank"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                        <i class="ri-external-link-line"></i> Buka Link Progress
                    </a>
                </div>
            @else
                <form method="POST" action="{{ route('admin.progress.link', $order) }}">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                        <i class="ri-link"></i> Buat Link untuk Client
                    </button>
                </form>
            @endif

            <p class="text-[11px] text-gray-400 mt-3 leading-relaxed">
                Link ini dapat dibagikan ke pemilik rumah/gedung untuk memantau progress pekerjaan secara real-time tanpa perlu login.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyProgressLink(btn) {
    const input = btn.closest('div').querySelector('input');
    if (!input) return;
    input.select();
    input.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
    } catch (e) {
        navigator.clipboard.writeText(input.value);
    }
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="ri-check-line"></i>';
    setTimeout(() => { btn.innerHTML = original; }, 1500);
}
</script>
@endsection
