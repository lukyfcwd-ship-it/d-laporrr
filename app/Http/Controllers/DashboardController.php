<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Sarana;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total' => Pengaduan::count(),
                'pending' => Pengaduan::status('pending')->count(),
                'diproses' => Pengaduan::status('diproses')->count(),
                'approved' => Pengaduan::status('approved')->count(),
                'rejected' => Pengaduan::status('rejected')->count(),
                'selesai' => Pengaduan::status('selesai')->count(),
                'total_sarana' => Sarana::count(),
                'total_siswa' => User::where('role', 'siswa')->count(),
            ];

            $terbaru = Pengaduan::with(['user', 'sarana'])->latest()->take(8)->get();

            // Dihitung dari LAPORAN siswa (Data Pengaduan), bukan dari jumlah aset terdaftar,
            // supaya admin langsung tahu kategori fasilitas mana yang paling sering dilaporkan rusak.
            // kategori_id dipakai untuk laporan baru; sarana->kategori_id jadi fallback untuk laporan lama.
            $counts = [];
            $kategoriMap = [];
            foreach (Pengaduan::with(['kategori', 'sarana.kategori'])->get() as $p) {
                $kat = $p->kategori ?? $p->sarana?->kategori;
                if (! $kat) {
                    continue;
                }
                $counts[$kat->id] = ($counts[$kat->id] ?? 0) + 1;
                $kategoriMap[$kat->id] = $kat;
            }
            $per_kategori = collect($counts)
                ->map(fn ($jumlah, $id) => (object) ['kategori' => $kategoriMap[$id], 'jumlah' => $jumlah])
                ->sortByDesc('jumlah')
                ->values();

            // Tren jumlah pengaduan baru dalam 7 hari terakhir, untuk grafik area di dashboard.
            $trend = collect(range(6, 0))->map(function ($daysAgo) {
                $date = now()->subDays($daysAgo);
                return [
                    'label' => $date->translatedFormat('D'),
                    'jumlah' => Pengaduan::whereDate('created_at', $date->toDateString())->count(),
                ];
            });

            return view('dashboard.admin', compact('stats', 'terbaru', 'per_kategori', 'trend'));
        }

        $stats = [
            'total' => $user->pengaduans()->count(),
            'pending' => $user->pengaduans()->status('pending')->count(),
            'approved' => $user->pengaduans()->status('approved')->count(),
            'selesai' => $user->pengaduans()->status('selesai')->count(),
        ];

        $riwayat = $user->pengaduans()->with('sarana')->latest()->take(8)->get();

        return view('dashboard.siswa', compact('stats', 'riwayat'));
    }
}
