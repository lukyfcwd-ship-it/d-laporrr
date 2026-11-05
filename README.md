# D-Laporr — Sistem Pengaduan Sarana Sekolah

Project Kelas XII RPL — SMK Negeri 5 Surakarta, Tahun Ajaran 2026/2027
Siswa: Andaryoko Wisnu Murti — XII RPL A / No. Absen 7
Sub-tema: **Sistem Pengaduan Sarana Sekolah** (Helpdesk/Ticketing System)

## Ringkasan Fitur

| Kode | Fitur | Status |
|---|---|---|
| FR-01 | Login/Logout terenkripsi (Bcrypt), profil & ubah password | ✅ |
| FR-02 | Master Data: Pengguna, Kategori Sarana, Sarana | ✅ |
| FR-03 | Siswa mengajukan pengaduan (judul, tanggal, deskripsi, foto) | ✅ |
| FR-04 | Admin verifikasi status: Diproses/Approved/Rejected/Selesai + catatan | ✅ |
| FR-05 | Dashboard statistik + rekap tabel + cetak PDF (per tiket & rekap) | ✅ |
| Non-Fungsional | Role-based access (Admin/Siswa), responsif, validasi input | ✅ |

Desain UI memakai Tailwind CSS (mobile-first, kontras tinggi, font ≥16px,
navigasi sidebar konsisten di semua halaman, semua halaman utama ≤3 klik
dari dashboard) dan Alpine.js untuk interaksi ringan (modal, sidebar mobile)
tanpa perlu build step (build-less, via CDN) agar tetap ringan dan cepat.

## ⚠️ Cara Memasang (WAJIB dibaca)

Kode di paket ini adalah **source code aplikasi (app/, database/, resources/views/,
routes/web.php)**, bukan project Laravel yang lengkap dengan folder `vendor/`.
Kamu perlu membuat project Laravel kosong dulu, baru menimpakan (overlay)
file-file di paket ini. Jalankan di komputer/laptop kamu (butuh PHP ≥8.2,
Composer, dan MySQL):

```bash
# 1. Buat project Laravel baru
composer create-project laravel/laravel d-laporr
cd d-laporr

# 2. Install package PDF
composer require barryvdh/laravel-dompdf

# 3. Salin (overwrite) folder app/, database/, resources/views/, routes/web.php
#    dari paket zip ini ke dalam project Laravel yang baru dibuat.
#    Contoh (Mac/Linux):
cp -r /path/to/paket-zip/app/* ./app/
cp -r /path/to/paket-zip/database/* ./database/
cp -r /path/to/paket-zip/resources/views/* ./resources/views/
cp /path/to/paket-zip/routes/web.php ./routes/web.php

# 4. Daftarkan middleware role (lihat isi file
#    bootstrap/app-middleware-snippet.php di paket ini) ke bootstrap/app.php

# 5. Setup .env: salin .env.example -> .env, isi kredensial database MySQL kamu
cp .env.example .env
php artisan key:generate

# 6. Migrasi & seed database (Migration + Seeder sesuai guideline)
php artisan migrate --seed

# 7. Link storage (agar foto bukti pengaduan bisa tampil)
php artisan storage:link

# 8. Jalankan
php artisan serve
```

Buka `http://127.0.0.1:8000`. Akun demo dari seeder:

- **Admin/Petugas**: admin@dlaporr.sch.id / password
- **Siswa**: siswa@dlaporr.sch.id / password

## Struktur Database (ERD ringkas)

- `users` (role: admin/siswa) — 1 user punya banyak `pengaduans`
- `kategoris` — 1 kategori punya banyak `saranas`
- `saranas` — milik 1 kategori, punya banyak `pengaduans`
- `pengaduans` — milik 1 user (pelapor), opsional milik 1 sarana,
  diverifikasi oleh 1 user (admin), status: pending → diproses →
  approved/rejected → selesai

Kode tiket (`kode_tiket`) di-generate otomatis saat pengaduan dibuat
(`TIK-YYYYMMDD-XXXX`) agar siswa mudah melacak laporannya — ini bagian dari
penyempurnaan logika yang kamu izinkan untuk mempermudah siswa & admin.

## Riwayat Revisi

**v3** — Skema warna baru (Primary Hijau Zamrud #047857 di sidebar/footer, Secondary Hijau Mint
#D1FAE5 di border/hover, Accent Oranye #F97316 di tombol aksi, Background putih bersih), ikon
menyesuaikan otomatis; dropdown "Sarana Terkait" di form pengaduan sekarang dikelompokkan per
Kategori Sarana; teks login diubah jadi "Buat Akun"; kolom Foto & Kelas pelapor ditambahkan di
halaman Data Pengaduan (admin) beserta detailnya; field baru "Lokasi Kerusakan" (teks bebas,
contoh: LAB RPL) di form pengaduan siswa — tampil juga di detail & PDF.

⚠️ Karena ada migration baru (`lokasi_kerusakan`) dan data kategori sudah pernah diubah
sebelumnya, jalankan `php artisan migrate:fresh --seed` lagi setelah update supaya database
sinkron dengan kode terbaru.

**v4** — Warna dikembalikan ke tema biru semula (skema hijau/oranye v3 dibatalkan). Halaman
Data Sarana sekarang menampilkan kolom "Laporan Terakhir" (foto bukti, nama & kelas pelapor)
untuk tiap barang. Form Buat Pengaduan disederhanakan: siswa hanya memilih **Kategori**
(Elektronik, Alat Olahraga, Furnitur, dst.), tidak lagi memilih barang spesifik satu per satu.

⚠️ Ada migration baru (`kategori_id` di tabel pengaduans). Jalankan lagi:
```
php artisan migrate:fresh --seed
```

**Catatan penting soal trade-off poin 3 & 4:** karena sekarang siswa hanya memilih *kategori*
(bukan barang spesifik lagi), untuk laporan-laporan baru sistem tidak tahu persis barang mana
yang rusak — jadi kolom "Laporan Terakhir" di Data Sarana akan menampilkan laporan terbaru
**dalam kategori yang sama**, bukan yang pasti soal barang itu persis. Detail lokasi tepatnya
tetap terekam lewat field "Lokasi Kerusakan" yang diisi bebas oleh siswa (mis. "LAB RPL"), jadi
admin tetap bisa tahu di mana kejadiannya — hanya saja tidak terhubung otomatis ke satu baris
barang tertentu di Data Sarana. Kalau kamu tetap butuh keterkaitan 1-ke-1 ke barang spesifik,
saya bisa kembalikan pilihan barang tapi dengan tampilan yang lebih ringkas (misalnya dua
dropdown berjenjang: pilih Kategori dulu, baru muncul pilihan barang di kategori itu).

**v5** — Redesain visual menyeluruh: sidebar gelap modern (slate-900) dengan menu aktif biru vibrant,
kartu statistik berikon berwarna, kartu/tombol/input dengan sudut lebih halus + bayangan lembut,
fokus input soft-ring, header tabel lebih rapi, alert berikon, halaman login & register bergaya
split-panel. Tidak ada migration baru — cukup timpa file (folder resources/views + komponen icon).

**v6** — Redesain advanced + animasi: font Plus Jakarta Sans, palet indigo–biru–cyan, sidebar bergradasi,
hero dashboard bergradasi bergerak, penghitung angka animasi, donut chart & bar chart animasi,
tracker progres status di detail pengaduan, toast notifikasi, modal dengan transisi, tombol berefek
kilau, tabel muncul bertahap, tampil/sembunyikan kata sandi, loading bar & tombol loading saat submit.
Animasi otomatis dimatikan bagi pengguna yang mengaktifkan "reduce motion" di perangkatnya.
File baru: resources/views/partials/head.blade.php. Model Pengaduan.php juga berubah (gaya badge).
Tidak ada migration baru.

**v7** — Level "advanced": palet aurora (violet-fuchsia-cyan) menggantikan indigo-biru polos, efek
tilt 3D saat kursor di atas kartu, tombol dengan efek ripple saat diklik, animasi muncul kini
berbasis SCROLL (IntersectionObserver) bukan cuma saat halaman dimuat — jadi elemen di bawah layar
tetap animatif saat di-scroll ke bawah, indikator menu aktif di sidebar meluncur halus antar menu,
grafik tren pengaduan 7 hari (area chart animasi) di dashboard admin, border berputar (conic-gradient)
saat fokus di input utama, dan efek confetti kecil saat notifikasi sukses muncul.
Perubahan file: partials/head.blade.php (rombak total), layouts/app.blade.php, dashboard/admin.blade.php,
auth/login.blade.php, auth/register.blade.php, DashboardController.php (query tren 7 hari — tidak
perlu migration baru).

**v7** — Palet diganti jadi "Aurora" (violet–fuchsia–cyan), lebih hidup dari indigo/biru sebelumnya.
Efek baru: spotlight yang mengikuti kursor di sidebar, tilt 3D halus di kartu statistik (nonaktif di
layar sentuh), efek ripple saat tombol utama diklik, judul bercahaya dengan gradien bergerak
(nama pengguna di dashboard, logo di halaman login/register versi mobile), dan fokus input beraura
ungu-magenta. Semua animasi otomatis nonaktif jika perangkat pengguna mengaktifkan "reduce motion".
Tidak ada migration baru — cukup timpa folder resources/views.

**v8** — Revisi balik ke gaya minimalis sesuai masukan: satu warna aksen saja (indigo #4f46e5),
gradasi pelangi/blob mengambang/efek spotlight & tilt 3D dihapus karena dinilai berlebihan.
Grafik "Tren Pengaduan Masuk" diperbaiki — sebelumnya distorsi (garis & titik gepeng aneh) karena
`preserveAspectRatio="none"` meregangkan SVG secara tidak proporsional; sekarang pakai viewBox
lebar yang proporsional sehingga bentuknya presisi. Halaman Buat Akun dirapikan dan diberi tautan
"← Kembali ke Login" yang jelas di bagian atas form. Tidak ada migration baru.

**v9** — (1) Foto bukti yang gagal tampil sekarang otomatis diganti ikon placeholder rapi,
bukan ikon rusak bawaan browser — penyebab aslinya kemungkinan `php artisan storage:link` belum
dijalankan di server, jadi folder symlink `public/storage` belum ada (jalankan perintah itu agar
foto asli muncul). (2) Kartu "Sarana per Kategori" di dashboard admin diganti jadi
**"Laporan Kerusakan per Kategori"** — datanya sekarang dihitung dari Data Pengaduan (laporan siswa),
bukan dari jumlah aset terdaftar di Data Sarana, sesuai permintaan. (3) Animasi interaktif baru:
kartu sedikit terangkat saat di-hover, titik berdenyut di kartu "Menunggu" bila ada laporan yang
butuh perhatian, ikon sidebar sedikit membesar saat hover, baris kategori bereaksi saat disentuh,
dan tombol salin kode tiket dengan animasi centang di halaman detail pengaduan. Tidak ada migration
baru — tapi WAJIB jalankan `php artisan storage:link` jika belum pernah.

**v10** — (1) Kolom "Jumlah Sarana" dihapus dari Kategori Sarana (diganti Keterangan). (2) Menu dan halaman
Data Sarana dihapus dari dashboard admin (tabel `saranas` dan model `Sarana` sengaja dipertahankan agar data
lama tidak rusak). (3) Profil dan Kata Sandi didesain ulang untuk admin dan siswa; siswa dapat memasang,
mengganti, dan menghapus foto profil (nama, NIS, kelas siswa dikunci karena berasal dari data sekolah).
(4) Tema baru "tinta navy + kuning penanda + kertas krem" dengan font Bricolage Grotesque dan Figtree;
halaman Login (papan laporan dengan tiket bergerak) dan Buat Akun (wizard 2 langkah dengan kartu pelapor
yang terisi langsung) dirancang ulang. Foto kini memakai alamat yang mengikuti host yang sedang dibuka,
bukan APP_URL. Paket ini juga sudah memuat perbaikan keamanan (pencarian dan cetak PDF tiket milik siswa lain),
verifikasi NIS saat mendaftar, dan migration admin awal untuk hosting. Tidak ada migration baru di versi ini.

## Rencana Pengembangan Lanjutan (opsional, sesuai timeline minggu 7-8)

- Tambahkan notifikasi email saat status berubah.
- Tambahkan grafik (chart.js) di dashboard admin untuk tren pengaduan per bulan.
- Tulis Laporan Proyek Akhir & User Manual berdasarkan fitur-fitur di atas —
  saya bisa bantu susun dokumennya (Bab I–V + ERD + screenshot) kalau kamu mau,
  tinggal minta.
