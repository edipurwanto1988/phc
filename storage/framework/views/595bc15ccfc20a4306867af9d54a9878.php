<?php $__env->startSection('title', 'Detail Pesanan'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-calendar-todo-line"></i> Pesanan: <?php echo e($order->order_number); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Order Details & Services List (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Details Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-information-line text-blue-600"></i> Detail Pesanan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Pelanggan</span>
                    <a href="<?php echo e(route('admin.customers.show', $order->customer)); ?>" class="text-sm font-semibold text-blue-600 hover:underline block mt-0.5">
                        <?php echo e($order->customer->nama); ?>

                    </a>
                    <span class="text-xs text-gray-500 block">WA: <?php echo e($order->customer->no_wa); ?></span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Jadwal Pengerjaan</span>
                    <span class="text-sm font-semibold text-gray-800 block mt-0.5">
                        <?php echo e($order->tanggal_jadwal->translatedFormat('d F Y, H:i')); ?> WIB
                    </span>
                    <span class="text-xs text-gray-500 block">Dibuat oleh: <?php echo e($order->creator->name ?? 'Sistem'); ?></span>
                    <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
                    <div class="mt-2">
                        <a href="<?php echo e(route('admin.orders.download-invoice', $order)); ?>" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1.5 rounded-lg text-xs inline-flex items-center gap-1.5 shadow-sm transition-all">
                            <i class="ri-download-2-line text-sm"></i> Download Invoice (PDF)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Alamat Pengerjaan</span>
                    <p class="text-sm font-medium text-gray-700 mt-1 leading-relaxed"><?php echo e($order->alamat_pengerjaan); ?></p>
                    <?php if($order->latitude && $order->longitude): ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e($order->latitude); ?>,<?php echo e($order->longitude); ?>" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-1 font-semibold">
                        <i class="ri-map-pin-line text-red-500"></i> Lihat di Google Maps
                    </a>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Catatan Order</span>
                    <p class="text-xs text-gray-600 bg-gray-50 border border-gray-100 p-2.5 rounded-lg mt-1 italic leading-relaxed">
                        <?php echo e($order->catatan ?? 'Tidak ada catatan tambahan.'); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-file-list-3-line text-blue-600"></i> Layanan yang Dipesan
                </h3>
                <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
                <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" class="btn border border-blue-600 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 font-semibold transition-all">
                    <i class="ri-edit-line"></i> Edit Layanan
                </a>
                <?php endif; ?>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase">Nama Jasa</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-center w-20">Qty</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Harga Satuan</th>
                            <th class="py-2.5 px-4 text-xs font-bold text-gray-500 uppercase text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800">
                                <?php echo e($item->service->nama); ?>

                                <?php if($item->catatan): ?>
                                <div class="text-[11px] text-gray-400 italic font-normal">catatan: <?php echo e($item->catatan); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 text-center font-medium">
                                <?php echo e($item->qty); ?> <?php echo e($item->satuan); ?>

                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 text-right font-medium">
                                Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 px-4 text-sm font-bold text-gray-800 text-right">
                                Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Financial summary -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                <div class="w-full md:w-64 space-y-2 text-sm text-gray-600 text-right">
                    <div class="flex justify-between">
                        <span>Total Jasa:</span>
                        <span class="font-bold text-gray-800">Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></span>
                    </div>
                    <div class="flex justify-between text-red-500">
                        <span>Potongan Harga:</span>
                        <span>- Rp <?php echo e(number_format($order->diskon, 0, ',', '.')); ?></span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                        <span>Grand Total:</span>
                        <span class="text-blue-600">Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Operational Controls & Cleaner Assignments (1/3 width) -->
    <div class="space-y-6">
        <!-- Status & Payment controls (Hanya untuk Admin/Super Admin yang punya manage_orders/edit_orders) -->
        <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-settings-line text-blue-600"></i> Kontrol Status
            </h3>
            
            <form method="POST" action="<?php echo e(route('admin.orders.status', $order)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <!-- Status Pengerjaan -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Order</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                        <option value="pending" <?php echo e($order->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="confirmed" <?php echo e($order->status === 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                        <option value="in_progress" <?php echo e($order->status === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                        <option value="completed" <?php echo e($order->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>

                <!-- Status Pembayaran -->
                <div>
                    <label for="status_bayar" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Pembayaran</label>
                    <select name="status_bayar" id="status_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                        <option value="unpaid" <?php echo e($order->status_bayar === 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                        <option value="partial" <?php echo e($order->status_bayar === 'partial' ? 'selected' : ''); ?>>Partial</option>
                        <option value="paid" <?php echo e($order->status_bayar === 'paid' ? 'selected' : ''); ?>>Paid</option>
                    </select>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Metode Pembayaran</span>
                    <span class="text-sm font-semibold text-gray-700 block mt-0.5"><?php echo e(ucfirst($order->metode_bayar ?? 'Belum ditentukan')); ?></span>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Koordinat Lokasi Card (Bisa diakses Cleaner & Admin/Super Admin) -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-map-pin-line text-blue-600"></i> Koordinat Lokasi
            </h3>
            
            <form method="POST" action="<?php echo e(route('admin.orders.coordinates', $order)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="latitude" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" value="<?php echo e($order->latitude); ?>">
                </div>
                <div>
                    <label for="longitude" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" value="<?php echo e($order->longitude); ?>">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="getCurrentCoordinates()" class="btn flex-1 border border-gray-300 bg-white hover:bg-gray-50 text-blue-600 font-semibold px-3 py-2 rounded-lg text-xs flex justify-center items-center gap-1.5 transition-colors" title="Deteksi GPS">
                        <i class="ri-gps-line text-sm"></i> Deteksi
                    </button>
                    <button type="submit" class="btn flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-2 rounded-lg text-xs transition-all shadow-sm">
                        Simpan
                    </button>
                </div>
                <p class="text-[10px] text-gray-500 text-center">Simpan koordinat agar mempermudah tim lapangan menuju lokasi pelanggan.</p>
            </form>
        </div>

        <!-- Cleaner Assignments Card -->
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-2 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ri-user-star-line text-blue-600"></i> Cleaner & Informasi Gaji
            </h3>
            
            <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
            <p class="text-xs text-gray-500 mb-4 italic"><i class="ri-drag-drop-line"></i> Seret (drag & drop) untuk mengurutkan. Cleaner teratas otomatis menjadi **PIC**.</p>
            <?php endif; ?>
            
            <div id="cleaners-sortable" class="space-y-4 mb-6">
                <?php $__empty_1 = true; $__currentLoopData = $order->assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 border border-gray-250 rounded-xl bg-gray-50 space-y-3 relative <?php echo e((auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')) ? 'cursor-grab active:cursor-grabbing' : ''); ?>" data-id="<?php echo e($assignment->id); ?>" x-data="{
                    gaji: '<?php echo e((int)$assignment->gaji); ?>',
                    statusGaji: '<?php echo e($assignment->status_gaji); ?>',
                    saving: false,
                    saved: false,
                    error: false,
                    async submitGaji() {
                        this.saving = true;
                        this.saved = false;
                        this.error = false;
                        try {
                            let response = await fetch('<?php echo e(route('admin.orders.update-gaji', $assignment)); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    gaji: this.gaji,
                                    status_gaji: this.statusGaji
                                })
                            });
                            
                            let resData = await response.json();
                            if (response.ok && resData.success) {
                                this.saved = true;
                                setTimeout(() => this.saved = false, 2000);
                            } else {
                                this.error = true;
                            }
                        } catch (err) {
                            this.error = true;
                        } finally {
                            this.saving = false;
                        }
                    }
                }">
                    <!-- Action Buttons Header (Hanya untuk Admin/Super Admin) -->
                    <div class="flex items-center justify-between pb-2 border-b border-gray-150">
                        <div>
                            <?php if($index === 0): ?>
                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase bg-blue-600 text-white rounded-lg tracking-wider">PIC / Leader</span>
                            <?php endif; ?>
                        </div>
                        <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
                        <div class="flex items-center gap-1.5 bg-gray-100 px-2 py-0.5 rounded-lg">
                            <form method="POST" action="<?php echo e(route('admin.orders.delete-assignment', $assignment)); ?>" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cleaner ini dari tugas?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 flex items-center justify-center rounded" title="Hapus Cleaner">
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </form>
                            <span class="text-gray-400 cursor-move p-1 flex items-center justify-center" title="Geser untuk Urutkan"><i class="ri-drag-move-2-line text-base"></i></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-gray-800"><?php echo e($assignment->cleaner->name); ?></div>
                            <div class="text-[10px] text-gray-400 mt-1">Status Tugas: 
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                    <?php if($assignment->status === 'assigned'): ?> bg-blue-100 text-blue-700
                                    <?php elseif($assignment->status === 'on_the_way'): ?> bg-yellow-100 text-yellow-700
                                    <?php elseif($assignment->status === 'working'): ?> bg-purple-100 text-purple-700
                                    <?php else: ?> bg-green-100 text-green-700 <?php endif; ?>">
                                    <?php echo e($assignment->status); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Gaji Form (Hanya untuk Admin/Super Admin) -->
                    <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
                    <div class="border-t border-gray-200 pt-3 space-y-2" x-data="{isPaidExpense: <?php echo e($assignment->expense_id ? 'true' : 'false'); ?> }">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-0.5">Gaji Cleaner (Rp)</label>
                                <input type="number" x-model="gaji" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-250 disabled:text-gray-500" placeholder="Contoh: 150000" min="0" required :disabled="isPaidExpense">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-0.5">Status Gaji</label>
                                <select x-model="statusGaji" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:bg-gray-250 disabled:text-gray-500" required :disabled="isPaidExpense">
                                    <option value="belum_dibayar">Belum Dibayar</option>
                                    <option value="sudah_dibayar">Sudah Dibayar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-1 pb-2">
                            <!-- Status feedback -->
                            <div class="text-[11px]">
                                <span class="text-gray-400 flex items-center gap-1" x-show="saving">
                                    <i class="ri-loader-4-line animate-spin text-sm"></i> Menyimpan...
                                </span>
                                <span class="text-green-650 font-bold flex items-center gap-1" x-show="saved">
                                    <i class="ri-checkbox-circle-line text-sm"></i> Tersimpan
                                </span>
                                <span class="text-red-500 font-bold flex items-center gap-1" x-show="error">
                                    <i class="ri-error-warning-line text-sm"></i> Gagal menyimpan
                                </span>
                                <span class="text-blue-600 font-semibold flex items-center gap-1" x-show="isPaidExpense">
                                    <i class="ri-lock-line text-sm"></i> Slip Gaji Telah Dibuat
                                </span>
                            </div>
                            
                            <!-- Save Button (Icon Only) -->
                            <button type="button" @click="submitGaji()" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold p-2 rounded-lg text-sm transition-all shadow-sm flex items-center justify-center disabled:opacity-50" title="Simpan Gaji" :disabled="isPaidExpense">
                                <i class="ri-save-line text-base"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Foto Sebelum & Sesudah Section (AJAX Auto Upload - Bisa diakses oleh Cleaner yang bersangkutan atau Admin) -->
                    <?php if(auth()->user()->id === $assignment->user_id || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
                    <div class="border-t border-gray-250 pt-3 space-y-2" x-data="{
                        uploadPhoto(field, event) {
                            let file = event.target.files[0];
                            if (!file) return;

                            let formData = new FormData();
                            formData.append(field, file);
                            formData.append('_token', '<?php echo e(csrf_token()); ?>');

                            // Show progress/loader on parent container or input
                            $data.saving = true;
                            $data.saved = false;
                            $data.error = false;

                            fetch('<?php echo e(route('admin.orders.upload-photos', $assignment)); ?>', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(res => {
                                if (res.success) {
                                    $data.saved = true;
                                    setTimeout(() => window.location.reload(), 800);
                                } else {
                                    $data.error = true;
                                }
                            })
                            .catch(err => {
                                $data.error = true;
                            })
                            .finally(() => {
                                $data.saving = false;
                            });
                        }
                    }">
                        <h4 class="text-[9px] font-bold text-gray-500 uppercase tracking-wide">Dokumentasi Kerja</h4>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Foto Sebelum -->
                            <div class="space-y-1">
                                <span class="block text-[8px] font-bold text-gray-400 uppercase">Sebelum</span>
                                <?php if($assignment->foto_sebelum): ?>
                                    <div class="relative group w-full h-20 rounded-lg overflow-hidden border border-gray-200 bg-black">
                                        <img src="<?php echo e(asset($assignment->foto_sebelum)); ?>" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                                        <a href="<?php echo e(asset($assignment->foto_sebelum)); ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity text-white text-[10px] font-semibold">
                                            <i class="ri-eye-line mr-0.5"></i> Lihat
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="w-full text-[9px] text-gray-400 file:mr-1 file:py-0.5 file:px-1.5 file:rounded file:border-0 file:text-[9px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" @change="uploadPhoto('foto_sebelum', $event)">
                            </div>

                            <!-- Foto Sesudah -->
                            <div class="space-y-1">
                                <span class="block text-[8px] font-bold text-gray-400 uppercase">Sesudah</span>
                                <?php if($assignment->foto_sesudah): ?>
                                    <div class="relative group w-full h-20 rounded-lg overflow-hidden border border-gray-200 bg-black">
                                        <img src="<?php echo e(asset($assignment->foto_sesudah)); ?>" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                                        <a href="<?php echo e(asset($assignment->foto_sesudah)); ?>" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity text-white text-[10px] font-semibold">
                                            <i class="ri-eye-line mr-0.5"></i> Lihat
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="w-full text-[9px] text-gray-400 file:mr-1 file:py-0.5 file:px-1.5 file:rounded file:border-0 file:text-[9px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" @change="uploadPhoto('foto_sesudah', $event)">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-xs text-red-500 font-semibold p-3 border border-red-50 bg-red-50 rounded-lg text-center">
                    <i class="ri-alert-line mr-1"></i> Belum ada cleaner yang ditugaskan ke order ini.
                </div>
                <?php endif; ?>
            </div>

            <!-- Assignment form (Hanya untuk Admin/Super Admin) -->
            <?php if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('edit_orders')): ?>
            <form method="POST" action="<?php echo e(route('admin.orders.assign', $order)); ?>" class="pt-4 border-t border-gray-100">
                <?php echo csrf_field(); ?>
                <label for="cleaner_id" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tugaskan Cleaner Baru</label>
                <div class="flex gap-2">
                    <select name="cleaner_id" id="cleaner_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="">-- Pilih Cleaner --</option>
                        <?php $__currentLoopData = $cleaners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cleaner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$order->assignments->contains('user_id', $cleaner->id)): ?>
                            <option value="<?php echo e($cleaner->id); ?>"><?php echo e($cleaner->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-all shadow-sm">
                        Assign
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('cleaners-sortable');
    if (el) {
        Sortable.create(el, {
            animation: 150,
            ghostClass: 'bg-blue-50',
            onEnd: async function () {
                let ids = [];
                el.querySelectorAll('[data-id]').forEach(item => {
                    ids.push(item.getAttribute('data-id'));
                });

                try {
                    let response = await fetch('<?php echo e(route('admin.orders.assignments-reorder', $order)); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: ids })
                    });
                    
                    if (response.ok) {
                        // Reload the page smoothly or update the DOM to show the new PIC tag
                        window.location.reload();
                    } else {
                        alert('Gagal mengurutkan cleaner.');
                    }
                } catch (err) {
                    alert('Terjadi kesalahan koneksi.');
                }
            }
        });
    }
});

function getCurrentCoordinates() {
    const btn = document.querySelector('[onclick="getCurrentCoordinates()"]');
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung fitur GPS / Geolocation.');
        return;
    }

    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-sm"></i> ...';
    btn.disabled = true;
    btn.classList.add('opacity-60');

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
            document.getElementById('longitude').value = position.coords.longitude.toFixed(8);

            btn.innerHTML = '<i class="ri-checkbox-circle-line text-sm text-green-600"></i> Sukses';
            btn.classList.remove('opacity-60');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 2000);
        },
        function (error) {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            btn.classList.remove('opacity-60');

            const messages = {
                1: 'Akses lokasi ditolak. Harap izinkan akses lokasi di browser Anda.',
                2: 'Lokasi tidak dapat ditentukan. Pastikan GPS aktif.',
                3: 'Waktu deteksi habis. Coba lagi.',
            };
            alert(messages[error.code] || 'Gagal mendeteksi koordinat.');
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>