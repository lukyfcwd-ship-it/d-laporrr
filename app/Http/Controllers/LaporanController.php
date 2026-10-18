<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $pengaduans = $this->filtered($request)->latest()->paginate(15)->withQueryString();

        return view('laporan.index', compact('pengaduans'));
    }

    public function cetakSemua(Request $request)
    {
        $pengaduans = $this->filtered($request)->latest()->get();

        $pdf = Pdf::loadView('laporan.pdf', [
            'pengaduans' => $pengaduans,
            'periode' => $this->periodeLabel($request),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-pengaduan-sarana-'.now()->format('Ymd-His').'.pdf');
    }

    public function cetakSatu(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'sarana.kategori', 'kategori', 'verifikator']);

        $pdf = Pdf::loadView('laporan.pdf-detail', compact('pengaduan'))->setPaper('a4', 'portrait');

        return $pdf->download('tiket-'.$pengaduan->kode_tiket.'.pdf');
    }

    protected function filtered(Request $request)
    {
        return Pengaduan::with(['user', 'sarana', 'kategori'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->dari, fn ($q) => $q->whereDate('tanggal_kejadian', '>=', $request->dari))
            ->when($request->sampai, fn ($q) => $q->whereDate('tanggal_kejadian', '<=', $request->sampai));
    }

    protected function periodeLabel(Request $request): string
    {
        if ($request->dari && $request->sampai) {
            return $request->dari.' s.d. '.$request->sampai;
        }

        return 'Semua periode';
    }
}
