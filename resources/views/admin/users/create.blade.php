@extends('layouts.admin')
@section('title', 'Tambah User')
@section('header')
<i class="ri-user-add-line"></i> Tambah User
@endsection

@section('content')
<div class="card">
    <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
        @csrf
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" class="input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" class="input w-full" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PHC ID <span class="text-xs text-gray-400">(maks 10 karakter)</span></label>
                    <input type="text" name="phc_id" class="input w-full" maxlength="10" value="{{ old('phc_id') }}" placeholder="Contoh: PHC-0001">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="input w-full" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role_id" id="role_id" class="input w-full" required>
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-role-name="{{ strtolower($role->name) }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="input w-full">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Jenis Cleaner (Hanya muncul jika Role Cleaner) -->
            @php
                $cleanerRoleId = $roles->firstWhere('name', 'Cleaner')->id ?? null;
                $isCurrentlyCleaner = old('role_id') == $cleanerRoleId;
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
                            <input type="radio" name="jenis" value="Tetap" class="text-blue-600 focus:ring-blue-500 h-4 w-4" {{ old('jenis') === 'Tetap' ? 'checked' : '' }}>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm block">Tetap</span>
                                <span class="text-xs text-gray-500">Cleaner staf / pegawai internal tetap</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all">
                            <input type="radio" name="jenis" value="Mitra" class="text-blue-600 focus:ring-blue-500 h-4 w-4" {{ old('jenis') === 'Mitra' ? 'checked' : '' }}>
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
                <textarea name="keahlian" rows="3" class="input w-full resize-y" placeholder="Contoh: Cuci sofa, poles lantai marmer, cleaning AC, deep cleaning...">{{ old('keahlian') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Pisahkan dengan koma jika lebih dari satu.</p>
            </div>
        </div>
        <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
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