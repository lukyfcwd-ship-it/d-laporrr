@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
@php
    $h = now()->hour;
    $salam = $h < 11 ? 'pagi' : ($h < 15 ? 'siang' : ($h < 18 ? 'sore' : 'malam'));

    $segs = [
        ['Menunggu',  $stats['pending'],  '#f59e0b'],
        ['Diproses',  $stats['diproses'], '#3b82f6'],
        ['Disetujui', $stats['approved'], '#10b981'],
        ['Selesai',   $stats['selesai'],  '#6366f1'],
        ['Ditolak',   $stats['rejected'], '#ef4444'],
    ];
    $sum = array_sum(array_column($segs, 1));
    $offset = 0;
    $maxKat = max($per_kategori->max('jumlah') ?? 1, 1);
    $barColors = ['bg-indigo-500', 'bg-blue-500', 'bg-cyan-500', 'bg-emerald-500', 'bg-amber-500'];
@endphp

<!-- Hero -->
<section class="hero p-6 md:p-8 mb-6">


    <div class="absolute inset-0 noise-grid opacity-30"></div>
    <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <p class="text-indigo-100 text-sm font-medium">Selamat {{ $salam }},</p>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-0.5">{{ auth()->user()->name }}</h2>
            <p class="text-indigo-100/90 mt-2 max-w-md text-sm md:text-base">
                @if ($stats['pending'] > 0)
                    Ada <span class="font-extrabold text-white">{{ $stats['pending'] }} pengaduan</span> yang menunggu verifikasi kamu hari ini.
                @else
                    Semua pengaduan sudah tertangani. Kerja bagus!
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('pengaduan.index', ['status' => 'pending']) }}" class="btn-white inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm">
                Tinjau Sekarang <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
            <a href="{{ route('laporan.index') }}" class="btn-glass inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold">
                <x-icon name="printer" class="w-4 h-4" /> Cetak Rekap
            </a>
        </div>
    </div>
</section>

<!-- Stat cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['Total Pengaduan', $stats['total'],    'ico-blue',   'clipboard', 'text-slate-900'],
        ['Menunggu',        $stats['pending'],  'ico-amber',  'clock',     'text-amber-600'],
        ['Diproses',        $stats['diproses'], 'ico-violet', 'chart',     'text-indigo-600'],
        ['Selesai',         $stats['selesai'],  'ico-green',  'check',     'text-emerald-600'],
    ] as $i => [$label, $val, $ico, $icon, $txt])
        <div class="card card-hover reveal p-5" style="--d: {{ 0.08 * $i + 0.1 }}s">
            <div class="relative inline-flex mb-4">
                <div class="ico {{ $ico }} w-11 h-11 rounded-2xl"><x-icon name="{{ $icon }}" class="w-5 h-5" /></div>
                @if ($label === 'Menunggu' && $val > 0)
                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500 ring-2 ring-white"></span>
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-500">{{ $label }}</p>
            <p class="text-3xl font-extrabold {{ $txt }} mt-0.5" data-count="{{ $val }}">{{ $val }}</p>
        </div>
    @endforeach
</div>

<!-- Charts -->
<div class="grid lg:grid-cols-3 gap-4 mb-6">
    <div class="card reveal p-5 md:p-6" style="--d:.2s">
        <h3 class="font-extrabold text-slate-900">Status Pengaduan</h3>
        <p class="text-xs text-slate-500 mb-4">Distribusi seluruh laporan</p>
        <div class="flex items-center gap-5">
            <div class="relative w-36 h-36 shrink-0">
                <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90" role="img" aria-label="Diagram donat status pengaduan">
                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#eef0f6" stroke-width="3.6"/>
                    @foreach ($segs as $i => [$name, $val, $color])
                        @if ($val > 0)
                            @php $pct = $val / max($sum, 1) * 100; @endphp
                            <circle class="donut-seg" cx="18" cy="18" r="15.9155" fill="none" stroke="{{ $color }}" stroke-width="3.6"
                                    stroke-dasharray="{{ number_format($pct, 2, '.', '') }} {{ number_format(100 - $pct, 2, '.', '') }}"
                                    stroke-dashoffset="{{ number_format(-$offset, 2, '.', '') }}" style="animation-delay: {{ $i * 0.12 }}s"/>
                            @php $offset += $pct; @endphp
                        @endif
                    @endforeach
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-extrabold text-slate-900" data-count="{{ $sum }}">{{ $sum }}</span>
                    <span class="text-[11px] text-slate-500 font-medium">Laporan</span>
                </div>
            </div>
            <ul class="space-y-1.5 text-sm flex-1 min-w-0">
                @foreach ($segs as [$name, $val, $color])
                    <li class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $color }}"></span>
                        <span class="text-slate-600 truncate">{{ $name }}</span>
                        <span class="ml-auto font-bold text-slate-900">{{ $val }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card reveal p-5 md:p-6" style="--d:.3s">
        <h3 class="font-bold text-slate-900">Laporan Kerusakan per Kategori</h3>
        <p class="text-xs text-slate-500 mb-4">Berdasarkan laporan siswa</p>
        <div class="space-y-4">
            @forelse ($per_kategori as $i => $row)
                <div class="kat-row p-1.5 -m-1.5">
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="font-medium text-slate-700">{{ $row->kategori->nama_kategori ?? '-' }}</span>
                        <span class="font-bold text-slate-900">{{ $row->jumlah }}</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="bar h-full rounded-full {{ $barColors[$i % count($barColors)] }}" style="--w: {{ round($row->jumlah / $maxKat * 100) }}%; animation-delay: {{ 0.35 + $i * 0.1 }}s"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400">Belum ada laporan kerusakan.</p>
            @endforelse
        </div>
    </div>

    <div class="card reveal p-5 md:p-6" style="--d:.4s">
        <h3 class="font-extrabold text-slate-900">Ringkasan</h3>
        <p class="text-xs text-slate-500 mb-4">Data master sistem</p>
        <ul class="space-y-3">
            <li class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50 transition-colors">
                <span class="ico ico-slate w-10 h-10 rounded-xl"><x-icon name="building" class="w-5 h-5" /></span>
                <div class="flex-1"><p class="text-xs text-slate-500 font-medium">Sarana terdaftar</p></div>
                <span class="text-xl font-extrabold text-slate-900" data-count="{{ $stats['total_sarana'] }}">{{ $stats['total_sarana'] }}</span>
            </li>
            <li class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50 transition-colors">
                <span class="ico ico-blue w-10 h-10 rounded-xl"><x-icon name="users" class="w-5 h-5" /></span>
                <div class="flex-1"><p class="text-xs text-slate-500 font-medium">Siswa terdaftar</p></div>
                <span class="text-xl font-extrabold text-slate-900" data-count="{{ $stats['total_siswa'] }}">{{ $stats['total_siswa'] }}</span>
            </li>
            <li class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-red-50 transition-colors">
                <span class="ico ico-red w-10 h-10 rounded-xl"><x-icon name="warning" class="w-5 h-5" /></span>
                <div class="flex-1"><p class="text-xs text-slate-500 font-medium">Laporan ditolak</p></div>
                <span class="text-xl font-extrabold text-red-600" data-count="{{ $stats['rejected'] }}">{{ $stats['rejected'] }}</span>
            </li>
        </ul>
    </div>
</div>

@php
    // viewBox lebar (700x180) sengaja dipilih mendekati rasio kartu sesungguhnya,
    // supaya TIDAK perlu preserveAspectRatio="none" — itulah yang membuat garis &
    // titik sebelumnya tampak gepeng/aneh (stroke dan lingkaran ikut terdistorsi).
    $padX = 24; $padTop = 16; $padBottom = 16; $w = 700; $h = 180;
    $tMax = max($trend->max('jumlah'), 1);
    $n = max($trend->count() - 1, 1);
    $tPts = $trend->values()->map(function ($row, $i) use ($tMax, $n, $padX, $padTop, $padBottom, $w, $h) {
        $x = round($padX + ($i / $n) * ($w - $padX * 2), 1);
        $y = round(($h - $padBottom) - ($row['jumlah'] / $tMax) * ($h - $padTop - $padBottom), 1);
        return ['x' => $x, 'y' => $y];
    });
    $linePath = 'M '.$tPts->map(fn ($p) => "{$p['x']},{$p['y']}")->implode(' L ');
    $areaPath = $linePath." L {$tPts->last()['x']},".($h - $padBottom)." L {$tPts->first()['x']},".($h - $padBottom).' Z';
@endphp
<div class="card reveal p-5 md:p-6 mb-6" style="--d:.15s">
    <div class="flex items-center justify-between mb-1">
        <div>
            <h3 class="font-bold text-slate-900">Tren Pengaduan Masuk</h3>
            <p class="text-xs text-slate-500">7 hari terakhir</p>
        </div>
        <span class="ico ico-violet w-10 h-10 rounded-xl shrink-0"><x-icon name="chart" class="w-5 h-5" /></span>
    </div>
    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="w-full h-32 mt-2" role="img" aria-label="Grafik tren pengaduan 7 hari terakhir">
        <defs>
            <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#4f46e5" stop-opacity=".18"/>
                <stop offset="100%" stop-color="#4f46e5" stop-opacity="0"/>
            </linearGradient>
        </defs>
        <path d="{{ $areaPath }}" fill="url(#areaFill)" stroke="none"/>
        <path d="{{ $linePath }}" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
              class="draw-line" style="--len: 1400"/>
        @foreach ($tPts as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" fill="#fff" stroke="#4f46e5" stroke-width="2"/>
        @endforeach
    </svg>
    <div class="flex justify-between text-[11px] text-slate-400 font-medium px-0.5">
        @foreach ($trend as $row)<span>{{ $row['label'] }}</span>@endforeach
    </div>
</div>

<!-- Recent table -->
<div class="card reveal overflow-hidden" style="--d:.45s">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-extrabold text-slate-900">Pengaduan Terbaru</h2>
        <a href="{{ route('pengaduan.index') }}" class="group inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
            Lihat semua <x-icon name="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" />
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Tiket</th>
                    <th class="text-left px-5 py-3 font-semibold">Judul</th>
                    <th class="text-left px-5 py-3 font-semibold">Pelapor</th>
                    <th class="text-left px-5 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($terbaru as $item)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-5 py-3.5"><a href="{{ route('pengaduan.show', $item) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">{{ $item->kode_tiket }}</a></td>
                        <td class="px-5 py-3.5 font-medium">{{ $item->judul }}</td>
                        <td class="px-5 py-3.5 text-slate-500">{{ $item->user->name }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ \App\Models\Pengaduan::statusBadge($item->status) }}">{{ \App\Models\Pengaduan::statusLabel($item->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">Belum ada pengaduan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
