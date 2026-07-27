@extends('layouts.admin')

@section('title', 'Manajemen Halaman')
@section('header')
<i class="ri-pages-line"></i> Manajemen Halaman (Laman Statis)
@endsection

@section('content')
<div class="card bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <form method="GET" action="{{ route('admin.halaman.index') }}" class="flex gap-2 items-center">
            <input type="text" name="search" class="w-48 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Cari judul..." value="{{ request('search') }}">
            <select name="status" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                <option value="">Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            <button type="submit" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold" title="Cari">
                <i class="ri-search-line"></i>
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.halaman.index') }}" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold" title="Reset">
                <i class="ri-refresh-line"></i>
            </a>
            @endif
        </form>
        <a href="{{ route('admin.halaman.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 w-24">Banner</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Judul Laman</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Slug URL</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-28">Status</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 w-40">Terakhir Update</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($halamans as $hal)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6">
                        @if($hal->featured_image)
                            <img src="{{ asset('storage/' . $hal->featured_image) }}" alt="{{ $hal->judul }}" class="w-16 h-10 object-cover rounded border border-gray-200">
                        @else
                            <div class="w-16 h-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold">no img</div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-bold text-gray-800">{{ $hal->judul }}</div>
                    </td>
                    <td class="py-4 px-6 text-xs text-gray-500 font-mono">
                        <a href="/halaman/{{ $hal->slug }}" target="_blank" class="hover:underline text-blue-600">/halaman/{{ $hal->slug }} <i class="ri-external-link-line text-[10px]"></i></a>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $hal->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($hal->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $hal->updated_at->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.halaman.edit', $hal) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.halaman.destroy', $hal) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laman ini?')">
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
                    <td colspan="6" class="py-8 text-center text-gray-500 text-sm font-semibold">Belum ada laman statis yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($halamans->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $halamans->links() }}
    </div>
    @endif
</div>
@endsection
