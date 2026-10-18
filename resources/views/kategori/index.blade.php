@extends('layouts.app')
@section('title', 'Kategori Sarana')

@section('content')
<div class="flex items-center justify-between mb-4" x-data="{ showAdd: false }">
    <h2 class="font-semibold text-slate-900">Master Data Kategori Sarana</h2>
    <button @click="showAdd = true" class="btn-primary px-4 py-2 rounded-lg text-sm inline-flex items-center gap-1.5"><x-icon name="plus" class="w-4 h-4" /> Tambah Kategori</button>

    <div x-show="showAdd" x-cloak class="modal-overlay fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="showAdd = false" class="modal-panel bg-white rounded-2xl p-6 shadow-2xl shadow-slate-900/20 w-full max-w-md">
            <h3 class="font-bold text-slate-900 mb-4">Tambah Kategori</h3>
            <form method="POST" action="{{ route('kategori.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                    <input name="nama_kategori" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Kategori</label>
                    <input name="kode_kategori" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx" placeholder="Contoh: LAB-KOM">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx"></textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 btn-primary py-2 rounded-lg text-sm">Simpan</button>
                    <button type="button" @click="showAdd = false" class="flex-1 border border-slate-300 text-slate-600 hover:bg-indigo-50/50 transition-colors py-2 rounded-lg text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Kode</th>
                    <th class="text-left px-5 py-3 font-semibold">Nama Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold">Jumlah Sarana</th>
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($kategoris as $kategori)
                    <tr class="hover:bg-indigo-50/50 transition-colors" x-data="{ showEdit: false }">
                        <td class="px-5 py-3.5 font-mono text-xs">{{ $kategori->kode_kategori }}</td>
                        <td class="px-5 py-3.5">{{ $kategori->nama_kategori }}</td>
                        <td class="px-5 py-3.5">{{ $kategori->saranas_count }}</td>
                        <td class="px-5 py-3.5 text-right space-x-2">
                            <button @click="showEdit = true" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Edit</button>
                            <form method="POST" action="{{ route('kategori.destroy', $kategori) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">Hapus</button>
                            </form>

                            <div x-show="showEdit" x-cloak class="modal-overlay fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                                <div @click.outside="showEdit = false" class="modal-panel bg-white rounded-2xl p-6 shadow-2xl shadow-slate-900/20 w-full max-w-md text-left">
                                    <h3 class="font-bold text-slate-900 mb-4">Edit Kategori</h3>
                                    <form method="POST" action="{{ route('kategori.update', $kategori) }}" class="space-y-3">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori</label>
                                            <input name="nama_kategori" value="{{ $kategori->nama_kategori }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Kode Kategori</label>
                                            <input name="kode_kategori" value="{{ $kategori->kode_kategori }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                                            <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">{{ $kategori->keterangan }}</textarea>
                                        </div>
                                        <div class="flex gap-2 pt-2">
                                            <button type="submit" class="flex-1 btn-primary py-2 rounded-lg text-sm">Simpan</button>
                                            <button type="button" @click="showEdit = false" class="flex-1 border border-slate-300 text-slate-600 hover:bg-indigo-50/50 transition-colors py-2 rounded-lg text-sm">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-200">{{ $kategoris->links() }}</div>
</div>
@endsection
