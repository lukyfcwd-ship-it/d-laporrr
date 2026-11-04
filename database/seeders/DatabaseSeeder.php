<?php

namespace Database\Seeders;

use App\Models\DataSiswa;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Sarana',
            'email' => 'admin@dlaporr.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nis_nip' => '198001012006041001',
        ]);

        $siswa = User::create([
            'name' => 'Andaryoko Wisnu Murti',
            'email' => 'siswa@dlaporr.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'nis_nip' => '0071234567',
            'kelas' => 'XII RPL A',
        ]);

        // Daftar siswa resmi (data contoh). Akun siswa demo di atas sudah terhubung ke NIS-nya;
        // dua siswa lain belum punya akun, jadi bisa dipakai mencoba halaman Buat Akun.
        DataSiswa::create(['nis' => '0071234567', 'nama' => 'Andaryoko Wisnu Murti', 'kelas' => 'XII RPL A', 'user_id' => $siswa->id]);
        DataSiswa::create(['nis' => '0071234501', 'nama' => 'Budi Santoso', 'kelas' => 'XII RPL A']);
        DataSiswa::create(['nis' => '0071234502', 'nama' => 'Siti Aisyah', 'kelas' => 'XII RPL B']);

        // Kategori berdasarkan JENIS barang (bukan berdasarkan ruangan/lokasi),
        // supaya satu kategori bisa berlaku untuk barang sejenis di lokasi manapun.
        $kategoriData = [
            ['nama_kategori' => 'Elektronik', 'kode_kategori' => 'ELK', 'keterangan' => 'Komputer, proyektor, sound system, kipas angin, lampu, dan perangkat listrik lainnya.'],
            ['nama_kategori' => 'Furnitur', 'kode_kategori' => 'FUR', 'keterangan' => 'Meja, kursi, lemari, dan perabot ruangan.'],
            ['nama_kategori' => 'Bangunan & Sanitasi', 'kode_kategori' => 'BGN', 'keterangan' => 'Pintu, jendela, atap, keran air, toilet, dan struktur bangunan.'],
            ['nama_kategori' => 'Alat Olahraga', 'kode_kategori' => 'OR', 'keterangan' => 'Perlengkapan dan alat olahraga di lapangan/aula.'],
            ['nama_kategori' => 'Lainnya', 'kode_kategori' => 'LNY', 'keterangan' => 'Barang/sarana yang tidak termasuk kategori di atas.'],
        ];

        $kategoris = collect($kategoriData)->map(fn ($k) => Kategori::create($k));

        // Contoh laporan: siswa memilih KATEGORI sarana, lalu mengisi lokasi kerusakan secara bebas.
        Pengaduan::create([
            'user_id' => $siswa->id,
            'kategori_id' => $kategoris->firstWhere('kode_kategori', 'ELK')->id,
            'judul' => 'Proyektor Lab RPL tidak menyala',
            'lokasi_kerusakan' => 'LAB RPL',
            'tanggal_kejadian' => now()->subDays(3),
            'deskripsi' => 'Proyektor di Lab RPL Lt.2 tidak bisa menyala sejak Senin pagi, sudah dicoba ganti kabel HDMI tetap tidak muncul gambar.',
            'prioritas' => 'tinggi',
            'status' => 'diproses',
            'diverifikasi_oleh' => $admin->id,
            'tanggal_verifikasi' => now()->subDay(),
            'catatan_admin' => 'Sedang dicek oleh teknisi.',
        ]);

        Pengaduan::create([
            'user_id' => $siswa->id,
            'kategori_id' => $kategoris->firstWhere('kode_kategori', 'BGN')->id,
            'judul' => 'Keran air toilet bocor',
            'lokasi_kerusakan' => 'Toilet Siswa Lt.1',
            'tanggal_kejadian' => now()->subDay(),
            'deskripsi' => 'Keran air di toilet siswa lantai 1 bocor terus menerus dan membuat lantai licin.',
            'prioritas' => 'sedang',
            'status' => 'pending',
        ]);
    }
}
