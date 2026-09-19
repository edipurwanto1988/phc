@extends('layouts.admin')

@section('title', 'Data Pelamar Loker')
@section('header')
<i class="ri-briefcase-line"></i> Data Pelamar Loker
@endsection

@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Pelamar Loker</h3>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.loker.index') }}" class="flex gap-2 items-center">
                <input type="text" name="nama" value="{{ $nama }}" class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Cari nama...">
                <select name="periode_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="">-- Semua Periode --</option>
                    @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}" {{ (string)$periodeId === (string)$periode->id ? 'selected' : '' }}>
                        {{ $periode->nama }} ({{ $periode->status }})
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm font-semibold" title="Filter">
                    <i class="ri-filter-3-line"></i>
                </button>
                @if($periodeId !== '' || $nama !== '')
                <a href="{{ route('admin.loker.index') }}" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm font-semibold" title="Reset">
                    <i class="ri-refresh-line"></i>
                </a>
                @endif
            </form>
            <a href="{{ route('admin.loker.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
                <i class="ri-add-line text-lg"></i> Tambah Pelamar
            </a>
        </div>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Periode</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">No. WhatsApp</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Jenis Kelamin</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Keahlian Khusus</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24 text-center">KTP</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-40 text-center">Tanggal</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lokeres as $loker)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                        {{ $loker->nama }}
                        @if($loker->email)
                        <div class="text-[10px] text-gray-400 font-normal">{{ $loker->email }}</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        @if($loker->periode)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $loker->periode->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $loker->periode->nama }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $loker->no_wa) }}" target="_blank" class="text-green-600 hover:underline font-medium">
                            <i class="ri-whatsapp-line"></i> {{ $loker->no_wa }}
                        </a>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $loker->jenis_kelamin }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs leading-relaxed">{{ $loker->keahlian_khusus ?: '-' }}</td>
                    <td class="py-4 px-6 text-center">
                        @if($loker->ktp)
                        <a href="{{ route('admin.loker.ktp.view', $loker) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                            <i class="ri-id-card-line"></i> Lihat
                        </a>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center text-xs text-gray-500">{{ $loker->created_at->format('d M Y H:i') }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.loker.show', $loker) }}" class="text-gray-600 hover:text-gray-800 transition-colors" title="Detail">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            <a href="{{ route('admin.loker.edit', $loker) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.loker.destroy', $loker) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pelamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada pelamar Loker yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lokeres->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $lokeres->links() }}
    </div>
    @endif
</div>
@endsection
