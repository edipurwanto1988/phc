<?php

use App\Models\Bahasa;
use App\Services\ImageService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('migrate:ci3-kategori {--group=6 : CI3 id_grup yang akan dimigrasikan; gunakan "all" untuk semua grup} {--database= : Override nama database sumber CI3}', function () {
    $group = (string) $this->option('group');
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceConnection = DB::connection('ci3_mysql');
    $sourceColumns = $sourceConnection->getSchemaBuilder()->getColumnListing('kategori');
    $selectColumns = ['id_kategori', 'nama_kategori', 'kategori_seo', 'aktif', 'id_grup'];
    $hasEnglishName = in_array('nama_kategori_english', $sourceColumns, true);

    if ($hasEnglishName) {
        $selectColumns[] = 'nama_kategori_english';
    }

    $query = $sourceConnection
        ->table('kategori')
        ->select($selectColumns)
        ->orderBy('id_kategori');

    if (strtolower($group) !== 'all') {
        $query->where('id_grup', $group);
    }

    $sourceKategoris = $query->get();

    if ($sourceKategoris->isEmpty()) {
        $this->warn('Tidak ada kategori CI3 yang ditemukan untuk group: '.$group);
        return self::SUCCESS;
    }

    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();
    $englishBahasa = $hasEnglishName ? Bahasa::where('kode', 'en')->first() : null;

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    DB::table('kategori_translations')->delete();
    DB::table('kategori')->delete();
    DB::statement('ALTER TABLE kategori AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE kategori_translations AUTO_INCREMENT = 1');

    DB::transaction(function () use ($sourceKategoris, $defaultBahasa, $englishBahasa) {
        $usedSlugs = [];
        $now = now();

        foreach ($sourceKategoris as $index => $source) {
            $name = trim((string) $source->nama_kategori);
            $baseSlug = trim((string) $source->kategori_seo) ?: Str::slug($name);
            $baseSlug = Str::slug($baseSlug) ?: 'kategori';
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[$slug] = true;

            $kategoriId = DB::table('kategori')->insertGetId([
                'nama_kategori' => $name,
                'slug' => $slug,
                'deskripsi' => null,
                'urutan' => $index + 1,
                'status' => strtoupper((string) $source->aktif) === 'Y' ? 'active' : 'inactive',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kategori_translations')->insert([
                'kategori_id' => $kategoriId,
                'bahasa_id' => $defaultBahasa->id,
                'nama_kategori' => $name,
                'deskripsi' => null,
            ]);

            if ($englishBahasa && trim((string) ($source->nama_kategori_english ?? '')) !== '') {
                DB::table('kategori_translations')->insert([
                    'kategori_id' => $kategoriId,
                    'bahasa_id' => $englishBahasa->id,
                    'nama_kategori' => trim((string) $source->nama_kategori_english),
                    'deskripsi' => null,
                ]);
            }
        }
    });

    $this->info('Migrasi kategori selesai: '.$sourceKategoris->count().' kategori dipindahkan dari CI3 ke Laravel.');
})->purpose('Migrasi kategori berita dari database CI3 ke Laravel');

Artisan::command('migrate:ci3-berita {--group=6 : CI3 id_grup yang akan dimigrasikan; gunakan "all" untuk semua grup} {--database= : Override nama database sumber CI3}', function () {
    $group = (string) $this->option('group');
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceConnection = DB::connection('ci3_mysql');
    $sourceColumns = $sourceConnection->getSchemaBuilder()->getColumnListing('berita');
    $selectColumns = [
        'id_berita',
        'id_kategori',
        'judul',
        'judul_seo',
        'isi_berita',
        'tanggal',
        'jam',
        'gambar',
        'dibaca',
        'id_grup',
    ];

    $hasEnglishTitle = in_array('judul_english', $sourceColumns, true);
    $hasEnglishBody = in_array('isi_berita_english', $sourceColumns, true);

    if ($hasEnglishTitle) {
        $selectColumns[] = 'judul_english';
    }

    if ($hasEnglishBody) {
        $selectColumns[] = 'isi_berita_english';
    }

    $query = $sourceConnection
        ->table('berita')
        ->select($selectColumns)
        ->orderBy('id_berita');

    if (strtolower($group) !== 'all') {
        $query->where('id_grup', $group);
    }

    $sourceBeritas = $query->get();

    if ($sourceBeritas->isEmpty()) {
        $this->warn('Tidak ada berita CI3 yang ditemukan untuk group: '.$group);
        return self::SUCCESS;
    }

    $sourceKategoriSlugs = $sourceConnection
        ->table('kategori')
        ->pluck('kategori_seo', 'id_kategori');

    $targetKategoriIds = DB::table('kategori')->pluck('id', 'slug');
    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();
    $englishBahasa = ($hasEnglishTitle || $hasEnglishBody) ? Bahasa::where('kode', 'en')->first() : null;

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    DB::table('berita_translations')->delete();
    DB::table('berita')->delete();
    DB::statement('ALTER TABLE berita AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE berita_translations AUTO_INCREMENT = 1');

    $disk = Storage::disk('public');
    $disk->deleteDirectory('uploads/berita');
    $disk->makeDirectory('uploads/berita');

    $sourceImageDirectory = base_path('../ci3/asset/foto_berita');
    $imageService = app(ImageService::class);
    $usedSlugs = [];
    $missingImages = 0;
    $copiedImages = 0;
    $createdThumbnails = 0;

    DB::transaction(function () use (
        $sourceBeritas,
        $sourceKategoriSlugs,
        $targetKategoriIds,
        $defaultBahasa,
        $englishBahasa,
        $sourceImageDirectory,
        $imageService,
        &$usedSlugs,
        &$missingImages,
        &$copiedImages,
        &$createdThumbnails
    ) {
        foreach ($sourceBeritas as $source) {
            $title = trim((string) $source->judul);
            $body = (string) $source->isi_berita;
            $baseSlug = trim((string) $source->judul_seo) ?: Str::slug($title);
            $baseSlug = Str::limit(Str::slug($baseSlug) ?: 'berita', 240, '');
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $suffixText = '-'.$suffix;
                $slug = Str::limit($baseSlug, 255 - strlen($suffixText), '').$suffixText;
                $suffix++;
            }

            $usedSlugs[$slug] = true;

            $featuredImage = null;
            $featuredImageThumbnail = null;
            $sourceFilename = trim((string) $source->gambar);

            if ($sourceFilename !== '') {
                $sourcePath = $sourceImageDirectory.'/'.$sourceFilename;

                if (File::isFile($sourcePath)) {
                    $targetFilename = ci3BeritaMigrationFilename($source->id_berita, $sourceFilename);
                    $featuredImage = 'uploads/berita/'.$targetFilename;
                    Storage::disk('public')->put($featuredImage, File::get($sourcePath));
                    $copiedImages++;

                    $featuredImageThumbnail = $imageService->generateThumbnail($featuredImage, 100, 100);
                    if ($featuredImageThumbnail !== $featuredImage) {
                        $createdThumbnails++;
                    }
                } else {
                    $missingImages++;
                }
            }

            $sourceKategoriSlug = $sourceKategoriSlugs[$source->id_kategori] ?? null;
            $kategoriId = $sourceKategoriSlug ? ($targetKategoriIds[$sourceKategoriSlug] ?? null) : null;
            $publishedAt = Carbon::parse(trim($source->tanggal.' '.$source->jam));
            $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($body))), 220, '');

            $beritaId = DB::table('berita')->insertGetId([
                'judul' => $title,
                'slug' => $slug,
                'tanggal' => $source->tanggal,
                'isi' => $body,
                'excerpt' => $excerpt !== '' ? $excerpt : null,
                'kategori_id' => $kategoriId,
                'featured_image' => $featuredImage,
                'featured_image_thumbnail' => $featuredImageThumbnail,
                'status' => 'published',
                'viewed_count' => (int) $source->dibaca,
                'created_at' => $publishedAt,
                'updated_at' => $publishedAt,
            ]);

            DB::table('berita_translations')->insert([
                'berita_id' => $beritaId,
                'bahasa_id' => $defaultBahasa->id,
                'judul' => $title,
                'isi' => $body,
                'excerpt' => $excerpt !== '' ? $excerpt : null,
            ]);

            $englishTitle = trim((string) ($source->judul_english ?? ''));
            $englishBody = (string) ($source->isi_berita_english ?? '');

            if ($englishBahasa && ($englishTitle !== '' || trim(strip_tags($englishBody)) !== '')) {
                $translationBody = $englishBody !== '' ? $englishBody : $body;
                $translationExcerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($translationBody))), 220, '');

                DB::table('berita_translations')->insert([
                    'berita_id' => $beritaId,
                    'bahasa_id' => $englishBahasa->id,
                    'judul' => $englishTitle !== '' ? $englishTitle : $title,
                    'isi' => $translationBody,
                    'excerpt' => $translationExcerpt !== '' ? $translationExcerpt : null,
                ]);
            }
        }
    });

    $this->info('Migrasi berita selesai: '.$sourceBeritas->count().' berita dipindahkan dari CI3 ke Laravel.');
    $this->line('Gambar disalin: '.$copiedImages);
    $this->line('Thumbnail dibuat: '.$createdThumbnails);
    $this->line('Gambar tidak ditemukan: '.$missingImages);
})->purpose('Migrasi berita dari database CI3 ke Laravel');

Artisan::command('migrate:ci3-halaman {--group=6 : CI3 id_grup yang akan dimigrasikan; gunakan "all" untuk semua grup} {--database= : Override nama database sumber CI3}', function () {
    $group = (string) $this->option('group');
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceConnection = DB::connection('ci3_mysql');
    $sourceColumns = $sourceConnection->getSchemaBuilder()->getColumnListing('halamanstatis');
    $selectColumns = ['id_halaman', 'judul', 'isi_halaman', 'tgl_posting', 'gambar', 'id_grup'];
    $hasEnglishTitle = in_array('judul_english', $sourceColumns, true);
    $hasEnglishBody = in_array('isi_halaman_english', $sourceColumns, true);

    if ($hasEnglishTitle) {
        $selectColumns[] = 'judul_english';
    }

    if ($hasEnglishBody) {
        $selectColumns[] = 'isi_halaman_english';
    }

    $query = $sourceConnection
        ->table('halamanstatis')
        ->select($selectColumns)
        ->orderBy('id_halaman');

    if (strtolower($group) !== 'all') {
        $query->where('id_grup', $group);
    }

    $sourceHalamans = $query->get();

    if ($sourceHalamans->isEmpty()) {
        $this->warn('Tidak ada halaman CI3 yang ditemukan untuk group: '.$group);
        return self::SUCCESS;
    }

    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();
    $englishBahasa = ($hasEnglishTitle || $hasEnglishBody) ? Bahasa::where('kode', 'en')->first() : null;

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    DB::table('halaman_translations')->delete();
    DB::table('halaman')->delete();
    DB::statement('ALTER TABLE halaman AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE halaman_translations AUTO_INCREMENT = 1');

    $disk = Storage::disk('public');
    $disk->deleteDirectory('uploads/halaman');
    $disk->makeDirectory('uploads/halaman');

    $sourceImageDirectory = base_path('../ci3/asset/foto_banner');
    $imageService = app(ImageService::class);
    $usedSlugs = [];
    $englishTranslations = 0;
    $withGambar = 0;
    $copiedImages = 0;
    $createdThumbnails = 0;
    $missingImages = 0;

    DB::transaction(function () use (
        $sourceHalamans,
        $defaultBahasa,
        $englishBahasa,
        $sourceImageDirectory,
        $imageService,
        &$usedSlugs,
        &$englishTranslations,
        &$withGambar,
        &$copiedImages,
        &$createdThumbnails,
        &$missingImages
    ) {
        foreach ($sourceHalamans as $source) {
            $title = trim((string) $source->judul);
            $body = (string) $source->isi_halaman;
            $baseSlug = Str::slug($title) ?: 'halaman-'.$source->id_halaman;
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[$slug] = true;

            if (trim((string) $source->gambar) !== '') {
                $withGambar++;
            }

            $featuredImage = null;
            $featuredImageThumbnail = null;
            $sourceFilename = trim((string) $source->gambar);

            if ($sourceFilename !== '') {
                $sourcePath = $sourceImageDirectory.'/'.$sourceFilename;

                if (File::isFile($sourcePath)) {
                    $targetFilename = ci3HalamanMigrationFilename($source->id_halaman, $sourceFilename);
                    $featuredImage = 'uploads/halaman/'.$targetFilename;
                    Storage::disk('public')->put($featuredImage, File::get($sourcePath));
                    $copiedImages++;

                    $featuredImageThumbnail = $imageService->generateThumbnail($featuredImage, 100, 100);
                    if ($featuredImageThumbnail !== $featuredImage) {
                        $createdThumbnails++;
                    }
                } else {
                    $missingImages++;
                }
            }

            $postedAt = Carbon::parse($source->tgl_posting ?: now());
            $halamanId = DB::table('halaman')->insertGetId([
                'judul' => $title,
                'slug' => $slug,
                'isi' => $body,
                'featured_image' => $featuredImage,
                'featured_image_thumbnail' => $featuredImageThumbnail,
                'status' => 'published',
                'created_at' => $postedAt,
                'updated_at' => $postedAt,
            ]);

            DB::table('halaman_translations')->insert([
                'halaman_id' => $halamanId,
                'bahasa_id' => $defaultBahasa->id,
                'judul' => $title,
                'isi' => $body,
            ]);

            $englishTitle = trim((string) ($source->judul_english ?? ''));
            $englishBody = (string) ($source->isi_halaman_english ?? '');

            if ($englishBahasa && ($englishTitle !== '' || trim(strip_tags($englishBody)) !== '')) {
                DB::table('halaman_translations')->insert([
                    'halaman_id' => $halamanId,
                    'bahasa_id' => $englishBahasa->id,
                    'judul' => $englishTitle !== '' ? $englishTitle : $title,
                    'isi' => $englishBody !== '' ? $englishBody : $body,
                ]);
                $englishTranslations++;
            }
        }
    });

    $this->info('Migrasi halaman selesai: '.$sourceHalamans->count().' halaman dipindahkan dari CI3 ke Laravel.');
    $this->line('Translation English dibuat: '.$englishTranslations);
    $this->line('Halaman CI3 dengan kolom gambar terisi: '.$withGambar);
    $this->line('Gambar disalin: '.$copiedImages);
    $this->line('Thumbnail dibuat: '.$createdThumbnails);
    $this->line('Gambar tidak ditemukan: '.$missingImages);
})->purpose('Migrasi halaman statis dari database CI3 ke Laravel');

Artisan::command('migrate:ci3-dosen {--group=Dosen : Nilai kolom grup pada tabel kepegawaian CI3} {--database= : Override nama database sumber CI3}', function () {
    $group = (string) $this->option('group');
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceDosens = DB::connection('ci3_mysql')
        ->table('kepegawaian')
        ->where('grup', $group)
        ->orderByRaw('urutan IS NULL, urutan ASC')
        ->orderBy('nama_pegawai')
        ->get();

    if ($sourceDosens->isEmpty()) {
        $this->warn('Tidak ada dosen CI3 yang ditemukan untuk grup: '.$group);
        return self::SUCCESS;
    }

    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    $prodiIds = DB::table('prodi')->pluck('id', 'slug');
    $sourceImageDirectory = base_path('../ci3/asset/pegawai');
    $imageService = app(ImageService::class);

    DB::table('penelitian_dosen_translations')->delete();
    DB::table('pengabdian_dosen_translations')->delete();
    DB::table('dosen_translations')->delete();
    DB::table('pendidikan_dosen')->delete();
    DB::table('penelitian_dosen')->delete();
    DB::table('pengabdian_dosen')->delete();
    DB::table('dosen')->delete();
    DB::statement('ALTER TABLE dosen AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE dosen_translations AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE pendidikan_dosen AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE penelitian_dosen AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE pengabdian_dosen AUTO_INCREMENT = 1');

    $disk = Storage::disk('public');
    $disk->deleteDirectory('uploads/dosen');
    $disk->makeDirectory('uploads/dosen');

    $usedSlugs = [];
    $copiedImages = 0;
    $createdThumbnails = 0;
    $missingImages = 0;

    DB::transaction(function () use (
        $sourceDosens,
        $defaultBahasa,
        $prodiIds,
        $sourceImageDirectory,
        $imageService,
        &$usedSlugs,
        &$copiedImages,
        &$createdThumbnails,
        &$missingImages
    ) {
        foreach ($sourceDosens as $index => $source) {
            $name = trim((string) $source->nama_pegawai);
            $baseSlug = Str::slug($name) ?: 'dosen-'.$source->id_kepegawaian;
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[$slug] = true;

            [$typeDosen, $kodeDosen] = ci3DosenIdentifier($source);
            $nik = ci3CleanDosenValue($source->nik);
            if ($nik === null) {
                $nik = 'ci3-'.$source->id_kepegawaian;
            }

            $prodiId = match (strtoupper(trim((string) $source->dosen))) {
                'TI' => $prodiIds['teknik-informatika'] ?? null,
                'SI' => $prodiIds['sistem-informasi'] ?? null,
                'BISDI' => $prodiIds['bisnis-digital'] ?? null,
                default => null,
            };

            $foto = null;
            $fotoThumbnail = null;
            $sourceFilename = trim((string) $source->gambar);

            if ($sourceFilename !== '') {
                $sourcePath = $sourceImageDirectory.'/'.$sourceFilename;

                if (File::isFile($sourcePath)) {
                    $targetFilename = ci3DosenMigrationFilename($source->id_kepegawaian, $sourceFilename);
                    $foto = 'uploads/dosen/'.$targetFilename;
                    Storage::disk('public')->put($foto, File::get($sourcePath));
                    $copiedImages++;

                    $fotoThumbnail = $imageService->generateThumbnail($foto, 128, 192);
                    if ($fotoThumbnail !== $foto) {
                        $createdThumbnails++;
                    }
                } else {
                    $missingImages++;
                }
            }

            $now = now();
            $dosenId = DB::table('dosen')->insertGetId([
                'type_dosen' => $typeDosen,
                'kode_dosen' => $kodeDosen,
                'nik' => $nik,
                'nama' => $name,
                'slug' => $slug,
                'prodi_id' => $prodiId,
                'jabatan' => 'Dosen',
                'deskripsi' => $source->riwayat,
                'email' => ci3CleanDosenValue($source->email),
                'phone' => null,
                'foto' => $foto,
                'foto_thumbnail' => $fotoThumbnail,
                'linkedin' => null,
                'google_scholar' => ci3CleanDosenValue($source->id_schoolar),
                'status' => (int) $source->is_active === 1 ? 'active' : 'inactive',
                'urut' => $source->urutan ?? ($index + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('dosen_translations')->insert([
                'dosen_id' => $dosenId,
                'bahasa_id' => $defaultBahasa->id,
                'deskripsi' => $source->riwayat,
            ]);
        }
    });

    $this->info('Migrasi dosen selesai: '.$sourceDosens->count().' dosen dipindahkan dari CI3 ke Laravel.');
    $this->line('Gambar disalin: '.$copiedImages);
    $this->line('Thumbnail dibuat: '.$createdThumbnails);
    $this->line('Gambar tidak ditemukan: '.$missingImages);
})->purpose('Migrasi dosen dari tabel kepegawaian CI3 ke Laravel');

Artisan::command('migrate:ci3-penelitian {--database= : Override nama database sumber CI3}', function () {
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceConnection = DB::connection('ci3_mysql');

    if (! $sourceConnection->getSchemaBuilder()->hasTable('google_schoolar')) {
        $this->error('Tabel google_schoolar tidak ditemukan di database CI3.');
        return self::FAILURE;
    }

    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    $dosenByScholar = [];
    $duplicateScholarIds = 0;

    DB::table('dosen')
        ->select('id', 'google_scholar')
        ->whereNotNull('google_scholar')
        ->orderBy('id')
        ->get()
        ->each(function ($dosen) use (&$dosenByScholar, &$duplicateScholarIds) {
            $scholarId = ci3CleanDosenValue($dosen->google_scholar);

            if ($scholarId === null) {
                return;
            }

            if (isset($dosenByScholar[$scholarId])) {
                $duplicateScholarIds++;
                return;
            }

            $dosenByScholar[$scholarId] = $dosen->id;
        });

    if ($dosenByScholar === []) {
        $this->warn('Tidak ada dosen Laravel dengan google_scholar. Jalankan migrate:ci3-dosen lebih dulu.');
        return self::SUCCESS;
    }

    $sourcePublications = $sourceConnection
        ->table('google_schoolar')
        ->select('id', 'id_schoolar', 'title', 'authors', 'venue', 'citations', 'year')
        ->whereIn('id_schoolar', array_keys($dosenByScholar))
        ->orderBy('id_schoolar')
        ->orderByDesc('year')
        ->orderBy('id')
        ->get();

    if ($sourcePublications->isEmpty()) {
        $this->warn('Tidak ada publikasi google_schoolar yang cocok dengan data dosen Laravel.');
        return self::SUCCESS;
    }

    DB::table('penelitian_dosen_translations')->delete();
    DB::table('penelitian_dosen')->delete();
    DB::statement('ALTER TABLE penelitian_dosen AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE penelitian_dosen_translations AUTO_INCREMENT = 1');

    $now = now();
    $inserted = 0;
    $skippedEmptyTitles = 0;

    DB::transaction(function () use (
        $sourcePublications,
        $dosenByScholar,
        $defaultBahasa,
        $now,
        &$inserted,
        &$skippedEmptyTitles
    ) {
        foreach ($sourcePublications as $publication) {
            $title = ci3CleanDosenValue($publication->title);

            if ($title === null) {
                $skippedEmptyTitles++;
                continue;
            }

            $scholarId = ci3CleanDosenValue($publication->id_schoolar);
            $dosenId = $scholarId !== null ? ($dosenByScholar[$scholarId] ?? null) : null;

            if (! $dosenId) {
                continue;
            }

            $descriptionParts = [];
            $authors = ci3CleanDosenValue($publication->authors);
            $venue = ci3CleanDosenValue($publication->venue);

            if ($authors !== null) {
                $descriptionParts[] = 'Penulis: '.$authors;
            }

            if ($venue !== null) {
                $descriptionParts[] = 'Publikasi: '.$venue;
            }

            $description = $descriptionParts === [] ? null : implode("\n", $descriptionParts);
            $year = is_numeric($publication->year) ? (int) $publication->year : null;
            $citations = is_numeric($publication->citations) ? (string) ((int) $publication->citations) : ci3CleanDosenValue($publication->citations);

            $penelitianId = DB::table('penelitian_dosen')->insertGetId([
                'dosen_id' => $dosenId,
                'judul' => $title,
                'deskripsi' => $description,
                'link' => null,
                'tahun' => $year,
                'citations' => $citations,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('penelitian_dosen_translations')->insert([
                'penelitian_id' => $penelitianId,
                'bahasa_id' => $defaultBahasa->id,
                'judul' => $title,
                'deskripsi' => $description,
            ]);

            $inserted++;
        }
    });

    $unmatchedPublications = $sourceConnection
        ->table('google_schoolar')
        ->whereNotIn('id_schoolar', array_keys($dosenByScholar))
        ->count();

    $this->info('Migrasi penelitian selesai: '.$inserted.' publikasi dipindahkan dari google_schoolar ke penelitian_dosen.');
    $this->line('Publikasi dilewati karena judul kosong: '.$skippedEmptyTitles);
    $this->line('Publikasi tanpa dosen cocok: '.$unmatchedPublications);
    $this->line('Duplikat Google Scholar ID pada dosen Laravel: '.$duplicateScholarIds);
})->purpose('Migrasi penelitian dosen dari tabel google_schoolar CI3 ke Laravel');

Artisan::command('migrate:ci3-tendik {--group=Tenaga Kependidikan : Nilai kolom grup pada tabel kepegawaian CI3} {--database= : Override nama database sumber CI3}', function () {
    $group = (string) $this->option('group');
    $database = $this->option('database');

    if ($database) {
        config(['database.connections.ci3_mysql.database' => $database]);
        DB::purge('ci3_mysql');
    }

    $sourceConnection = DB::connection('ci3_mysql');
    $sourceTendiks = $sourceConnection
        ->table('kepegawaian')
        ->where('grup', $group)
        ->orderByRaw('urutan IS NULL, urutan ASC')
        ->orderBy('nama_pegawai')
        ->get();

    if ($sourceTendiks->isEmpty() && $group === 'Tenaga Kependidikan') {
        $fallbackGroup = 'Tenaga Pendidik';
        $sourceTendiks = $sourceConnection
            ->table('kepegawaian')
            ->where('grup', $fallbackGroup)
            ->orderByRaw('urutan IS NULL, urutan ASC')
            ->orderBy('nama_pegawai')
            ->get();

        if ($sourceTendiks->isNotEmpty()) {
            $this->warn('Grup "Tenaga Kependidikan" tidak ditemukan. Menggunakan grup CI3 "'.$fallbackGroup.'".');
            $group = $fallbackGroup;
        }
    }

    if ($sourceTendiks->isEmpty()) {
        $this->warn('Tidak ada tendik CI3 yang ditemukan untuk grup: '.$group);
        return self::SUCCESS;
    }

    $defaultBahasa = Bahasa::where('is_default', 'yes')->first()
        ?: Bahasa::where('kode', 'id')->first()
        ?: Bahasa::first();

    if (! $defaultBahasa) {
        $this->error('Tabel bahasa Laravel masih kosong. Isi bahasa default lebih dulu.');
        return self::FAILURE;
    }

    DB::table('tendik_translations')->delete();
    DB::table('tendik')->delete();
    DB::statement('ALTER TABLE tendik AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE tendik_translations AUTO_INCREMENT = 1');

    $disk = Storage::disk('public');
    $disk->deleteDirectory('uploads/tendik');
    $disk->makeDirectory('uploads/tendik');

    $sourceImageDirectory = base_path('../ci3/asset/pegawai');
    $imageService = app(ImageService::class);
    $usedSlugs = [];
    $copiedImages = 0;
    $createdThumbnails = 0;
    $missingImages = 0;

    DB::transaction(function () use (
        $sourceTendiks,
        $defaultBahasa,
        $sourceImageDirectory,
        $imageService,
        &$usedSlugs,
        &$copiedImages,
        &$createdThumbnails,
        &$missingImages
    ) {
        foreach ($sourceTendiks as $source) {
            $name = trim((string) $source->nama_pegawai);
            $baseSlug = Str::slug($name) ?: 'tendik-'.$source->id_kepegawaian;
            $slug = $baseSlug;
            $suffix = 2;

            while (isset($usedSlugs[$slug])) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[$slug] = true;

            $nik = ci3CleanDosenValue($source->nik);
            if ($nik === null) {
                $nik = 'ci3-'.$source->id_kepegawaian;
            }

            $foto = null;
            $fotoThumbnail = null;
            $sourceFilename = trim((string) $source->gambar);

            if ($sourceFilename !== '') {
                $sourcePath = $sourceImageDirectory.'/'.$sourceFilename;

                if (File::isFile($sourcePath)) {
                    $targetFilename = ci3TendikMigrationFilename($source->id_kepegawaian, $sourceFilename);
                    $foto = 'uploads/tendik/'.$targetFilename;
                    Storage::disk('public')->put($foto, File::get($sourcePath));
                    $copiedImages++;

                    $fotoThumbnail = $imageService->generateThumbnail($foto, 128, 192);
                    if ($fotoThumbnail !== $foto) {
                        $createdThumbnails++;
                    }
                } else {
                    $missingImages++;
                }
            }

            $now = now();
            $tendikId = DB::table('tendik')->insertGetId([
                'nik' => $nik,
                'nama' => $name,
                'slug' => $slug,
                'jabatan' => ci3CleanDosenValue($source->staff) ?: 'Staff',
                'prodi_id' => null,
                'deskripsi' => $source->riwayat,
                'email' => ci3CleanDosenValue($source->email),
                'phone' => null,
                'foto' => $foto,
                'foto_thumbnail' => $fotoThumbnail,
                'status' => (int) $source->is_active === 1 ? 'active' : 'inactive',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('tendik_translations')->insert([
                'tendik_id' => $tendikId,
                'bahasa_id' => $defaultBahasa->id,
                'deskripsi' => $source->riwayat,
            ]);
        }
    });

    $this->info('Migrasi tendik selesai: '.$sourceTendiks->count().' tendik dipindahkan dari CI3 ke Laravel.');
    $this->line('Grup sumber CI3: '.$group);
    $this->line('Gambar disalin: '.$copiedImages);
    $this->line('Thumbnail dibuat: '.$createdThumbnails);
    $this->line('Gambar tidak ditemukan: '.$missingImages);
})->purpose('Migrasi tendik dari tabel kepegawaian CI3 ke Laravel');

if (! function_exists('ci3BeritaMigrationFilename')) {
    function ci3BeritaMigrationFilename(int $sourceId, string $sourceFilename): string
    {
        $extension = pathinfo($sourceFilename, PATHINFO_EXTENSION);
        $name = pathinfo($sourceFilename, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'gambar';
        $filename = 'ci3-'.$sourceId.'-'.$slug;

        if ($extension !== '') {
            $filename .= '.'.strtolower($extension);
        }

        return Str::limit($filename, 240, '');
    }
}

if (! function_exists('ci3CleanDosenValue')) {
    function ci3CleanDosenValue(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' || $value === '-' ? null : $value;
    }
}

if (! function_exists('ci3DosenIdentifier')) {
    function ci3DosenIdentifier(object $source): array
    {
        $nuptk = ci3CleanDosenValue($source->nuptk);
        if ($nuptk !== null) {
            return ['nuptk', $nuptk];
        }

        $nip = ci3CleanDosenValue($source->nip);
        if ($nip !== null) {
            return ['nidn', $nip];
        }

        return ['nidn', 'ci3-'.$source->id_kepegawaian];
    }
}

if (! function_exists('ci3DosenMigrationFilename')) {
    function ci3DosenMigrationFilename(int $sourceId, string $sourceFilename): string
    {
        $extension = pathinfo($sourceFilename, PATHINFO_EXTENSION);
        $name = pathinfo($sourceFilename, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'foto';
        $filename = 'ci3-dosen-'.$sourceId.'-'.$slug;

        if ($extension !== '') {
            $filename .= '.'.strtolower($extension);
        }

        return Str::limit($filename, 240, '');
    }
}

if (! function_exists('ci3TendikMigrationFilename')) {
    function ci3TendikMigrationFilename(int $sourceId, string $sourceFilename): string
    {
        $extension = pathinfo($sourceFilename, PATHINFO_EXTENSION);
        $name = pathinfo($sourceFilename, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'foto';
        $filename = 'ci3-tendik-'.$sourceId.'-'.$slug;

        if ($extension !== '') {
            $filename .= '.'.strtolower($extension);
        }

        return Str::limit($filename, 240, '');
    }
}

if (! function_exists('ci3HalamanMigrationFilename')) {
    function ci3HalamanMigrationFilename(int $sourceId, string $sourceFilename): string
    {
        $extension = pathinfo($sourceFilename, PATHINFO_EXTENSION);
        $name = pathinfo($sourceFilename, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'gambar';
        $filename = 'ci3-halaman-'.$sourceId.'-'.$slug;

        if ($extension !== '') {
            $filename .= '.'.strtolower($extension);
        }

        return Str::limit($filename, 240, '');
    }
}
