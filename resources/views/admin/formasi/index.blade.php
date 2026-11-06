@extends('layouts.admin')

@section('title', 'Formasi')
@section('header')
<i class="ri-user-star-line"></i> Formasi
@endsection

@section('content')
<div class="card bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Formasi</h3>
        <a href="{{ route('admin.formasi.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Formasi
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Formasi</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-32 text-center">Jumlah Pelamar</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formasis as $formasi)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">{{ $formasi->nama }}</td>
                    <td class="py-4 px-6 text-center text-sm text-gray-600">{{ $formasi->lokeres_count }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.formasi.edit', $formasi) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.formasi.destroy', $formasi) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus formasi ini?')">
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
                    <td colspan="3" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada formasi yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($formasis->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $formasis->links() }}
    </div>
    @endif
</div>
@endsection
