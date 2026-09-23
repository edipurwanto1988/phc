@extends('layouts.admin')

@section('title', 'Data Absensi - ' . $order->order_number)
@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.orders.index') }}" class="btn bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg text-sm shadow-sm transition-all">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
    <span><i class="ri-map-pin-user-line"></i> Data Absensi: <span class="font-bold text-blue-600">{{ $order->order_number }}</span></span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Order & Tambah Jadwal -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
            <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                <i class="ri-information-line text-blue-500"></i> Informasi Order
            </h4>
            <div class="space-y-3 mb-4">
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Pelanggan</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->customer->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Jadwal Utama</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->tanggal_jadwal ? $order->tanggal_jadwal->translatedFormat('d M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase">Alamat Pengerjaan</span>
                    <span class="text-sm font-medium text-gray-800">{{ $order->alamat_pengerjaan }}</span>
                </div>
            </div>
            
            @if($isPicOrAdmin)
            <div class="border-t border-gray-100 pt-4 mt-2" x-data="{ openModal: false }">
                <h5 class="font-bold text-sm text-gray-700 mb-3"><i class="ri-calendar-event-line"></i> Tambah Jadwal Hari Kerja</h5>
                <button type="button" @click="openModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full flex items-center justify-center gap-2">
                    <i class="ri-add-line"></i> Tambah Jadwal
                </button>

                <!-- Modal Tambah Jadwal -->
                <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @click.self="openModal = false">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden" @click.stop>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h4 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <i class="ri-calendar-event-line text-blue-600"></i> Pilih Jadwal & Pekerja
                            </h4>
                            <button type="button" @click="openModal = false" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('admin.orders.absensi.storeSchedule', $order) }}" method="POST">
                            @csrf
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Jadwal</label>
                                    <input type="date" name="tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Pilih Cleaner & PIC</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                        @foreach($order->assignments as $assignment)
                                            <label class="flex items-center gap-3 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="checkbox" name="cleaners[]" value="{{ $assignment->user_id }}" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <div class="flex-1">
                                                    <span class="text-sm font-medium text-gray-800">{{ $assignment->cleaner->name ?? 'Unknown' }}</span>
                                                    @if($loop->first)
                                                        <span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-md">PIC</span>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-2">Hapus centang pada pekerja yang tidak akan bertugas pada tanggal tersebut.</p>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                                <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Jadwal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Daftar Jadwal & Absensi -->
    <div class="lg:col-span-2 space-y-6">
        @forelse($order->schedules as $schedule)
        <div class="card p-0 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex justify-between items-center">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-calendar-check-line text-blue-600"></i> Jadwal: {{ $schedule->tanggal->translatedFormat('d F Y') }}
                </h4>
                @if($isPicOrAdmin && !$schedule->attendances->contains('status', 'approved'))
                <form action="{{ route('admin.orders.absensi.destroySchedule', [$order, $schedule]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini beserta data absennya?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium"><i class="ri-delete-bin-line"></i> Hapus</button>
                </form>
                @endif
            </div>
            
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="py-2 px-3 font-semibold w-10">No</th>
                                <th class="py-2 px-3 font-semibold">Cleaner</th>
                                <th class="py-2 px-3 font-semibold">Waktu Absen</th>
                                <th class="py-2 px-3 font-semibold text-center">Status</th>
                                <th class="py-2 px-3 font-semibold text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule->attendances as $index => $attendance)
                                @php
                                    $cleanerUser = $attendance->user;
                                    $isCurrentUser = $attendance->user_id == $user->id;
                                    $isPic = $order->assignments->first() && $order->assignments->first()->user_id == $attendance->user_id;
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="py-3 px-3 font-medium text-gray-800">
                                        {{ $cleanerUser->name ?? 'Unknown' }}
                                        @if($isPic)
                                            <span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-md">PIC</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-gray-600">
                                        {{ $attendance->waktu_absen ? $attendance->waktu_absen->format('H:i:s') : '-' }}
                                        @if($attendance->waktu_absen && $attendance->latitude)
                                            <a href="https://maps.google.com/?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank" class="block text-[10px] text-blue-500 hover:underline mt-1">
                                                <i class="ri-map-pin-line"></i> Lihat Lokasi
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if(!$attendance->waktu_absen)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">Belum Absen</span>
                                        @elseif($attendance->status == 'pending')
                                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-full font-medium">Menunggu ACC</span>
                                        @elseif($attendance->status == 'approved')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Aktif</span>
                                        @elseif($attendance->status == 'rejected')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if(!$attendance->waktu_absen && $isCurrentUser)
                                            @if($schedule->tanggal->isToday())
                                                <form action="{{ route('admin.orders.absensi.storeAttendance', [$order, $schedule]) }}" method="POST" id="form-absen-{{ $schedule->id }}">
                                                    @csrf
                                                    <input type="hidden" name="latitude" id="lat-{{ $schedule->id }}">
                                                    <input type="hidden" name="longitude" id="lng-{{ $schedule->id }}">
                                                    <button type="button" onclick="doAbsen({{ $schedule->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold w-full">
                                                        Absen GPS
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" disabled class="bg-gray-300 text-gray-500 cursor-not-allowed px-3 py-1.5 rounded-lg text-xs font-semibold w-full" title="Absen hanya bisa dilakukan pada tanggal jadwal">
                                                    Absen GPS
                                                </button>
                                            @endif
                                        @elseif($attendance->waktu_absen && $attendance->status == 'pending')
                                            @php
                                                $canAcc = $isPicOrAdmin;
                                            @endphp
                                            
                                            @if($canAcc)
                                                <div class="flex gap-1 justify-center">
                                                    <form action="{{ route('admin.orders.absensi.updateStatus', [$order, $attendance]) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" style="background-color: #22c55e; color: white; padding: 0.375rem; border-radius: 0.25rem;" class="text-xs font-semibold hover:opacity-80" title="ACC">
                                                            <i class="ri-check-line"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.orders.absensi.updateStatus', [$order, $attendance]) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" style="background-color: #ef4444; color: white; padding: 0.375rem; border-radius: 0.25rem;" class="text-xs font-semibold hover:opacity-80" title="Tolak">
                                                            <i class="ri-close-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-10 bg-white border border-gray-200 rounded-xl text-center text-gray-500">
            <i class="ri-calendar-todo-line text-5xl text-gray-300 block mb-4"></i>
            <p class="font-semibold">Belum ada jadwal kerja untuk pesanan ini.</p>
            @if($isPicOrAdmin)
            <p class="text-sm mt-1">Gunakan form di sebelah kiri untuk menambahkan tanggal kerja.</p>
            @endif
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function doAbsen(scheduleId) {
        if (navigator.geolocation) {
            Swal.fire({
                title: 'Mendapatkan Lokasi...',
                text: 'Mohon tunggu dan pastikan GPS Anda aktif.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    Swal.close();
                    document.getElementById('lat-' + scheduleId).value = position.coords.latitude;
                    document.getElementById('lng-' + scheduleId).value = position.coords.longitude;
                    document.getElementById('form-absen-' + scheduleId).submit();
                },
                function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat mengambil lokasi GPS. Pastikan izin lokasi browser Anda aktif.'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        } else {
            alert('Browser Anda tidak mendukung geolokasi.');
        }
    }
</script>
<!-- Make sure SweetAlert2 is loaded for the loading dialog, but we assume it's loaded in layout or we can just use native confirm/alert if not. -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
