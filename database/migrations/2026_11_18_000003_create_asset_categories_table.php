<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('nama')->constrained('asset_categories')->nullOnDelete();
        });

        // Pindahkan kategori teks yang sudah ada ke tabel master
        $names = DB::table('assets')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        foreach ($names as $name) {
            $now = now();
            $id = DB::table('asset_categories')->insertGetId([
                'nama' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('assets')->where('kategori', $name)->update(['kategori_id' => $id]);
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('nama');
        });

        $categories = DB::table('asset_categories')->get();
        foreach ($categories as $category) {
            DB::table('assets')->where('kategori_id', $category->id)->update(['kategori' => $category->nama]);
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('asset_categories');
    }
};
