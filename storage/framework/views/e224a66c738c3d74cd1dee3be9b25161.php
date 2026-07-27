<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-user-settings-line"></i> Users
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <div></div>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
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
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium"><?php echo e($user->name); ?></td>
                    <td class="py-3 px-4"><?php echo e($user->username); ?></td>
                    <td class="py-3 px-4"><?php echo e($user->email); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-primary/10 text-primary">
                            <?php echo e($user->role->name ?? '-'); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo e($user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'); ?>">
                            <?php echo e($user->status === 'active' ? 'Aktif' : 'Nonaktif'); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="ri-edit-line"></i>
                        </a>
                        <?php if($user->id !== auth()->user()->id): ?>
                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Belum ada user</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($users->hasPages()): ?>
        <div class="mt-4 px-4">
            <?php echo e($users->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/users/index.blade.php ENDPATH**/ ?>