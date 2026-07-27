<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('service_categories')->truncate();
        DB::table('services')->truncate();
        DB::table('customers')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_items')->truncate();
        DB::table('order_assignments')->truncate();
        DB::table('testimonials')->truncate();
        DB::table('settings')->truncate();
        DB::table('posts')->truncate();
        DB::table('menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'permissions' => json_encode(['all']), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Admin', 'permissions' => json_encode(['manage_services', 'manage_customers', 'manage_orders', 'view_orders', 'update_order_status', 'view_reports', 'manage_blog', 'view_own_profile']), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Staff', 'permissions' => json_encode(['manage_customers', 'manage_orders', 'view_orders', 'update_order_status', 'view_own_profile']), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Cleaner', 'permissions' => json_encode(['view_orders', 'update_order_status', 'view_own_profile']), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Customer', 'permissions' => json_encode(['view_orders', 'view_own_profile']), 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed Users (Super Admin, Admin, Staff, Cleaner, Customer)
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin PHC',
                'username' => 'superadmin',
                'email' => 'superadmin@phc.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Admin Operasional',
                'username' => 'admin',
                'email' => 'admin@phc.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Staff Operator',
                'username' => 'staff',
                'email' => 'staff@phc.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Rian Hidayat (Cleaner)',
                'username' => 'cleaner1',
                'email' => 'rian@phc.com',
                'password' => Hash::make('password'),
                'role_id' => 4,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Rudi Hartono (Cleaner)',
                'username' => 'cleaner2',
                'email' => 'rudi@phc.com',
                'password' => Hash::make('password'),
                'role_id' => 4,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Budi Santoso (Customer)',
                'username' => 'budi_santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 5,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Seed Service Categories
        DB::table('service_categories')->insert([
            ['id' => 1, 'nama' => 'Cleaning Rumah', 'slug' => 'cleaning-rumah', 'deskripsi' => 'Layanan kebersihan menyeluruh untuk rumah dan ruangan Anda.', 'icon' => 'ri-home-4-line', 'is_active' => true, 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Sofa & Karpet', 'slug' => 'sofa-karpet', 'deskripsi' => 'Layanan cuci sofa, karpet, dan spring bed profesional.', 'icon' => 'ri-sofa-line', 'is_active' => true, 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'AC', 'slug' => 'ac', 'deskripsi' => 'Pembersihan dan service AC berkala.', 'icon' => 'ri-temp-cold-line', 'is_active' => true, 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Poles Lantai', 'slug' => 'poles-lantai', 'deskripsi' => 'Poles marmer, granit, dan lantai teraso.', 'icon' => 'ri-sparkling-line', 'is_active' => true, 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'Fumigasi', 'slug' => 'fumigasi', 'deskripsi' => 'Fogging anti nyamuk dan pembasmian hama.', 'icon' => 'ri-bubble-chart-line', 'is_active' => true, 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed Services
        DB::table('services')->insert([
            // Category: Cleaning Rumah
            [
                'id' => 1,
                'kategori_id' => 1,
                'nama' => 'General Cleaning',
                'slug' => 'general-cleaning',
                'deskripsi' => 'Pembersihan standar berkala untuk seluruh area rumah.',
                'deskripsi_singkat' => 'Pembersihan menyeluruh untuk rumah Anda. Lantai, kaca, kamar mandi, dan area lainnya.',
                'harga' => 350000.00,
                'satuan' => 'sesi',
                'durasi_estimasi' => 120,
                'gambar' => 'general-cleaning.jpg',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'kategori_id' => 1,
                'nama' => 'Deep Cleaning',
                'slug' => 'deep-cleaning',
                'deskripsi' => 'Pembersihan mendalam untuk noda membandel dan area tersembunyi.',
                'deskripsi_singkat' => 'Pembersihan mendalam hingga sudut-sudut tersembunyi. Cocok untuk rumah yang lama tidak dibersihkan.',
                'harga' => 500000.00,
                'satuan' => 'ruangan',
                'durasi_estimasi' => 180,
                'gambar' => 'deep-cleaning.jpg',
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'kategori_id' => 1,
                'nama' => 'Post-Renovation Cleaning',
                'slug' => 'post-renovation-cleaning',
                'deskripsi' => 'Pembersihan intensif setelah proses renovasi atau pembangunan selesai.',
                'deskripsi_singkat' => 'Pembersihan menyeluruh setelah renovasi agar rumah siap langsung huni.',
                'harga' => 800000.00,
                'satuan' => 'm²',
                'durasi_estimasi' => 300,
                'gambar' => 'post-renovation.jpg',
                'is_active' => true,
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Category: Sofa & Karpet
            [
                'id' => 4,
                'kategori_id' => 2,
                'nama' => 'Cuci Sofa (3-seater)',
                'slug' => 'cuci-sofa-3-seater',
                'deskripsi' => 'Pencucian sofa dengan cairan khusus pembersih noda dan vakum ekstraktor.',
                'deskripsi_singkat' => 'Cuci sofa profesional dengan teknik ekstraksi. Hilangkan noda, debu, dan bau tidak sedap.',
                'harga' => 200000.00,
                'satuan' => 'unit',
                'durasi_estimasi' => 90,
                'gambar' => 'cuci-sofa.jpg',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'kategori_id' => 2,
                'nama' => 'Cuci Karpet',
                'slug' => 'cuci-karpet',
                'deskripsi' => 'Cuci karpet ruangan besar / masjid dengan pengeringan cepat.',
                'deskripsi_singkat' => 'Cuci karpet menyeluruh untuk menghilangkan tungau dan kotoran.',
                'harga' => 50000.00,
                'satuan' => 'm²',
                'durasi_estimasi' => 60,
                'gambar' => 'cuci-karpet.jpg',
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'kategori_id' => 2,
                'nama' => 'Cuci Spring Bed',
                'slug' => 'cuci-spring-bed',
                'deskripsi' => 'Pembersihan kasur/spring bed ukuran double dengan teknologi anti tungau.',
                'deskripsi_singkat' => 'Bersihkan tungau, debu, dan noda pada kasur. Tidur lebih nyenyak dan sehat.',
                'harga' => 250000.00,
                'satuan' => 'unit',
                'durasi_estimasi' => 120,
                'gambar' => 'cuci-springbed.jpg',
                'is_active' => true,
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Category: AC
            [
                'id' => 7,
                'kategori_id' => 3,
                'nama' => 'Cuci AC (0.5–1 PK)',
                'slug' => 'cuci-ac-0-5-1-pk',
                'deskripsi' => 'Cuci filter dan evaporator AC agar dingin kembali.',
                'deskripsi_singkat' => 'Service dan cuci AC agar dingin maksimal dan hemat listrik.',
                'harga' => 80000.00,
                'satuan' => 'unit',
                'durasi_estimasi' => 45,
                'gambar' => 'cuci-ac.jpg',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'kategori_id' => 3,
                'nama' => 'Cuci AC (1.5–2 PK)',
                'slug' => 'cuci-ac-1-5-2-pk',
                'deskripsi' => 'Cuci AC ukuran sedang hingga besar untuk apartemen atau kantor.',
                'deskripsi_singkat' => 'Service dan cuci AC agar dingin maksimal dan hemat listrik.',
                'harga' => 100000.00,
                'satuan' => 'unit',
                'durasi_estimasi' => 60,
                'gambar' => 'cuci-ac-besar.jpg',
                'is_active' => true,
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Category: Poles Lantai
            [
                'id' => 9,
                'kategori_id' => 4,
                'nama' => 'Poles Lantai Marmer',
                'slug' => 'poles-lantai-marmer',
                'deskripsi' => 'Pembersihan kerak dan poles marmer agar berkilau kembali seperti baru.',
                'deskripsi_singkat' => 'Poles lantai marmer, granit, dan teraso. Kembalikan kilau alami lantai Anda.',
                'harga' => 75000.00,
                'satuan' => 'm²',
                'durasi_estimasi' => 180,
                'gambar' => 'poles-lantai.jpg',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Category: Fumigasi
            [
                'id' => 10,
                'kategori_id' => 5,
                'nama' => 'Fogging Anti Nyamuk',
                'slug' => 'fogging-anti-nyamuk',
                'deskripsi' => 'Penyemprotan asap pembasmi nyamuk demam berdarah dan serangga lainnya.',
                'deskripsi_singkat' => 'Fogging pembasmian serangga untuk rumah / halaman.',
                'harga' => 300000.00,
                'satuan' => 'sesi',
                'durasi_estimasi' => 60,
                'gambar' => 'fogging.jpg',
                'is_active' => true,
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 5. Seed Customers
        DB::table('customers')->insert([
            [
                'id' => 1,
                'user_id' => 6,
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. HR. Soebrantas, Perumahan Melati Indah Blok C No. 12',
                'kelurahan' => 'Sidomulyo Barat',
                'kecamatan' => 'Tampan',
                'kota' => 'Pekanbaru',
                'latitude' => 0.46820000,
                'longitude' => 101.37890000,
                'no_wa' => '6281234567890',
                'email' => 'budi@gmail.com',
                'sumber_info' => 'Instagram',
                'catatan' => 'Pelihara kucing ramah, pengerjaan sebaiknya hari libur.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'user_id' => null, // Guest customer
                'nama' => 'Sari Dewi',
                'alamat' => 'Jl. Arifin Ahmad, Gg. Damai No. 4',
                'kelurahan' => 'Sidomulyo Timur',
                'kecamatan' => 'Marpoyan Damai',
                'kota' => 'Pekanbaru',
                'latitude' => 0.48510000,
                'longitude' => 101.42310000,
                'no_wa' => '6289876543210',
                'email' => 'saridewi@gmail.com',
                'sumber_info' => 'Google Maps',
                'catatan' => 'Minta disinfektan ekstra di area dapur.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 6. Seed Settings
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'Pekanbaru Home Cleaning', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_tagline', 'value' => 'Jasa Cleaning Profesional #1 di Pekanbaru', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'value' => '0761-12345', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp', 'value' => '6281234567890', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'value' => 'info@phc-pekanbaru.com', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'address', 'value' => 'Jl. HR. Soebrantas, Pekanbaru, Riau', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'instagram', 'value' => '@phc.pekanbaru', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook', 'value' => 'PHCPekanbaru', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tiktok', 'value' => '@phc.pekanbaru', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'google_maps_embed', 'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.39572418512!2d101.3789!3d0.4682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5acdfa785e72d%3A0x6a053c89b3f309a4!2sPekanbaru%2C%20Riau!5e0!3m2!1sid!2sid!4v1700000000000" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'google_search_console', 'value' => '', 'group' => 'seo', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bing_webmaster', 'value' => '', 'group' => 'seo', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 7. Seed Testimonials
        DB::table('testimonials')->insert([
            [
                'customer_id' => 1,
                'nama' => 'Budi Santoso',
                'konten' => 'Sangat puas dengan hasil cleaning-nya. Rumah jadi bersih dan wangi. Tim PHC sangat profesional dan ramah. Pasti akan pakai lagi!',
                'rating' => 5,
                'is_featured' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => 2,
                'nama' => 'Sari Dewi',
                'konten' => 'Sofa yang sudah kotor bertahun-tahun jadi seperti baru lagi. Harganya juga terjangkau dibanding tempat lain. Highly recommended!',
                'rating' => 5,
                'is_featured' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => null,
                'nama' => 'Ahmad Rizki',
                'konten' => 'AC rumah jadi dingin lagi setelah dicuci PHC. Prosesnya cepat dan bersih, tidak berantakan. Terima kasih PHC!',
                'rating' => 5,
                'is_featured' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 8. Seed Posts (Blog Articles)
        DB::table('posts')->insert([
            [
                'user_id' => 2,
                'judul' => '5 Tanda Sofa Anda Perlu Dicuci Segera',
                'slug' => '5-tanda-sofa-anda-perlu-dicuci-segera',
                'konten' => '<p>Sofa yang terlihat bersih belum tentu bebas dari kuman. Kenali 5 tanda ini untuk menjaga kesehatan keluarga Anda:</p><ol><li>Warna sofa mulai terlihat kusam atau pudar.</li><li>Timbul bau tidak sedap atau bau apek saat diduduki.</li><li>Anggota keluarga mulai sering bersin atau gatal-gatal saat berada di sofa.</li><li>Ada noda bercak cairan atau makanan yang sudah mengering.</li><li>Sofa belum pernah dibersihkan secara mendalam selama lebih dari 6 bulan.</li></ol>',
                'excerpt' => 'Sofa yang terlihat bersih belum tentu bebas dari kuman. Kenali 5 tanda ini untuk menjaga kesehatan keluarga Anda.',
                'gambar_utama' => 'blog-sofa.jpg',
                'meta_title' => 'Kapan Harus Cuci Sofa? Ini 5 Tanda Utama',
                'meta_description' => 'Jangan tunggu sampai gatal! Ini dia 5 tanda sofa Anda sudah menimbun debu dan tungau serta harus dicuci segera.',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2,
                'judul' => 'Cara Merawat AC Agar Tetap Dingin dan Hemat Listrik',
                'slug' => 'cara-merawat-ac-agar-tetap-dingin-dan-hemat-listrik',
                'konten' => '<p>AC yang terawat bisa menghemat listrik hingga 30%. Simak tips perawatan AC yang benar di sini:</p><ul><li>Bersihkan filter AC setiap 2 minggu sekali secara mandiri.</li><li>Atur suhu AC ideal pada kisaran 22-24 derajat Celcius.</li><li>Lakukan cuci AC profesional setiap 3-4 bulan sekali untuk membersihkan evaporator luar dan dalam.</li><li>Hindari menyalakan AC di ruangan dengan pintu/jendela terbuka.</li></ul>',
                'excerpt' => 'AC yang terawat bisa menghemat listrik hingga 30%. Simak tips perawatan AC yang benar di sini.',
                'gambar_utama' => 'blog-ac.jpg',
                'meta_title' => 'Cara Merawat AC Agar Dingin & Hemat Listrik',
                'meta_description' => 'Tips sederhana merawat pendingin ruangan (AC) di rumah Anda agar hemat listrik dan awet tahan lama.',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2,
                'judul' => 'Perbedaan General Cleaning dan Deep Cleaning',
                'slug' => 'perbedaan-general-cleaning-dan-deep-cleaning',
                'konten' => '<p>Bingung pilih general cleaning atau deep cleaning? Pahami perbedaannya agar Anda bisa memilih yang tepat.</p><p><b>General Cleaning:</b> Pembersihan rutin standar seperti menyapu, mengepel, mengelap kaca jendela, merapikan kasur, dan membersihkan kamar mandi secara umum. Dilakukan secara mingguan.</p><p><b>Deep Cleaning:</b> Pembersihan ekstra mendalam meliputi pembersihan kerak keramik kamar mandi, pembersihan belakang lemari, pembersihan sela-sela kusen jendela, pencucian kulkas bagian dalam, dan pembersihan kerak kompor dapur secara mendetail. Dilakukan 1-2 kali setahun.</p>',
                'excerpt' => 'Bingung pilih general cleaning atau deep cleaning? Pahami perbedaannya agar Anda bisa memilih yang tepat.',
                'gambar_utama' => 'blog-cleaning.jpg',
                'meta_title' => 'Pilih General Cleaning vs Deep Cleaning?',
                'meta_description' => 'Ketahui perbedaan mendasar antara layanan general cleaning harian/mingguan dengan deep cleaning mendalam.',
                'status' => 'published',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 9. Seed Orders & Items & Assignments
        DB::table('orders')->insert([
            [
                'id' => 1,
                'order_number' => 'PHC-20260726-001',
                'customer_id' => 1,
                'tanggal_order' => '2026-07-26',
                'tanggal_jadwal' => '2026-07-27 09:00:00',
                'alamat_pengerjaan' => 'Jl. HR. Soebrantas, Perumahan Melati Indah Blok C No. 12',
                'latitude' => 0.46820000,
                'longitude' => 101.37890000,
                'status' => 'confirmed',
                'total_harga' => 550000.00,
                'diskon' => 0.00,
                'grand_total' => 550000.00,
                'metode_bayar' => 'transfer',
                'status_bayar' => 'paid',
                'catatan' => 'Minta disiram pewangi lavender.',
                'created_by' => 3, // staff
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('order_items')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'service_id' => 1, // General Cleaning
                'qty' => 1,
                'satuan' => 'sesi',
                'harga_satuan' => 350000.00,
                'subtotal' => 350000.00,
                'catatan' => null
            ],
            [
                'id' => 2,
                'order_id' => 1,
                'service_id' => 4, // Cuci Sofa
                'qty' => 1,
                'satuan' => 'unit',
                'harga_satuan' => 200000.00,
                'subtotal' => 200000.00,
                'catatan' => 'Sofa kain warna krem.'
            ]
        ]);

        DB::table('order_assignments')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'user_id' => 4, // Cleaner Rian
                'status' => 'assigned',
                'started_at' => null,
                'finished_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 10. Seed Menus
        DB::table('menus')->insert([
            // Header Menus
            [
                'id' => 1,
                'nama' => 'Layanan',
                'icon' => 'ri-sparkling-line',
                'url' => '/#layanan',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'header',
                'urutan' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Tentang',
                'icon' => 'ri-information-line',
                'url' => '/#tentang',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'header',
                'urutan' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'Blog',
                'icon' => 'ri-article-line',
                'url' => '/blog',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'header',
                'urutan' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'Kontak',
                'icon' => 'ri-contacts-book-line',
                'url' => '/#kontak',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'header',
                'urutan' => 4,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Footer Menus
            [
                'id' => 5,
                'nama' => 'Layanan Kami',
                'icon' => null,
                'url' => '/#layanan',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'footer',
                'urutan' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'nama' => 'Tentang Kami',
                'icon' => null,
                'url' => '/#tentang',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'footer',
                'urutan' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'nama' => 'Blog / Tips',
                'icon' => null,
                'url' => '/blog',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'footer',
                'urutan' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'nama' => 'Kontak',
                'icon' => null,
                'url' => '/#kontak',
                'target' => '_self',
                'parent_id' => null,
                'posisi' => 'footer',
                'urutan' => 4,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
