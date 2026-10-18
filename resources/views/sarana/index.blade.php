@extends('layouts.app')
@section('title', 'Data Sarana')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4" x-data="{ showAdd: false }">
    <form method="GET" class="flex flex-wrap gap-2 flex-1">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama sarana..." class="flex-1 min-w-[160px] rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
        <select name="kategori_id" class="rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 active:scale-95 text-white transition text-sm font-medium">Filter</button>
    </form>
    <button @click="showAdd = true" class="btn-primary px-4 py-2 rounded-lg text-sm inline-flex items-center gap-1.5"><x-icon name="plus" class="w-4 h-4" /> Tambah Sarana</button>

    <div x-show="showAdd" x-cloak class="modal-overlay fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="showAdd = false" class="modal-panel bg-white rounded-2xl p-6 shadow-2xl shadow-slate-900/20 w-full max-w-md">
            <h3 class="font-bold text-slate-900 mb-4">Tambah Sarana</h3>
            <form method="POST" action="{{ route('sarana.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="kategori_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                        @foreach ($kategoris as $k)<option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Sarana</label>
                    <input name="kode_sarana" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Sarana</label>
                    <input name="nama_sarana" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi</label>
                    <input name="lokasi" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi</label>
                    <select name="kondisi" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
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
                    <th class="text-left px-5 py-3 font-semibold">Nama</th>
                    <th class="text-left px-5 py-3 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold">Lokasi</th>
                    <th class="text-left px-5 py-3 font-semibold">Kondisi</th>
                    <th class="text-left px-5 py-3 font-semibold">Laporan Terakhir</th>
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($saranas as $s)
                    @php
                        $laporan = \App\Models\Pengaduan::with('user')
                            ->where('sarana_id', $s->id)
                            ->orWhere('kategori_id', $s->kategori_id)
                            ->latest()
                            ->first();
                    @endphp
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs">{{ $s->kode_sarana }}</td>
                        <td class="px-5 py-3.5">{{ $s->nama_sarana }}</td>
                        <td class="px-5 py-3.5">{{ $s->kategori->nama_kategori }}</td>
                        <td class="px-5 py-3.5">{{ $s->lokasi }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $s->kondisi == 'baik' ? 'bg-emerald-100 text-emerald-800' : ($s->kondisi == 'rusak_ringan' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucwords(str_replace('_', ' ', $s->kondisi)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($laporan)
                                <div class="flex items-center gap-2">
                                    @if ($laporan->bukti_foto)
                                        <a href="{{ Storage::url($laporan->bukti_foto) }}" target="_blank" rel="noopener">
                                            <img src="{{ Storage::url($laporan->bukti_foto) }}" alt="Bukti foto {{ $laporan->judul }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2394a3b8%27 stroke-width=%271.5%27%3E%3Crect x=%272%27 y=%273%27 width=%2720%27 height=%2718%27 rx=%272%27 fill=%27%23f1f5f9%27 stroke=%27%23e2e8f0%27/%3E%3Ccircle cx=%278.5%27 cy=%278.5%27 r=%271.5%27/%3E%3Cpath d=%27m21 15-5-5L5 21%27/%3E%3C/svg%3E'" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                        </a>
                                    @else
                                        <span class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300 shrink-0"><x-icon name="photo" class="w-4 h-4" /></span>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('pengaduan.show', $laporan) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs block truncate transition-colors">{{ $laporan->user->name }}</a>
                                        <span class="text-xs text-slate-400">{{ $laporan->user->kelas ?? $laporan->user->nis_nip ?? '-' }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-300 text-xs">Belum ada laporan</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('sarana.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus sarana ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">Belum ada data sarana.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-200">{{ $saranas->links() }}</div>
</div>
@endsection
