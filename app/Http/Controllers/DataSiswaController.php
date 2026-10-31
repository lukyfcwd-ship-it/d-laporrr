<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataSiswaController extends Controller
{
    private const MAKS_BARIS_IMPOR = 3000;

    public function index(Request $request)
    {
        $siswas = DataSiswa::query()
            ->when($request->q, fn ($q) => $q->where(fn ($w) => $w
                ->where('nama', 'like', "%{$request->q}%")
                ->orWhere('nis', 'like', "%{$request->q}%")
                ->orWhere('kelas', 'like', "%{$request->q}%")))
            ->when($request->status === 'terdaftar', fn ($q) => $q->whereNotNull('user_id'))
            ->when($request->status === 'belum', fn ($q) => $q->whereNull('user_id'))
            ->orderBy('kelas')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => DataSiswa::count(),
            'terdaftar' => DataSiswa::whereNotNull('user_id')->count(),
        ];
        $stats['belum'] = $stats['total'] - $stats['terdaftar'];

        return view('data-siswa.index', compact('siswas', 'stats'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nis' => DataSiswa::normalisasiNis($request->nis),
            'nama' => Str::squish((string) $request->nama),
            'kelas' => Str::squish((string) $request->kelas),
        ]);

        $data = $request->validate([
            'nis' => ['required', 'string', 'max:30', 'unique:data_siswas,nis'],
            'nama' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:30'],
        ]);

        DataSiswa::create($data);

        return back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Impor massal dari file CSV dengan kolom: nis, nama, kelas.
     * Pemisah koma (,) atau titik koma (;) dikenali otomatis, baris judul boleh ada.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $barisPertama = (string) fgets($handle);
        rewind($handle);
        $pemisah = substr_count($barisPertama, ';') > substr_count($barisPertama, ',') ? ';' : ',';

        $hasil = ['ditambah' => 0, 'diperbarui' => 0, 'dilewati' => 0];
        $nomor = 0;

        DB::transaction(function () use ($handle, $pemisah, &$hasil, &$nomor) {
            while (($row = fgetcsv($handle, 0, $pemisah)) !== false) {
                $nomor++;

                if ($nomor > self::MAKS_BARIS_IMPOR) {
                    $hasil['dilewati']++;
                    break;
                }

                // Buang karakter BOM (muncul pada CSV dari Excel) dan ubah ke UTF-8 bila perlu.
                $row = array_map(function ($v) {
                    $v = preg_replace('/^\xEF\xBB\xBF/', '', (string) $v);

                    return mb_check_encoding($v, 'UTF-8') ? $v : mb_convert_encoding($v, 'UTF-8', 'Windows-1252');
                }, $row);

                if (count(array_filter($row, fn ($v) => trim($v) !== '')) === 0) {
                    continue; // baris kosong
                }

                [$nis, $nama, $kelas] = array_pad($row, 3, '');

                if ($nomor === 1 && Str::lower(trim($nis)) === 'nis') {
                    continue; // baris judul
                }

                $nis = DataSiswa::normalisasiNis($nis);
                $nama = Str::squish($nama);
                $kelas = Str::squish($kelas);

                if ($nis === '' || $nama === '' || $kelas === ''
                    || mb_strlen($nis) > 30 || mb_strlen($nama) > 100 || mb_strlen($kelas) > 30) {
                    $hasil['dilewati']++;

                    continue;
                }

                $ada = DataSiswa::where('nis', $nis)->first();

                if (! $ada) {
                    DataSiswa::create(compact('nis', 'nama', 'kelas'));
                    $hasil['ditambah']++;
                } elseif ($ada->sudahTerdaftar()) {
                    $hasil['dilewati']++; // sudah punya akun, jangan diubah
                } else {
                    $ada->update(compact('nama', 'kelas'));
                    $hasil['diperbarui']++;
                }
            }
        });

        fclose($handle);

        $ringkasan = "{$hasil['ditambah']} ditambahkan, {$hasil['diperbarui']} diperbarui, {$hasil['dilewati']} dilewati.";

        if ($hasil['ditambah'] + $hasil['diperbarui'] === 0) {
            return back()->with('error', "Tidak ada data yang diimpor ({$ringkasan}) Pastikan kolomnya: nis, nama, kelas.");
        }

        return back()->with('success', "Impor selesai: {$ringkasan}");
    }

    public function destroy(DataSiswa $dataSiswa)
    {
        if ($dataSiswa->sudahTerdaftar()) {
            return back()->with('error', 'Siswa ini sudah punya akun. Hapus akunnya di menu Data Pengguna dulu jika ingin menghapus datanya.');
        }

        $dataSiswa->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }
}
