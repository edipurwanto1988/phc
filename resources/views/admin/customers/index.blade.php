@extends('layouts.admin')

@section('title', 'Data Customer')
@section('header')
<i class="ri-team-line"></i> Manajemen Customer
@endsection

@section('content')
<!-- Search & Filter Controls -->
<div class="card mb-6 bg-white p-6 rounded-xl border border-gray-200">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Search Input -->
        <div class="md:col-span-2">
            <label for="search" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Customer</label>
            <div class="relative">
                <input type="text" name="search" id="search" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Cari nama, no. WA, alamat..." value="{{ request('search') }}">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </div>

        <!-- Status Filter -->
        <div>
            <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg text-sm transition-all shadow-sm">
                <i class="ri-filter-2-line mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.customers.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm transition-all" title="Reset Filter">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Customer</h3>
        <a href="{{ route('admin.customers.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-user-add-line text-lg"></i> Tambah Customer
        </a>
    </div>
    <div class="overflow-x-auto bg-white">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Lengkap</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">No. WhatsApp</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Email</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Alamat</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 text-center font-semibold w-24">Status</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:underline">{{ $customer->nama }}</a>
                        @if($customer->user_id)
                        <span class="ml-1 px-1.5 py-0.5 text-[10px] font-bold rounded bg-green-50 text-green-600 border border-green-200" title="Terhubung dengan akun Gmail">GMAIL</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 font-medium">
                        <a href="https://wa.me/{{ $customer->no_wa }}" target="_blank" class="hover:text-green-600 inline-flex items-center gap-1">
                            <i class="ri-whatsapp-line text-green-500 text-base"></i> {{ $customer->no_wa }}
                        </a>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">{{ $customer->email ?? '-' }}</td>
                    <td class="py-4 px-6 text-sm text-gray-500 max-w-xs truncate" title="{{ $customer->alamat }}">
                        {{ $customer->alamat }}
                        @if($customer->kelurahan || $customer->kecamatan)
                        <div class="text-[10px] text-gray-400 font-normal">
                            {{ $customer->kelurahan ? $customer->kelurahan . ', ' : '' }}{{ $customer->kecamatan }}
                        </div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $customer->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Lihat Profil">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data customer ini?')">
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
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data customer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="p-6 border-t border-gray-100 bg-white rounded-b-xl">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
