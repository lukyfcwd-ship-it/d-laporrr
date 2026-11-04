@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')
<div x-data="{ showAdd: false }">
    @if ($errors->any())
        <div class="mb-5 flex items-start gap-2.5 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm" role="alert">
            <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
            <div>@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        </div>
    @endif

    <!-- Penjelasan fungsi halaman -->
    <div class="card p-5 mb-4 flex items-start gap-3">
        <span class="ico ico-ink w-10 h-10 rounded-xl shrink-0"><x-icon name="users" class="w-5 h-5" /></span>
        <p class="text-sm text-slate-600">
            Daftar ini adalah <span class="font-semibold text-slate-900">data siswa resmi SMK Negeri 5 Surakarta</span>.
            Hanya siswa yang <span class="font-semibold">NIS dan namanya</span> ada di sini yang bisa membuat akun lewat halaman <span class="font-mono text-xs">Buat Akun</span>.
            Satu NIS hanya bisa dipakai untuk satu akun.
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div class="card p-4"><p class="text-xs font-semibold text-slate-500">Total siswa</p><p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total'] }}</p></div>
        <div class="card p-4"><p class="text-xs font-semibold text-slate-500">Sudah punya akun</p><p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $stats['terdaftar'] }}</p></div>
        <div class="card p-4"><p class="text-xs font-semibold text-slate-500">Belum punya akun</p><p class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $stats['belum'] }}</p></div>
    </div>

    <!-- Impor CSV -->
    <div class="card p-5 mb-4">
        <h3 class="font-bold text-slate-900">Impor dari file CSV</h3>
        <p class="text-xs text-slate-500 mt-0.5 mb-3">
            Kolom: <span class="font-mono">nis, nama, kelas</span> (pemisah koma atau titik koma, baris judul boleh ada).
            NIS yang sudah ada dan belum punya akun akan diperbarui; yang sudah punya akun dilewati.
            <a href="{{ asset('contoh-data-siswa.csv') }}" download class="text-brand-600 hover:text-brand-800 font-medium transition-colors">Unduh contoh file</a>
        </p>
        <form method="POST" action="{{ route('data-siswa.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 sm:items-center">
            @csrf
            <input type="file" name="file" accept=".csv,.txt" required
                   class="flex-1 text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-medium hover:file:bg-brand-100">
            <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm inline-flex items-center justify-center gap-1.5">Impor Data</button>
        </form>
    </div>

    <!-- Pencarian, filter, tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIS, nama, atau kelas..." class="flex-1 min-w-[160px] rounded-xl border border-[#d6cfbd] px-3 py-2 text-sm input-fx">
            <select name="status" class="rounded-xl border border-[#d6cfbd] px-3 py-2 text-sm input-fx">
                <option value="">Semua status</option>
                <option value="terdaftar" @selected(request('status') === 'terdaftar')>Sudah punya akun</option>
                <option value="belum" @selected(request('status') === 'belum')>Belum punya akun</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-900 hover:bg-brand-800 active:scale-95 text-white text-sm font-medium transition">Filter</button>
        </form>
        <button @click="showAdd = true" type="button" class="btn-primary px-4 py-2 rounded-lg text-sm inline-flex items-center gap-1.5"><x-icon name="plus" class="w-4 h-4" /> Tambah Siswa</button>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#faf7ef] text-slate-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold">NIS</th>
                        <th class="text-left px-5 py-3 font-semibold">Nama</th>
                        <th class="text-left px-5 py-3 font-semibold">Kelas</th>
                        <th class="text-left px-5 py-3 font-semibold">Status Akun</th>
                        <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe9da]">
                    @forelse ($siswas as $s)
                        <tr class="hover:bg-sun-300/15 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs">{{ $s->nis }}</td>
                            <td class="px-5 py-3.5 font-medium">{{ $s->nama }}</td>
                            <td class="px-5 py-3.5">{{ $s->kelas }}</td>
                            <td class="px-5 py-3.5">
                                @if ($s->sudahTerdaftar())
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200">Sudah punya akun</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200">Belum punya akun</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if ($s->sudahTerdaftar())
                                    <span class="text-xs text-slate-400">Hapus akun dulu di Data Pengguna</span>
                                @else
                                    <form method="POST" action="{{ route('data-siswa.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus data siswa ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">Belum ada data siswa. Tambahkan satu per satu atau impor dari file CSV agar siswa bisa membuat akun.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-[#e6dfcf]">{{ $siswas->links() }}</div>
    </div>

    <!-- Modal tambah siswa -->
    <div x-show="showAdd" x-cloak class="modal-overlay fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="showAdd = false" class="modal-panel bg-white rounded-2xl p-6 shadow-2xl shadow-slate-900/20 w-full max-w-md">
            <h3 class="font-bold text-slate-900 mb-4">Tambah Data Siswa</h3>
            <form method="POST" action="{{ route('data-siswa.store') }}" class="space-y-3">
                @csrf
                <input name="nis" placeholder="NIS" required maxlength="30" class="w-full rounded-xl border border-[#d6cfbd] px-3 py-2 text-sm input-fx">
                <input name="nama" placeholder="Nama lengkap (sesuai data sekolah)" required maxlength="100" class="w-full rounded-xl border border-[#d6cfbd] px-3 py-2 text-sm input-fx">
                <input name="kelas" placeholder="Kelas, contoh: XII RPL A" required maxlength="30" class="w-full rounded-xl border border-[#d6cfbd] px-3 py-2 text-sm input-fx">
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 btn-primary py-2 rounded-lg text-sm">Simpan</button>
                    <button type="button" @click="showAdd = false" class="flex-1 border border-[#d6cfbd] text-slate-600 hover:bg-sun-300/15 transition-colors py-2 rounded-lg text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
