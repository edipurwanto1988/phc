@extends('layouts.admin')

@section('title', 'Users')
@section('header')
<i class="ri-user-settings-line"></i> Users
@endsection
@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <div></div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ri-add-line mr-2"></i>Tambah
        </a>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4">Nama</th>
                    <th class="text-left py-3 px-4">Username</th>
                    <th class="text-left py-3 px-4">Email</th>
                    <th class="text-left py-3 px-4">Role</th>
                    <th class="text-left py-3 px-4">Status</th>
                    <th class="text-left py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                    <td class="py-3 px-4">{{ $user->username }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary">
                            {{ $user->role->name ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="ri-edit-line"></i>
                        </a>
                        @if($user->id !== auth()->user()->id)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
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
                    <td colspan="6" class="py-8 text-center text-gray-500">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="mt-4 px-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection