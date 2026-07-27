@extends('layouts.admin')
@section('title', 'Edit User')
@section('header')
<i class="ri-user-settings-line"></i> Edit User
@endsection

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" class="input w-full" value="{{ $user->name }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" class="input w-full" value="{{ $user->username }}" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="input w-full" value="{{ $user->email }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-xs text-gray-400">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="input w-full">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role_id" class="input w-full" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="input w-full">
                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line mr-2"></i>Update
            </button>
        </div>
    </form>
</div>
@endsection