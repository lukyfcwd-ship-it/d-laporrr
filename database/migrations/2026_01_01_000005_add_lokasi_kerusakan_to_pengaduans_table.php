<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            // Lokasi bebas yang diisi siswa saat lapor (contoh: "LAB RPL"),
            // terpisah dari lokasi baku milik master data Sarana.
            $table->string('lokasi_kerusakan')->nullable()->after('sarana_id');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn('lokasi_kerusakan');
        });
    }
};
