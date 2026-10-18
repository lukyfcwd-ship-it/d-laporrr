@extends('layouts.app')
@section('title', 'Detail Pengaduan')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-lg font-bold text-slate-900">{{ $pengaduan->judul }}</h2>
        <div x-data="{ copied: false }" class="flex items-center gap-1.5">
            <p class="text-sm text-slate-500 font-mono">{{ $pengaduan->kode_tiket }}</p>
            <button type="button"
                    @click="navigator.clipboard.writeText('{{ $pengaduan->kode_tiket }}'); copied = true; setTimeout(() => copied = false, 1500)"
                    class="copy-btn p-1 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50"
                    :aria-label="copied ? 'Tersalin' : 'Salin kode tiket'">
                <span x-show="!copied"><x-icon name="copy" class="w-4 h-4" /></span>
                <span x-show="copied" x-cloak class="copy-check text-emerald-500"><x-icon name="check" class="w-4 h-4" /></span>
            </button>
        </div>
    </div>
    <a href="{{ route('pengaduan.cetak', $pengaduan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium hover:bg-slate-50">
        <x-icon name="printer" class="w-4 h-4" /> Cetak PDF
    </a>
</div>

@php
    $steps = ['Diajukan', 'Diproses', 'Keputusan', 'Selesai'];
    $cur = match ($pengaduan->status) { 'pending' => 0, 'diproses' => 1, 'approved', 'rejected' => 2, 'selesai' => 3, default => 0 };
    $rej = $pengaduan->status === 'rejected';
@endphp
<div class="card p-5 md:p-6 mb-4">
    <ol class="flex items-start">
        @foreach ($steps as $i => $label)
            <li class="flex items-start {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center w-14 sm:w-20">
                    <span class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all
                        {{ $i < $cur ? 'bg-indigo-600 text-white' : ($i === $cur ? (($rej && $i === 2) ? 'bg-red-500 text-white' : 'bg-indigo-600 text-white') . ' pulse-ring' : 'bg-slate-100 text-slate-400') }}">
                        @if ($i < $cur) <x-icon name="check" class="w-5 h-5" /> @else {{ $i + 1 }} @endif
                    </span>
                    <span class="mt-2 text-[11px] sm:text-xs font-semibold text-center {{ $i <= $cur ? 'text-slate-800' : 'text-slate-400' }}">
                        {{ ($i === 2 && $cur === 2) ? ($rej ? 'Ditolak' : 'Disetujui') : $label }}
                    </span>
                </div>
                @if ($i < count($steps) - 1)
                    <div class="flex-1 h-1 mt-[18px] rounded-full bg-slate-100 overflow-hidden">
                        <div class="bar h-full rounded-full bg-indigo-500" style="--w: {{ $i < $cur ? 100 : 0 }}%; animation-delay: {{ 0.3 + $i * 0.25 }}s"></div>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
</div>

<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 card p-4 md:p-6 space-y-4">
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ \App\Models\Pengaduan::statusBadge($pengaduan->status) }}">
                {{ \App\Models\Pengaduan::statusLabel($pengaduan->status) }}
            </span>
            <span class="text-xs text-slate-400">Prioritas: {{ ucfirst($pengaduan->prioritas) }}</span>
        </div>

        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Pelapor</dt><dd class="font-medium">{{ $pengaduan->user->name }} <span class="text-slate-400 font-normal">({{ $pengaduan->user->kelas ?? $pengaduan->user->nis_nip ?? '-' }})</span></dd></div>
            <div><dt class="text-slate-500">Tanggal Kejadian</dt><dd class="font-medium">{{ $pengaduan->tanggal_kejadian->translatedFormat('d F Y') }}</dd></div>
            <div><dt class="text-slate-500">Kategori Sarana</dt><dd class="font-medium">{{ $pengaduan->kategori?->nama_kategori ?? $pengaduan->sarana?->kategori?->nama_kategori ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Lokasi Kerusakan</dt><dd class="font-medium">{{ $pengaduan->lokasi_kerusakan ?? '-' }}</dd></div>
        </dl>

        <div>
            <dt class="text-slate-500 text-sm mb-1">Deskripsi</dt>
            <dd class="text-slate-800">{{ $pengaduan->deskripsi }}</dd>
        </div>

        @if ($pengaduan->bukti_foto)
            <div>
                <dt class="text-slate-500 text-sm mb-1">Bukti Foto</dt>
                <img src="{{ Storage::url($pengaduan->bukti_foto) }}" alt="Bukti foto kerusakan {{ $pengaduan->judul }}" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%2394a3b8%27 stroke-width=%271.5%27%3E%3Crect x=%272%27 y=%273%27 width=%2720%27 height=%2718%27 rx=%272%27 fill=%27%23f1f5f9%27 stroke=%27%23e2e8f0%27/%3E%3Ccircle cx=%278.5%27 cy=%278.5%27 r=%271.5%27/%3E%3Cpath d=%27m21 15-5-5L5 21%27/%3E%3C/svg%3E'" class="rounded-lg border border-slate-200 max-w-sm w-full">
            </div>
        @endif

        @if ($pengaduan->catatan_admin)
            <div class="bg-slate-50 rounded-lg p-4">
                <dt class="text-slate-500 text-sm mb-1">Catatan Petugas</dt>
                <dd class="text-slate-800">{{ $pengaduan->catatan_admin }}</dd>
                @if ($pengaduan->verifikator)
                    <p class="text-xs text-slate-400 mt-1">oleh {{ $pengaduan->verifikator->name }} · {{ $pengaduan->tanggal_verifikasi?->translatedFormat('d M Y H:i') }}</p>
                @endif
            </div>
        @endif
    </div>

    @if (auth()->user()->isAdmin())
        <div class="card p-4 md:p-6 h-fit">
            <h3 class="font-semibold text-slate-900 mb-3">Verifikasi & Tindak Lanjut</h3>
            <form method="POST" action="{{ route('pengaduan.status', $pengaduan) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Ubah Status</label>
                    <select id="status" name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx input-fx">
                        <option value="diproses" @selected($pengaduan->status == 'diproses')>Diproses</option>
                        <option value="approved" @selected($pengaduan->status == 'approved')>Disetujui</option>
                        <option value="rejected" @selected($pengaduan->status == 'rejected')>Ditolak</option>
                        <option value="selesai" @selected($pengaduan->status == 'selesai')>Selesai / Diperbaiki</option>
                    </select>
                </div>
                <div>
                    <label for="catatan_admin" class="block text-sm font-medium text-slate-700 mb-1">Catatan / Tanggapan</label>
                    <textarea id="catatan_admin" name="catatan_admin" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx input-fx">{{ $pengaduan->catatan_admin }}</textarea>
                </div>
                <button type="submit" class="w-full btn-primary py-2.5 rounded-lg text-sm">Simpan Perubahan</button>
            </form>
        </div>
    @endif
</div>
@endsection
