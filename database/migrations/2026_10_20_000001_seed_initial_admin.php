<?php

use Database\Seeders\ProductionSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Untuk hosting tanpa akses terminal (mis. Wasmer): server menjalankan "php artisan migrate"
 * otomatis setiap deploy, jadi akun admin pertama + kategori dibuat lewat migration ini.
 * Hanya jalan SEKALI, dan hanya jika ADMIN_EMAIL & ADMIN_PASSWORD diisi di environment variables.
 * Di komputer lokal (tanpa variabel itu) migration ini tidak melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email && ! $password) {
            return;
        }

        if (! $email || ! $password || strlen($password) < 10) {
            throw new RuntimeException('ADMIN_EMAIL wajib diisi dan ADMIN_PASSWORD minimal 10 karakter.');
        }

        (new ProductionSeeder)->run();
    }

    public function down(): void
    {
        // Sengaja kosong: tidak menghapus akun admin saat rollback.
    }
};
