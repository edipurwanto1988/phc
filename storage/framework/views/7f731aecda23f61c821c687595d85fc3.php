<?php $__env->startSection('title', 'Pengaturan Website'); ?>
<?php $__env->startSection('header'); ?>
<i class="ri-settings-3-line"></i> Pengaturan Website
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ activeTab: '<?php echo e(request('tab', 'general')); ?>' }">
    <!-- Tab Navigation Headers -->
    <div class="flex border-b border-gray-200 mb-6 bg-white p-2 rounded-lg border shadow-sm">
        <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-global-line"></i> Umum
        </button>
        <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-contacts-book-line"></i> Kontak
        </button>
        <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-share-line"></i> Sosial Media
        </button>
        <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-search-line"></i> SEO
        </button>
    </div>

    <div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tab" :value="activeTab">

            <!-- TAB: GENERAL -->
            <div x-show="activeTab === 'general'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Pengaturan Umum</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Website / Perusahaan</label>
                    <input type="text" name="settings[site_name]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('site_name')); ?>" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tagline Website</label>
                    <input type="text" name="settings[site_tagline]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('site_tagline')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Maps Embed iframe</label>
                    <textarea name="settings[google_maps_embed]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Paste <iframe> tag from Google Maps share panel..."><?php echo e(\App\Models\Setting::get('google_maps_embed')); ?></textarea>
                </div>
            </div>

            <!-- TAB: CONTACT -->
            <div x-show="activeTab === 'contact'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Informasi Kontak & Bisnis</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon Kantor</label>
                    <input type="text" name="settings[phone]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('phone')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Link wa.me (format: 628xxx)</label>
                    <input type="text" name="settings[whatsapp]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('whatsapp')); ?>">
                    <p class="text-[11px] text-gray-500 mt-1">Gunakan format angka murni (tanpa spasi / tanda tambah), diawali kode negara 62.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email Bisnis</label>
                    <input type="email" name="settings[email]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('email')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Fisik Kantor / Basecamp</label>
                    <textarea name="settings[address]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"><?php echo e(\App\Models\Setting::get('address')); ?></textarea>
                </div>
            </div>

            <!-- TAB: SOCIAL MEDIA -->
            <div x-show="activeTab === 'social'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Tautan Sosial Media</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram Username / Link</label>
                    <input type="text" name="settings[instagram]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('instagram')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook Fanpage / Link</label>
                    <input type="text" name="settings[facebook]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('facebook')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">TikTok Link / Username</label>
                    <input type="text" name="settings[tiktok]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('tiktok')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Threads Username / Link</label>
                    <input type="text" name="settings[threads]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('threads')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">LinkedIn Profile / Link</label>
                    <input type="text" name="settings[linkedin]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('linkedin')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">YouTube Channel / Link</label>
                    <input type="text" name="settings[youtube]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('youtube')); ?>">
                </div>
            </div>

            <!-- TAB: SEO -->
            <div x-show="activeTab === 'seo'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Search Engine Optimization (SEO)</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Search Console Verification Code</label>
                    <input type="text" name="settings[google_search_console]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('google_search_console')); ?>" placeholder="Contoh: google-site-verification=xxxxxx">
                    <p class="text-[11px] text-gray-500 mt-1">Masukkan kode verifikasi penelusuran Google Search Console Anda.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bing Webmaster Verification Code</label>
                    <input type="text" name="settings[bing_webmaster]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="<?php echo e(\App\Models\Setting::get('bing_webmaster')); ?>" placeholder="Contoh: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                    <p class="text-[11px] text-gray-500 mt-1">Masukkan kode verifikasi penelusuran Bing Webmaster Anda.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-100 mt-6">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/settings.blade.php ENDPATH**/ ?>