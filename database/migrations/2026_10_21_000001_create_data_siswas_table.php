<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daftar siswa resmi SMK Negeri 5 Surakarta. Hanya NIS yang ada di tabel ini
        // yang boleh dipakai untuk membuat akun (lihat RegisterController).
        Schema::create('data_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 30)->unique();
            $table->string('nama', 100);
            $table->string('kelas', 30);
            // Terisi saat siswa berhasil membuat akun. Jika akunnya dihapus, kolom ini
            // otomatis kosong lagi sehingga NIS bisa dipakai mendaftar ulang.
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_siswas');
    }
};
