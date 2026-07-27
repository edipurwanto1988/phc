<?php $__env->startSection('title', '403 - Akses Ditolak'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('errors.partials.error-page', [
    'code' => 403,
    'iconSymbol' => '403',
    'title' => 'Akses ditolak',
    'message' => 'Anda tidak memiliki izin untuk membuka halaman ini.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/errors/403.blade.php ENDPATH**/ ?>