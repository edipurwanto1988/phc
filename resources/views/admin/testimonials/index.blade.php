@extends('layouts.admin')

@section('title', 'Moderasi Testimoni')
@section('header')
<i class="ri-chat-voice-line"></i> Moderasi Testimoni Customer
@endsection

@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Testimoni Pelanggan</h3>
        <a href="{{ route('admin.testimonials.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Testimoni Offline
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Pengirim</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Ulasan (Konten)</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Rating</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24 text-center">Tampil Web</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24 text-center">Featured</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $testimonial)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                        {{ $testimonial->nama }}
                        @if($testimonial->customer)
                        <div class="text-[10px] text-gray-400 font-normal">Customer ID: #{{ $testimonial->customer->id }}</div>
                        @else
                        <div class="text-[10px] text-gray-400 font-normal">Offline Input</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-sm leading-relaxed">{{ $testimonial->konten }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center text-yellow-500 gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="ri-star-fill text-sm"></i>
                                @else
                                    <i class="ri-star-line text-sm"></i>
                                @endif
                            @endfor
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $testimonial->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $testimonial->is_approved ? 'Disetujui' : 'Pending' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $testimonial->is_featured ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $testimonial->is_featured ? 'Featured' : 'Regular' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit / Moderasi">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan testimoni ini?')">
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
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada testimoni pelanggan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($testimonials->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $testimonials->links() }}
    </div>
    @endif
</div>
@endsection
