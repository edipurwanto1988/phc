@extends('layouts.admin')

@section('title', 'Roles')
@section('header')
<i class="ri-shield-user-line"></i> Roles & Permissions
@endsection
@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <div></div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="ri-add-line mr-2"></i>Tambah
        </a>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4">Nama Role</th>
                    <th class="text-left py-3 px-4">Permissions</th>
                    <th class="text-left py-3 px-4">Users</th>
                    <th class="text-left py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">
                        <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm">
                            {{ $role->name }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @if(in_array('all', $role->permissions ?? []))
                            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">All Permissions</span>
                        @else
                            <div class="flex flex-wrap gap-1">
                                @foreach(($role->permissions ?? []) as $perm)
                                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ $perm }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-700">
                            {{ $role->users_count }} users
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="ri-edit-line"></i>
                        </a>
                        @if($role->users_count == 0 && !in_array('all', $role->permissions ?? []))
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">Belum ada role</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection