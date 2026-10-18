@extends('layouts.app')
@section('title', auth()->user()->isAdmin() ? 'Data Pengaduan' : 'Pengaduan Saya')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    <form method="GET" class="flex flex-wrap gap-2 flex-1">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / kode tiket..."
               class="flex-1 min-w-[160px] rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx input-fx">
        <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx input-fx">
            <option value="">Semua Status</option>
            @foreach (['pending' => 'Menunggu', 'diproses' => 'Diproses', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'selesai' => 'Selesai'] as $val => $label)
                <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 active:scale-95 text-white transition text-sm font-medium">Filter</button>
    </form>
    @if (auth()->user()->isSiswa())
        <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center justify-center gap-1.5 btn-primary px-4 py-2 rounded-lg text-sm"><x-icon name="plus" class="w-4 h-4" /> Buat Baru</a>
    @endif
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    @if (auth()->user()->isAdmin())<th class="text-left px-5 py-3 font-semibold">Foto</th>@endif
                    <th class="text-left px-5 py-3 font-semibold">Tiket</th>
                    <th class="text-left px-5 py-3 font-semibold">Judul</th>
                    @if (auth()->user()->isAdmin())<th class="text-left px-5 py-3 font-semibold">Pelapor</th>@endif
                    <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                    <th class="text-left px-5 py-3 font-semibold">Status</th>
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pengaduans as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        @if (auth()->user()->isAdmin())
                            <td class="px-5 py-3.5">
                                @if ($item->bukti_foto)
                                    <a href="{{ Storage::url($item->bukti_foto) }}" target="_blank" rel="noopener">
                                        <img src="{{ Storage::url($item->bukti_foto) }}" alt="Bukti foto {{ $item->judul }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2394a3b8%27 stroke-width=%271.5%27%3E%3Crect x=%272%27 y=%273%27 width=%2720%27 height=%2718%27 rx=%272%27 fill=%27%23f1f5f9%27 stroke=%27%23e2e8f0%27/%3E%3Ccircle cx=%278.5%27 cy=%278.5%27 r=%271.5%27/%3E%3Cpath d=%27m21 15-5-5L5 21%27/%3E%3C/svg%3E'" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                                    </a>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                        @endif
                        <td class="px-5 py-3.5 font-mono text-xs">{{ $item->kode_tiket }}</td>
                        <td class="px-5 py-3.5">{{ $item->judul }}</td>
                        @if (auth()->user()->isAdmin())
                            <td class="px-5 py-3.5">
                                {{ $item->user->name }}
                                <span class="block text-xs text-slate-400">{{ $item->user->kelas ?? $item->user->nis_nip ?? '-' }}</span>
                            </td>
                        @endif
                        <td class="px-5 py-3.5">{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ \App\Models\Pengaduan::statusBadge($item->status) }}">
                                {{ \App\Models\Pengaduan::statusLabel($item->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('pengaduan.show', $item) }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ auth()->user()->isAdmin() ? 7 : 5 }}" class="px-5 py-12 text-center text-slate-400">Tidak ada data pengaduan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-200">
        {{ $pengaduans->links() }}
    </div>
</div>
@endsection
