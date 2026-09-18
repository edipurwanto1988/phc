@extends('layouts.admin')
@section('title', 'Edit User')
@section('header')
<i class="ri-user-settings-line"></i> Edit User
@endsection

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <!-- Foto Profil -->
            <div class="flex items-center gap-6 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                <div class="relative group shrink-0">
                    @if($user->foto)
                        <img src="{{ route('admin.users.foto.view', $user) }}" alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-xl object-cover border-2 border-gray-200 shadow-sm">
                    @else
                        <div class="w-24 h-24 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-3xl border-2 border-gray-200 shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Foto Profil</label>
                    <p class="text-xs text-gray-500 mb-3">Upload foto profil (JPG/PNG/WebP, maks 5MB). Tersimpan otomatis ke Google Drive.</p>
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg cursor-pointer transition-colors">
                            <i class="ri-upload-2-line"></i> {{ $user->foto ? 'Ganti Foto' : 'Upload Foto' }}
                            <input type="file" name="foto" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                        @if($user->foto)
                        <button type="button" onclick="deleteFoto()" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                            <i class="ri-delete-bin-line"></i> Hapus
                        </button>
                        @endif
                    </div>
                </div>
            </div>

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
                    <label class="block text-sm font-medium text-gray-700 mb-2">PHC ID <span class="text-xs text-gray-400">(maks 10 karakter)</span></label>
                    <input type="text" name="phc_id" class="input w-full" maxlength="10" value="{{ old('phc_id', $user->phc_id) }}" placeholder="Contoh: PHC-0001">
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
                    <select name="role_id" id="role_id" class="input w-full" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-role-name="{{ strtolower($role->name) }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="input w-full">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Jenis Cleaner (Hanya muncul jika Role Cleaner) -->
            @php
                $cleanerRoleId = $roles->firstWhere('name', 'Cleaner')->id ?? null;
                $currentRoleId = old('role_id', $user->role_id);
                $isCurrentlyCleaner = ($currentRoleId == $cleanerRoleId) || (strtolower($user->role->name ?? '') === 'cleaner');
            @endphp
            <div id="cleaner-type-container" class="{{ $isCurrentlyCleaner ? '' : 'hidden' }}">
                <div class="p-4 bg-blue-50/70 border border-blue-200 rounded-xl space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-gray-800">
                            <i class="ri-user-star-line text-blue-600 mr-1"></i> Jenis Cleaner
                        </label>
                        <span class="text-xs text-blue-700 font-medium bg-blue-100 px-2 py-0.5 rounded-full">Khusus Role Cleaner</span>
                    </div>
                    <p class="text-xs text-gray-500">Pilih status kepegawaian untuk cleaner ini:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all">
                            <input type="radio" name="jenis" value="Tetap" class="text-blue-600 focus:ring-blue-500 h-4 w-4" {{ old('jenis', $user->jenis) === 'Tetap' ? 'checked' : '' }}>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm block">Tetap</span>
                                <span class="text-xs text-gray-500">Cleaner staf / pegawai internal tetap</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all">
                            <input type="radio" name="jenis" value="Mitra" class="text-blue-600 focus:ring-blue-500 h-4 w-4" {{ old('jenis', $user->jenis) === 'Mitra' ? 'checked' : '' }}>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm block">Mitra</span>
                                <span class="text-xs text-gray-500">Cleaner mitra lepas / freelance partner</span>
                            </div>
                        </label>
                    </div>
                    @error('jenis')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Keahlian Khusus -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keahlian Khusus</label>
                <textarea name="keahlian" rows="3" class="input w-full resize-y" placeholder="Contoh: Cuci sofa, poles lantai marmer, cleaning AC, deep cleaning...">{{ old('keahlian', $user->keahlian) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Pisahkan dengan koma jika lebih dari satu.</p>
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

@if($user->foto)
<form id="delete-foto-form" method="POST" action="{{ route('admin.users.foto.destroy', $user) }}" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@section('scripts')
<script>
    function deleteFoto() {
        if (!confirm('Hapus foto profil ini?')) return;
        document.getElementById('delete-foto-form').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const cleanerContainer = document.getElementById('cleaner-type-container');

        function updateCleanerVisibility() {
            if (!roleSelect || !cleanerContainer) return;
            const selectedOpt = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOpt ? (selectedOpt.getAttribute('data-role-name') || selectedOpt.text).toLowerCase() : '';
            
            if (roleName.includes('cleaner')) {
                cleanerContainer.classList.remove('hidden');
            } else {
                cleanerContainer.classList.add('hidden');
                // Uncheck radios when non-cleaner is chosen
                const radios = cleanerContainer.querySelectorAll('input[type="radio"]');
                radios.forEach(r => r.checked = false);
            }
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', updateCleanerVisibility);
        }
    });
</script>
@endsection