<?php $__env->startSection('title', 'Menu'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-menu-line"></i> Menu
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <div></div>
        <a href="<?php echo e(route('admin.menu.create')); ?>" class="btn btn-primary">
            <i class="ri-add-line mr-2"></i>Tambah
        </a>
    </div>
    <div class="p-6 bg-white rounded-b-xl" id="menu-container">
        <?php $__currentLoopData = ['header' => 'Header', 'footer' => 'Footer', 'sidebar' => 'Sidebar']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posisi => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $posisiMenus = $menus->where('posisi', $posisi)->whereNull('parent_id'); ?>
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-gray-550 uppercase tracking-wider mb-3"><?php echo e($label); ?></h3>
            <div class="space-y-3 min-h-[48px] droppable-zone" data-posisi="<?php echo e($posisi); ?>">
                <?php if($posisiMenus->count() > 0): ?>
                    <?php $__currentLoopData = $posisiMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="menu-item flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-4 py-3 cursor-grab active:cursor-grabbing hover:border-primary/50 hover:shadow-sm transition-all group"
                         data-id="<?php echo e($menu->id); ?>" draggable="true">
                        <i class="ri-drag-move-2-line text-gray-400 group-hover:text-gray-600 shrink-0"></i>
                        <?php if($menu->icon): ?>
                        <i class="<?php echo e($menu->icon); ?> text-lg text-gray-500 shrink-0"></i>
                        <?php endif; ?>
                        <span class="font-medium flex-1"><?php echo e($menu->nama); ?></span>
                        <span class="text-xs text-gray-400"><?php echo e($menu->url); ?></span>
                        <span class="px-2 py-0.5 text-xs rounded-full <?php echo e($menu->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>"><?php echo e($menu->status); ?></span>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                            <a href="<?php echo e(route('admin.menu.edit', $menu)); ?>" class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="ri-edit-line"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.menu.destroy', $menu)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1" onclick="return confirm('Yakin hapus?')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php $__currentLoopData = $menu->children->sortBy('urutan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="menu-item flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 cursor-grab active:cursor-grabbing hover:border-primary/50 hover:shadow-sm transition-all group ml-8"
                         data-id="<?php echo e($child->id); ?>" draggable="true">
                        <i class="ri-drag-move-2-line text-gray-400 group-hover:text-gray-600 shrink-0"></i>
                        <span class="text-gray-300 shrink-0 ml-1">└</span>
                        <?php if($child->icon): ?>
                        <i class="<?php echo e($child->icon); ?> text-base text-gray-400 shrink-0"></i>
                        <?php endif; ?>
                        <span class="font-medium flex-1 text-sm"><?php echo e($child->nama); ?></span>
                        <span class="text-xs text-gray-400"><?php echo e($child->url); ?></span>
                        <span class="px-2 py-0.5 text-xs rounded-full <?php echo e($child->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>"><?php echo e($child->status); ?></span>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                            <a href="<?php echo e(route('admin.menu.edit', $child)); ?>" class="text-blue-600 hover:text-blue-800 p-1">
                                <i class="ri-edit-line"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.menu.destroy', $child)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-650 hover:text-red-805 p-1" onclick="return confirm('Yakin hapus?')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <div class="text-sm text-gray-400 italic px-4 py-3 border border-dashed border-gray-200 rounded-lg">Belum ada menu di <?php echo e($label); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let draggedItem = null;
    let sourceContainer = null;

    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('dragstart', function(e) {
            draggedItem = item;
            sourceContainer = item.closest('[data-posisi]');
            setTimeout(() => item.classList.add('opacity-50'), 0);
            e.dataTransfer.effectAllowed = 'move';
        });

        item.addEventListener('dragend', function() {
            item.classList.remove('opacity-50');
            draggedItem = null;
            sourceContainer = null;
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('border-primary', 'border-t-4'));
            document.querySelectorAll('.droppable-zone').forEach(el => el.classList.remove('bg-primary/5'));
        });
    });

    document.querySelectorAll('.droppable-zone').forEach(container => {
        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            document.querySelectorAll('.droppable-zone').forEach(z => z.classList.remove('bg-primary/5'));
            container.classList.add('bg-primary/5');

            const afterElement = getDragAfterElement(container, e.clientY);
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('border-primary', 'border-t-4'));

            if (afterElement) {
                afterElement.classList.add('border-primary', 'border-t-4');
            }

            if (draggedItem) {
                if (afterElement) {
                    container.insertBefore(draggedItem, afterElement);
                } else {
                    container.appendChild(draggedItem);
                }
            }
        });

        container.addEventListener('drop', function(e) {
            e.preventDefault();
            document.querySelectorAll('.droppable-zone').forEach(z => z.classList.remove('bg-primary/5'));

            const posisi = container.dataset.posisi;
            const orderData = [];

            container.querySelectorAll('.menu-item').forEach((item, index) => {
                orderData.push({
                    id: item.dataset.id,
                    urutan: index + 1,
                    posisi: posisi
                });
            });

            fetch('<?php echo e(route("admin.menu.reorder")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: orderData })
            }).then(res => res.json());
        });
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.menu-item:not(.opacity-50)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            }
            return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/menu/index.blade.php ENDPATH**/ ?>