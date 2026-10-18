<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Sarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user', 'sarana']);

        if (Auth::user()->isSiswa()) {
            $query->where('user_id', Auth::id());
        }

        $pengaduans = $query
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->q, fn ($q) => $q->where('judul', 'like', "%{$request->q}%")
                ->orWhere('kode_tiket', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        // Siswa cukup memilih KATEGORI sarana (Elektronik, Alat Olahraga, dll),
        // tanpa perlu tahu kode/nama barang spesifik satu per satu.
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('pengaduan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => ['nullable', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'max:150'],
            'lokasi_kerusakan' => ['required', 'string', 'max:150'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'bukti_foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti-pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create($data);

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', "Pengaduan berhasil dikirim dengan kode tiket {$pengaduan->kode_tiket}.");
    }

    public function show(Pengaduan $pengaduan)
    {
        $this->authorizeAccess($pengaduan);
        $pengaduan->load(['user', 'sarana.kategori', 'kategori', 'verifikator']);

        return view('pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:diproses,approved,rejected,selesai'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $data['diverifikasi_oleh'] = Auth::id();
        $data['tanggal_verifikasi'] = now();

        if ($data['status'] === 'selesai') {
            $data['tanggal_selesai'] = now();
        }

        $pengaduan->update($data);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    protected function authorizeAccess(Pengaduan $pengaduan): void
    {
        if (Auth::user()->isSiswa() && $pengaduan->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
