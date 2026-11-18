@extends('layouts.admin')

@section('title', 'Edit Alat')
@section('header')
<i class="ri-edit-line"></i> Edit Alat: {{ $asset->nama }}
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200 max-w-4xl">
    <form method="POST" action="{{ route('admin.master-alat.update', $asset) }}">
        @csrf
        @method('PUT')
        @include('admin.master-alat._form')

        <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.master-alat.show', $asset) }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
