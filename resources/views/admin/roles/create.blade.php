@extends('layouts.admin')
@section('title', 'Tambah Role')
@section('header')
<i class="ri-shield-user-line"></i> Tambah Role & Hak Akses
@endsection

@section('content')
<div class="card bg-white rounded-xl shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.roles.store') }}" class="p-6">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role</label>
                <input type="text" name="name" class="input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Staff Operator" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Hak Akses (Permissions)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
                    @php
                    $groupedPerms = [
                        'Layanan / Jasa' => ['manage_services'],
                        'Customer' => ['manage_customers'],
                        'Pesanan / Orders' => ['manage_orders', 'create_orders', 'edit_orders', 'delete_orders', 'view_orders', 'update_order_status'],
                        'Laporan & Ringkasan' => ['view_reports'],
                        'Pengeluaran & Keuangan' => ['manage_expenses', 'view_expenses'],
                        'Blog & Artikel' => ['manage_blog'],
                        'Lainnya' => ['view_own_profile']
                    ];
                    @endphp
                    @foreach($groupedPerms as $group => $perms)
                    <div class="space-y-2.5 p-4 bg-white rounded-lg border border-gray-150 shadow-sm">
                        <h4 class="font-bold text-sm text-blue-600 border-b border-gray-100 pb-1.5">{{ $group }}</h4>
                        @foreach($perms as $perm)
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer hover:bg-gray-50 p-1.5 rounded transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}" class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="text-gray-700 font-medium text-xs">{{ str_replace('_', ' ', $perm) }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Centang hak akses yang akan diberikan ke role ini.</p>
            </div>
        </div>
        <div class="pt-6 border-t border-gray-250 flex justify-end gap-3 mt-6">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg text-sm shadow-sm transition-all">
                <i class="ri-save-line mr-1"></i> Simpan Role
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2 rounded-lg text-sm transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection