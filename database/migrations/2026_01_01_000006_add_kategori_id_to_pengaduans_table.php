<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            // Siswa sekarang cukup memilih KATEGORI sarana (Elektronik, Alat Olahraga, dll),
            // bukan barang spesifik. sarana_id tetap ada untuk data lama & kebutuhan admin.
            $table->foreignId('kategori_id')->nullable()->after('sarana_id')->constrained('kategoris')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });
    }
};
