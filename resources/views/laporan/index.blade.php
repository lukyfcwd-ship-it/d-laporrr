@extends('layouts.app')
@section('title', 'Rekap & Cetak Laporan')

@section('content')
<form method="GET" class="flex flex-wrap items-end gap-2 mb-4 bg-white border border-slate-200 rounded-xl p-4">
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
        <input type="date" name="dari" value="{{ request('dari') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Sampai Tanggal</label>
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
        <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
            <option value="">Semua</option>
            @foreach (['pending' => 'Menunggu', 'diproses' => 'Diproses', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'selesai' => 'Selesai'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 active:scale-95 text-white transition text-sm font-medium">Terapkan Filter</button>
    <a href="{{ route('laporan.cetak', request()->query()) }}" class="ml-auto inline-flex items-center gap-1.5 btn-primary px-4 py-2 rounded-lg text-sm">
        <x-icon name="printer" class="w-4 h-4" /> Cetak PDF Rekap
    </a>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Tiket</th>
                    <th class="text-left px-5 py-3 font-semibold">Judul</th>
                    <th class="text-left px-5 py-3 font-semibold">Pelapor</th>
                    <th class="text-left px-5 py-3 font-semibold">Kategori</th>
                    <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                    <th class="text-left px-5 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pengaduans as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs">{{ $item->kode_tiket }}</td>
                        <td class="px-5 py-3.5">{{ $item->judul }}</td>
                        <td class="px-5 py-3.5">{{ $item->user->name }}</td>
                        <td class="px-5 py-3.5">{{ $item->kategori?->nama_kategori ?? $item->sarana?->nama_sarana ?? '-' }}</td>
                        <td class="px-5 py-3.5">{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ \App\Models\Pengaduan::statusBadge($item->status) }}">
                                {{ \App\Models\Pengaduan::statusLabel($item->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-200">{{ $pengaduans->links() }}</div>
</div>
@endsection
