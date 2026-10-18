@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4" x-data="{ showAdd: false }">
    <form method="GET" class="flex-1">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pengguna..." class="w-full max-w-xs rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
    </form>
    <button @click="showAdd = true" class="btn-primary px-4 py-2 rounded-lg text-sm inline-flex items-center gap-1.5"><x-icon name="plus" class="w-4 h-4" /> Tambah Pengguna</button>

    <div x-show="showAdd" x-cloak class="modal-overlay fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div @click.outside="showAdd = false" class="modal-panel bg-white rounded-2xl p-6 shadow-2xl shadow-slate-900/20 w-full max-w-md">
            <h3 class="font-bold text-slate-900 mb-4">Tambah Pengguna</h3>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-3">
                @csrf
                <input name="name" placeholder="Nama Lengkap" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                <input type="email" name="email" placeholder="Email" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                <input type="password" name="password" placeholder="Kata Sandi (min 8 karakter)" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                <select name="role" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                    <option value="siswa">Siswa</option>
                    <option value="admin">Admin/Petugas</option>
                </select>
                <input name="nis_nip" placeholder="NIS/NIP (opsional)" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
                <input name="kelas" placeholder="Kelas (opsional)" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm input-fx">
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
                    <th class="text-left px-5 py-3 font-semibold">Nama</th>
                    <th class="text-left px-5 py-3 font-semibold">Email</th>
                    <th class="text-left px-5 py-3 font-semibold">Role</th>
                    <th class="text-left px-5 py-3 font-semibold">Kelas/NIS</th>
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $u)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-medium"><div class="flex items-center gap-3"><span class="avatar w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">{{ strtoupper(substr($u->name, 0, 1)) }}</span>{{ $u->name }}</div></td>
                        <td class="px-5 py-3.5">{{ $u->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $u->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">{{ $u->kelas ?? $u->nis_nip ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors" @if($u->id === auth()->id()) disabled @endif>Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-200">{{ $users->links() }}</div>
</div>
@endsection
