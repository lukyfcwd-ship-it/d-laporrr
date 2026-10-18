<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Sarana;
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

        $saranaData = [
            ['kategori' => 'ELK', 'kode_sarana' => 'ELK-01', 'nama_sarana' => 'Komputer PC Lab RPL', 'lokasi' => 'Lab RPL Lt.2', 'kondisi' => 'baik'],
            ['kategori' => 'ELK', 'kode_sarana' => 'ELK-02', 'nama_sarana' => 'Proyektor Lab RPL', 'lokasi' => 'Lab RPL Lt.2', 'kondisi' => 'rusak_ringan'],
            ['kategori' => 'ELK', 'kode_sarana' => 'ELK-03', 'nama_sarana' => 'Sound System Aula', 'lokasi' => 'Aula Utama', 'kondisi' => 'baik'],
            ['kategori' => 'OR', 'kode_sarana' => 'OR-01', 'nama_sarana' => 'Ring Basket', 'lokasi' => 'Lapangan Basket', 'kondisi' => 'rusak_berat'],
            ['kategori' => 'FUR', 'kode_sarana' => 'FUR-01', 'nama_sarana' => 'Kursi Kelas XII RPL A', 'lokasi' => 'Ruang XII RPL A', 'kondisi' => 'baik'],
            ['kategori' => 'BGN', 'kode_sarana' => 'BGN-01', 'nama_sarana' => 'Keran Air Toilet Siswa', 'lokasi' => 'Toilet Lt.1', 'kondisi' => 'rusak_ringan'],
        ];

        $saranas = collect($saranaData)->map(function ($s) use ($kategoris) {
            $kategori = $kategoris->firstWhere('kode_kategori', $s['kategori']);

            return Sarana::create([
                'kategori_id' => $kategori->id,
                'kode_sarana' => $s['kode_sarana'],
                'nama_sarana' => $s['nama_sarana'],
                'lokasi' => $s['lokasi'],
                'kondisi' => $s['kondisi'],
            ]);
        });

        // Contoh laporan "lama" — masih menunjuk barang spesifik (sarana_id),
        // sekaligus kategori_id-nya ikut diisi agar konsisten dengan alur baru.
        Pengaduan::create([
            'user_id' => $siswa->id,
            'sarana_id' => $saranas->firstWhere('kode_sarana', 'ELK-02')->id,
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

        // Contoh laporan "baru" — siswa hanya memilih KATEGORI (Bangunan & Sanitasi),
        // tanpa menyebut barang spesifik, sesuai alur form yang sudah disederhanakan.
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
