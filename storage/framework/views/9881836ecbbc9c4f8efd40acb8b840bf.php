<?php $__env->startSection('title', 'Layanan Kami - PHC Pekanbaru'); ?>
<?php $__env->startSection('meta_description', 'Daftar lengkap layanan kebersihan Pekanbaru Home Cleaning. Dari general cleaning, deep cleaning, cuci AC, cuci sofa, poles lantai hingga fumigasi.'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-surface py-12 border-b border-border">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-text-primary tracking-tight">Daftar Lengkap Layanan Jasa</h1>
        <p class="mt-3 text-text-secondary max-w-xl mx-auto text-sm leading-relaxed">Pilih layanan kebersihan profesional terbaik yang sesuai dengan kebutuhan rumah, apartemen, kos-kosan, atau kantor Anda.</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
    <div class="space-y-16">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="space-y-6">
            <!-- Category Title & Header -->
            <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="<?php echo e($category->icon ?? 'ri-sparkling-line'); ?> text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-850"><?php echo e($category->nama); ?></h2>
                    <p class="text-xs text-text-secondary mt-0.5"><?php echo e($category->deskripsi); ?></p>
                </div>
            </div>

            <!-- Services Grid inside Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $category->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card-hover bg-white rounded-lg border border-border p-6 flex flex-col justify-between shadow-sm">
                    <a href="/layanan/<?php echo e($service->slug); ?>" class="block group">
                        <?php if($service->gambar): ?>
                        <div class="w-full h-40 rounded-lg overflow-hidden mb-4 border border-border">
                            <img src="<?php echo e(asset('storage/' . $service->gambar)); ?>" alt="<?php echo e($service->nama); ?>" class="w-full h-full object-cover">
                        </div>
                        <?php endif; ?>
                        <h3 class="text-base font-bold text-text-primary group-hover:text-primary transition-colors"><?php echo e($service->nama); ?></h3>
                        <p class="mt-2 text-xs text-text-secondary leading-relaxed leading-normal"><?php echo e(\Illuminate\Support\Str::words(strip_tags($service->deskripsi_singkat ?? $service->deskripsi), 50, '...')); ?></p>
                    </a>
                    <div class="mt-6 pt-4 border-t border-gray-150 flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-primary">Rp <?php echo e(number_format($service->harga, 0, ',', '.')); ?></span>
                            <span class="text-xs text-text-secondary">/ <?php echo e($service->satuan); ?></span>
                        </div>
                        <a href="https://wa.me/<?php echo e(\App\Models\Setting::get('whatsapp', '6281234567890')); ?>?text=Halo%20PHC,%20saya%20tertarik%20dengan%20layanan%20*<?php echo e($service->nama); ?>*" target="_blank" class="inline-flex items-center gap-1 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                            Pesan <i class="ri-whatsapp-line"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-3 text-center py-6 text-sm text-gray-400 font-medium">Belum ada layanan tersedia pada kategori ini.</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/pages/services/index.blade.php ENDPATH**/ ?>