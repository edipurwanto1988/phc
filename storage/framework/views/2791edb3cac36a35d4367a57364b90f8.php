<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-dashboard-line"></i> Dashboard Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Active Orders -->
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <i class="ri-calendar-event-line text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Order Aktif</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['active_orders']); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Completed Orders -->
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <i class="ri-checkbox-circle-line text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Order Selesai</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['completed_orders']); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Active Customers -->
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
                <i class="ri-team-line text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Customer</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['total_customers']); ?></p>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <i class="ri-money-dollar-circle-line text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($stats['total_revenue'], 0, ',', '.')); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Layout Grid: Chart + Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
    <!-- Revenue Trend Chart (2/3 width on large screens) -->
    <div class="lg:col-span-2 card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-line-chart-line text-blue-600"></i> Tren Pendapatan Bulanan
        </h3>
        <div style="height: 350px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <!-- Quick Stats Summary (1/3 width) -->
    <div class="card p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-pie-chart-line text-blue-600"></i> Statistik Sistem
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600 font-medium">Total Order Masuk</span>
                    <span class="text-base font-bold text-gray-800"><?php echo e($stats['total_orders']); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600 font-medium">Jumlah Master Jasa</span>
                    <span class="text-base font-bold text-gray-800"><?php echo e($stats['total_services']); ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600 font-medium">Total Cleaner Aktif</span>
                    <span class="text-base font-bold text-gray-800"><?php echo e($stats['cleaners_count']); ?></span>
                </div>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="<?php echo e(route('admin.orders.create')); ?>" class="w-full btn btn-primary flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition-all shadow-sm">
                <i class="ri-add-line"></i> Input Order Baru
            </a>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="mt-8 card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <i class="ri-file-list-3-line text-blue-600"></i> 5 Pesanan Terbaru
        </h3>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-sm text-blue-600 hover:underline font-medium">
            Lihat Semua Pesanan <i class="ri-arrow-right-s-line"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">No. Order</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Customer</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600">Jadwal Pengerjaan</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-right">Grand Total</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center">Status</th>
                    <th class="py-3.5 px-6 text-sm font-semibold text-gray-600 text-center">Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-6 text-sm font-semibold text-blue-600">
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>"><?php echo e($order->order_number); ?></a>
                    </td>
                    <td class="py-3.5 px-6">
                        <div class="text-sm font-medium text-gray-800"><?php echo e($order->customer->nama); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($order->customer->no_wa); ?></div>
                    </td>
                    <td class="py-3.5 px-6 text-sm text-gray-600">
                        <?php echo e($order->tanggal_jadwal->translatedFormat('d M Y, H:i')); ?> WIB
                    </td>
                    <td class="py-3.5 px-6 text-sm font-semibold text-gray-800 text-right">
                        Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>

                    </td>
                    <td class="py-3.5 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            <?php if($order->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                            <?php elseif($order->status === 'confirmed'): ?> bg-blue-100 text-blue-700
                            <?php elseif($order->status === 'in_progress'): ?> bg-purple-100 text-purple-700
                            <?php elseif($order->status === 'completed'): ?> bg-green-100 text-green-700
                            <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $order->status))); ?>

                        </span>
                    </td>
                    <td class="py-3.5 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            <?php if($order->status_bayar === 'paid'): ?> bg-green-100 text-green-700
                            <?php elseif($order->status_bayar === 'partial'): ?> bg-blue-100 text-blue-700
                            <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                            <?php echo e(ucfirst($order->status_bayar)); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data pesanan masuk.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months, 15, 512) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?php echo json_encode($revenueData, 15, 512) ?>,
                borderColor: '#2563eb', // Blue-600
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#2563eb',
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + 'jt';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: { size: 11 }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>