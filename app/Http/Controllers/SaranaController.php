<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    public function index(Request $request)
    {
        // Kategori ikut dimuat supaya view bisa mencari laporan (pengaduan) TERBARU
        // untuk tiap sarana — baik laporan lama yang menunjuk barang spesifik (sarana_id)
        // maupun laporan baru yang hanya menunjuk kategorinya (kategori_id) — lengkap
        // dengan pelapor (nama, kelas) dan foto buktinya.
        $saranas = Sarana::with('kategori')
            ->when($request->q, fn ($q) => $q->where('nama_sarana', 'like', "%{$request->q}%"))
            ->when($request->kategori_id, fn ($q) => $q->where('kategori_id', $request->kategori_id))
            ->orderBy('nama_sarana')
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('sarana.index', compact('saranas', 'kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'kode_sarana' => ['required', 'string', 'max:30', 'unique:saranas,kode_sarana'],
            'nama_sarana' => ['required', 'string', 'max:150'],
            'lokasi' => ['required', 'string', 'max:150'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        Sarana::create($data);

        return back()->with('success', 'Sarana berhasil ditambahkan.');
    }

    public function update(Request $request, Sarana $sarana)
    {
        $data = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'kode_sarana' => ['required', 'string', 'max:30', 'unique:saranas,kode_sarana,'.$sarana->id],
            'nama_sarana' => ['required', 'string', 'max:150'],
            'lokasi' => ['required', 'string', 'max:150'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        $sarana->update($data);

        return back()->with('success', 'Sarana berhasil diperbarui.');
    }

    public function destroy(Sarana $sarana)
    {
        $sarana->delete();

        return back()->with('success', 'Sarana berhasil dihapus.');
    }
}
