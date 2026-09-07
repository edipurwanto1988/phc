<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-user-settings-line"></i> Edit User
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" class="input w-full" value="<?php echo e($user->name); ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" class="input w-full" value="<?php echo e($user->username); ?>" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="input w-full" value="<?php echo e($user->email); ?>" required>
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
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>" data-role-name="<?php echo e(strtolower($role->name)); ?>" <?php echo e(old('role_id', $user->role_id) == $role->id ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="input w-full">
                        <option value="active" <?php echo e(old('status', $user->status) === 'active' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="inactive" <?php echo e(old('status', $user->status) === 'inactive' ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Jenis Cleaner (Hanya muncul jika Role Cleaner) -->
            <?php
                $cleanerRoleId = $roles->firstWhere('name', 'Cleaner')->id ?? null;
                $currentRoleId = old('role_id', $user->role_id);
                $isCurrentlyCleaner = ($currentRoleId == $cleanerRoleId) || (strtolower($user->role->name ?? '') === 'cleaner');
            ?>
            <div id="cleaner-type-container" class="<?php echo e($isCurrentlyCleaner ? '' : 'hidden'); ?>">
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
                            <input type="radio" name="jenis" value="Tetap" class="text-blue-600 focus:ring-blue-500 h-4 w-4" <?php echo e(old('jenis', $user->jenis) === 'Tetap' ? 'checked' : ''); ?>>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm block">Tetap</span>
                                <span class="text-xs text-gray-500">Cleaner staf / pegawai internal tetap</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all">
                            <input type="radio" name="jenis" value="Mitra" class="text-blue-600 focus:ring-blue-500 h-4 w-4" <?php echo e(old('jenis', $user->jenis) === 'Mitra' ? 'checked' : ''); ?>>
                            <div>
                                <span class="font-semibold text-gray-800 text-sm block">Mitra</span>
                                <span class="text-xs text-gray-500">Cleaner mitra lepas / freelance partner</span>
                            </div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
        <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line mr-2"></i>Update
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/users/edit.blade.php ENDPATH**/ ?>