@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('content')
@php
    $h = now()->hour;
    $salam = $h < 11 ? 'pagi' : ($h < 15 ? 'siang' : ($h < 18 ? 'sore' : 'malam'));
    $bar = ['pending' => 'bg-amber-400', 'diproses' => 'bg-blue-500', 'approved' => 'bg-emerald-500', 'rejected' => 'bg-red-500', 'selesai' => 'bg-indigo-500'];
@endphp

<section class="hero p-6 md:p-8 mb-6">

    <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <p class="text-indigo-100 text-sm font-medium flex items-center gap-1.5">Selamat {{ $salam }} <x-icon name="sparkles" class="w-4 h-4 text-amber-300" /></p>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-0.5">{{ auth()->user()->name }}</h2>
            <p class="text-indigo-100/90 mt-2 max-w-md text-sm md:text-base">Lihat ada sarana sekolah yang rusak? Laporkan sekarang dan pantau penanganannya di sini.</p>
        </div>
        <a href="{{ route('pengaduan.create') }}" class="btn-white inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm self-start md:self-auto">
            <x-icon name="plus" class="w-5 h-5" /> Buat Pengaduan Baru
        </a>
    </div>
</section>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['Total Laporan Saya', $stats['total'],    'ico-blue',  'clipboard', 'text-slate-900'],
        ['Menunggu',           $stats['pending'],  'ico-amber', 'clock',     'text-amber-600'],
        ['Disetujui',          $stats['approved'], 'ico-green', 'check',     'text-emerald-600'],
        ['Selesai',            $stats['selesai'],  'ico-slate', 'dashboard', 'text-slate-700'],
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

<div class="card reveal overflow-hidden" style="--d:.35s">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-extrabold text-slate-900">Riwayat Terbaru</h2>
        <a href="{{ route('pengaduan.index') }}" class="group inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
            Lihat semua <x-icon name="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" />
        </a>
    </div>
    <ul class="divide-y divide-slate-100">
        @forelse ($riwayat as $item)
            <li>
                <a href="{{ route('pengaduan.show', $item) }}" class="group flex items-center gap-4 px-5 py-4 hover:bg-indigo-50/50 transition-colors">
                    <span class="w-1.5 h-10 rounded-full shrink-0 {{ $bar[$item->status] ?? 'bg-slate-300' }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-slate-900 truncate group-hover:text-indigo-700 transition-colors">{{ $item->judul }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item->kode_tiket }} · {{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-semibold {{ \App\Models\Pengaduan::statusBadge($item->status) }}">{{ \App\Models\Pengaduan::statusLabel($item->status) }}</span>
                    <x-icon name="arrow-right" class="w-4 h-4 text-slate-300 shrink-0 transition-all group-hover:text-indigo-500 group-hover:translate-x-1" />
                </a>
            </li>
        @empty
            <li class="px-5 py-14 text-center">
                <span class="ico ico-blue w-14 h-14 rounded-2xl mx-auto mb-3 float"><x-icon name="clipboard" class="w-6 h-6" /></span>
                <p class="font-semibold text-slate-700">Belum ada pengaduan</p>
                <p class="text-sm text-slate-400">Yuk buat laporan pertamamu!</p>
            </li>
        @endforelse
    </ul>
</div>
@endsection
