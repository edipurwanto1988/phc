<?php $__env->startSection('title', '404 - Halaman Tidak Ditemukan'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('errors.partials.error-page', [
    'code' => 404,
    'iconSymbol' => '404',
    'title' => 'Halaman tidak ditemukan',
    'message' => 'Alamat yang Anda buka tidak tersedia, sudah dipindahkan, atau tautannya tidak lagi aktif.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/errors/404.blade.php ENDPATH**/ ?>