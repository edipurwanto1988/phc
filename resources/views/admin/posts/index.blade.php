@extends('layouts.admin')

@section('title', 'Manajemen Blog')
@section('header')
<i class="ri-article-line"></i> Manajemen Artikel & Blog
@endsection

@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="flex gap-2 items-center">
            <input type="text" name="search" class="w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Cari judul artikel..." value="{{ request('search') }}">
            <button type="submit" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold" title="Cari">
                <i class="ri-search-line"></i>
            </button>
            @if(request('search'))
            <a href="{{ route('admin.posts.index') }}" class="btn border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold" title="Reset">
                <i class="ri-refresh-line"></i>
            </a>
            @endif
        </form>
        <a href="{{ route('admin.posts.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tulis Artikel Baru
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 w-24">Gambar</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Judul Artikel</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Penulis</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-28">Status</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 w-40">Tanggal Rilis</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-center">
                        <input type="checkbox" class="post-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ $post->id }}">
                    </td>
                    <td class="py-4 px-6">
                        @if($post->gambar_utama)
                            <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-16 h-10 object-cover rounded border border-gray-200">
                        @else
                            <div class="w-16 h-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-gray-400 text-xs font-semibold">no img</div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm font-bold text-gray-800 line-clamp-1">{{ $post->judul }}</div>
                        <div class="text-[11px] text-gray-400 line-clamp-1 mt-0.5">{{ $post->excerpt ?? 'Tanpa kutipan ringkas.' }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $post->author->name ?? 'Admin' }}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $post->published_at ? $post->published_at->translatedFormat('d M Y, H:i') : 'Draft' }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
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
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada artikel yang ditulis.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection


