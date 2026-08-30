@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('header')
<i class="ri-settings-3-line"></i> Pengaturan Website
@endsection

@section('content')
<div x-data="{ activeTab: '{{ request('tab', 'general') }}' }">
    <!-- Tab Navigation Headers -->
    <div class="flex border-b border-gray-200 mb-6 bg-white p-2 rounded-lg border shadow-sm flex-wrap gap-2">
        <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 min-w-[120px] py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-global-line"></i> Umum
        </button>
        <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 min-w-[120px] py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-contacts-book-line"></i> Kontak
        </button>
        <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 min-w-[120px] py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-share-line"></i> Sosial Media
        </button>
        <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 min-w-[120px] py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-search-line"></i> SEO
        </button>
        <button @click="activeTab = 'gdrive'" :class="activeTab === 'gdrive' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-650 hover:bg-gray-55'" class="flex-1 min-w-[120px] py-2 px-4 rounded-lg text-sm transition-all">
            <i class="ri-google-line"></i> Google Drive
        </button>
    </div>

    <div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <input type="hidden" name="tab" :value="activeTab">

            <!-- TAB: GENERAL -->
            <div x-show="activeTab === 'general'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Pengaturan Umum</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Website / Perusahaan</label>
                    <input type="text" name="settings[site_name]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('site_name') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tagline Website</label>
                    <input type="text" name="settings[site_tagline]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('site_tagline') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Maps Embed iframe</label>
                    <textarea name="settings[google_maps_embed]" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Paste <iframe> tag from Google Maps share panel...">{{ \App\Models\Setting::get('google_maps_embed') }}</textarea>
                </div>
            </div>

            <!-- TAB: CONTACT -->
            <div x-show="activeTab === 'contact'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Informasi Kontak & Bisnis</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon Kantor</label>
                    <input type="text" name="settings[phone]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('phone') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Link wa.me (format: 628xxx)</label>
                    <input type="text" name="settings[whatsapp]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('whatsapp') }}">
                    <p class="text-[11px] text-gray-500 mt-1">Gunakan format angka murni (tanpa spasi / tanda tambah), diawali kode negara 62.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email Bisnis</label>
                    <input type="email" name="settings[email]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('email') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Fisik Kantor / Basecamp</label>
                    <textarea name="settings[address]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ \App\Models\Setting::get('address') }}</textarea>
                </div>
            </div>

            <!-- TAB: SOCIAL MEDIA -->
            <div x-show="activeTab === 'social'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Tautan Sosial Media</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram Username / Link</label>
                    <input type="text" name="settings[instagram]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('instagram') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook Fanpage / Link</label>
                    <input type="text" name="settings[facebook]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('facebook') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">TikTok Link / Username</label>
                    <input type="text" name="settings[tiktok]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('tiktok') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Threads Username / Link</label>
                    <input type="text" name="settings[threads]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('threads') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">LinkedIn Profile / Link</label>
                    <input type="text" name="settings[linkedin]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('linkedin') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">YouTube Channel / Link</label>
                    <input type="text" name="settings[youtube]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('youtube') }}">
                </div>
            </div>

            <!-- TAB: SEO -->
            <div x-show="activeTab === 'seo'" x-cloak class="space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-gray-100 pb-2 mb-4">Search Engine Optimization (SEO)</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Search Console Verification Code</label>
                    <input type="text" name="settings[google_search_console]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('google_search_console') }}" placeholder="Contoh: google-site-verification=xxxxxx">
                    <p class="text-[11px] text-gray-500 mt-1">Masukkan kode verifikasi penelusuran Google Search Console Anda.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bing Webmaster Verification Code</label>
                    <input type="text" name="settings[bing_webmaster]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ \App\Models\Setting::get('bing_webmaster') }}" placeholder="Contoh: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                    <p class="text-[11px] text-gray-500 mt-1">Masukkan kode verifikasi penelusuran Bing Webmaster Anda.</p>
                </div>
            </div>

            <!-- TAB: GOOGLE DRIVE -->
            <div x-show="activeTab === 'gdrive'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-sm font-bold text-blue-600">Integrasi Google Drive</h3>
                    @php $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true'; @endphp
                    @if($gdriveConnected)
                    <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="ri-checkbox-circle-fill"></i> Terhubung ke Google Drive
                    </span>
                    @else
                    <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="ri-close-circle-fill"></i> Belum Terhubung
                    </span>
                    @endif
                </div>

                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-xs text-gray-650 space-y-2">
                    <p class="font-bold text-gray-800">Bagaimana Cara Kerja Integrasi Ini?</p>
                    <p>Setelah Anda menghubungkan Google Drive, setiap dokumentasi kerja (foto sebelum & sesudah pengerjaan) yang diupload oleh cleaner akan disimpan langsung secara otomatis ke Google Drive PHC.</p>
                    <p>Sistem akan membuat folder dinamis per pesanan (Contoh: <code class="bg-gray-200 px-1 py-0.5 rounded font-mono text-blue-600">PHC_Orders/TRX-xxxx/</code>) dan mengunggah file foto kesana secara otomatis.</p>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Google Client ID</label>
                            <input type="text" name="settings[gdrive_client_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" value="{{ \App\Models\Setting::get('gdrive_client_id') }}" placeholder="Contoh: 12345678-xxxx.apps.googleusercontent.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Google Client Secret</label>
                            <input type="password" name="settings[gdrive_client_secret]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" value="{{ \App\Models\Setting::get('gdrive_client_secret') }}" placeholder="••••••••••••••••••••••••">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Target Sync Folder ID (Opsional)</label>
                        <input type="text" name="settings[gdrive_folder_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" value="{{ \App\Models\Setting::get('gdrive_folder_id') }}" placeholder="Contoh: 1a2b3c4d5e6f7g8h9i0j-xxxxxxxx (Biarkan kosong untuk Root Google Drive)">
                    </div>

                    <div class="pt-2">
                        @if($gdriveConnected)
                        <div class="flex items-center gap-3">
                            <!-- Hidden input to maintain connection status -->
                            <input type="hidden" name="settings[gdrive_connected]" value="true">
                            <button type="button" onclick="disconnectGoogleDrive()" class="btn border border-red-500 hover:bg-red-50 text-red-600 font-semibold py-2 px-4 rounded-lg text-xs transition-colors">
                                <i class="ri-logout-box-line mr-1"></i> Putuskan Koneksi
                            </button>
                        </div>
                        @else
                        <div class="flex items-center gap-3">
                            <input type="hidden" id="gdrive_conn_hidden" name="settings[gdrive_connected]" value="false">
                            <button type="button" onclick="connectGoogleDrive()" class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-xs shadow-sm transition-all">
                                <i class="ri-login-box-line mr-1"></i> Hubungkan ke Google Drive
                            </button>
                        </div>
                        @endif
                    </div>
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
@endsection

@section('scripts')
<script>
function connectGoogleDrive() {
    const clientInput = document.querySelector('input[name="settings[gdrive_client_id]"]');
    const secretInput = document.querySelector('input[name="settings[gdrive_client_secret]"]');

    if (!clientInput.value || !secretInput.value) {
        alert('Mohon isi Google Client ID dan Google Client Secret terlebih dahulu.');
        return;
    }

    if (confirm('Hubungkan PHC dengan Google Drive menggunakan kredensial ini?')) {
        document.getElementById('gdrive_conn_hidden').value = 'true';
        clientInput.form.submit();
    }
}

function disconnectGoogleDrive() {
    if (confirm('Apakah Anda yakin ingin memutuskan integrasi Google Drive?')) {
        const form = document.querySelector('form');
        const connHidden = document.createElement('input');
        connHidden.type = 'hidden';
        connHidden.name = 'settings[gdrive_connected]';
        connHidden.value = 'false';
        form.appendChild(connHidden);
        form.submit();
    }
}
</script>
@endsection
