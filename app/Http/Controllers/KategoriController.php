<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('saranas')->orderBy('nama_kategori')->paginate(10);

        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode_kategori' => ['required', 'string', 'max:20', 'unique:kategoris,kode_kategori'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Kategori::create($data);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode_kategori' => ['required', 'string', 'max:20', 'unique:kategoris,kode_kategori,'.$kategori->id],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kategori->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
