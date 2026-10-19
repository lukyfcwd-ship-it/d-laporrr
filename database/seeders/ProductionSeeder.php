<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk server online: TANPA akun demo & TANPA data contoh.
 * Hanya membuat kategori sarana + 1 akun admin dari variabel ADMIN_* di .env / secrets.
 *
 * Jalankan:  php artisan db:seed --class=ProductionSeeder --force
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password || strlen($password) < 10) {
            $this->command?->error('Isi ADMIN_EMAIL dan ADMIN_PASSWORD (minimal 10 karakter) dulu.');

            return;
        }

        $kategoris = [
            ['kode_kategori' => 'ELK', 'nama_kategori' => 'Elektronik', 'keterangan' => 'Komputer, proyektor, sound system, kipas angin, lampu, dan perangkat listrik lainnya.'],
            ['kode_kategori' => 'FUR', 'nama_kategori' => 'Furnitur', 'keterangan' => 'Meja, kursi, lemari, dan perabot ruangan.'],
            ['kode_kategori' => 'BGN', 'nama_kategori' => 'Bangunan & Sanitasi', 'keterangan' => 'Pintu, jendela, atap, keran air, toilet, dan struktur bangunan.'],
            ['kode_kategori' => 'OR', 'nama_kategori' => 'Alat Olahraga', 'keterangan' => 'Perlengkapan dan alat olahraga di lapangan/aula.'],
            ['kode_kategori' => 'LNY', 'nama_kategori' => 'Lainnya', 'keterangan' => 'Barang/sarana yang tidak termasuk kategori di atas.'],
        ];

        foreach ($kategoris as $k) {
            Kategori::firstOrCreate(['kode_kategori' => $k['kode_kategori']], $k);
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make($password),
                'role' => 'admin',
            ]
        );

        $this->command?->info("Selesai. Admin: {$email}");
    }
}
