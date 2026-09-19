@extends('layouts.admin')

@section('title', 'Periode Loker')
@section('header')
<i class="ri-calendar-line"></i> Periode Loker
@endsection

@section('content')
<div class="card bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Periode Loker</h3>
        <a href="{{ route('admin.periode-loker.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Periode
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Periode</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Tanggal Mulai</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Tanggal Selesai</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Status</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Jumlah Pelamar</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodes as $periode)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">{{ $periode->nama }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $periode->tanggal_mulai ? $periode->tanggal_mulai->translatedFormat('d M Y') : '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $periode->tanggal_selesai ? $periode->tanggal_selesai->translatedFormat('d M Y') : '-' }}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $periode->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $periode->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center text-sm text-gray-600">{{ $periode->lokeres_count }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.periode-loker.edit', $periode) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.periode-loker.destroy', $periode) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')">
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
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada periode loker yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($periodes->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $periodes->links() }}
    </div>
    @endif
</div>
@endsection
