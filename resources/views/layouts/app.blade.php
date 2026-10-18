<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · D-Laporr</title>
    @include('partials.head')
</head>
<body class="page-app text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
@php $nav = fn ($on) => 'nav-link'.($on ? ' nav-link-active' : ''); @endphp
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="sidebar-bg fixed inset-y-0 left-0 z-40 w-64 text-white transform transition-transform duration-300 md:translate-x-0 md:static md:flex md:flex-col">
        <div class="h-16 flex items-center gap-3 px-5 border-b border-white/10">
            <span class="brand-badge w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="logo" class="w-5 h-5 text-white" />
            </span>
            <div class="leading-tight">
                <span class="block font-extrabold text-lg tracking-tight">D-Laporr</span>
                <span class="block text-[10px] uppercase tracking-widest text-slate-500">Sarana Sekolah</span>
            </div>
        </div>

        <nav data-nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto" aria-label="Navigasi utama">
            <a href="{{ route('dashboard') }}" class="{{ $nav(request()->routeIs('dashboard')) }}">
                <x-icon name="dashboard" class="w-5 h-5 shrink-0" /> Dashboard
            </a>
            <a href="{{ route('pengaduan.index') }}" class="{{ $nav(request()->routeIs('pengaduan.*') && ! request()->routeIs('pengaduan.create')) }}">
                <x-icon name="clipboard" class="w-5 h-5 shrink-0" /> {{ auth()->user()->isAdmin() ? 'Data Pengaduan' : 'Pengaduan Saya' }}
            </a>
            @if (auth()->user()->isSiswa())
                <a href="{{ route('pengaduan.create') }}" class="{{ $nav(request()->routeIs('pengaduan.create')) }}">
                    <x-icon name="plus" class="w-5 h-5 shrink-0" /> Buat Pengaduan
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <p class="px-3 pt-5 pb-1.5 text-[11px] font-bold uppercase tracking-widest text-slate-500">Master Data</p>
                <a href="{{ route('kategori.index') }}" class="{{ $nav(request()->routeIs('kategori.*')) }}"><x-icon name="tag" class="w-5 h-5 shrink-0" /> Kategori Sarana</a>
                <a href="{{ route('sarana.index') }}" class="{{ $nav(request()->routeIs('sarana.*')) }}"><x-icon name="building" class="w-5 h-5 shrink-0" /> Data Sarana</a>
                <a href="{{ route('users.index') }}" class="{{ $nav(request()->routeIs('users.*')) }}"><x-icon name="users" class="w-5 h-5 shrink-0" /> Data Pengguna</a>
                <p class="px-3 pt-5 pb-1.5 text-[11px] font-bold uppercase tracking-widest text-slate-500">Laporan</p>
                <a href="{{ route('laporan.index') }}" class="{{ $nav(request()->routeIs('laporan.*')) }}"><x-icon name="chart" class="w-5 h-5 shrink-0" /> Rekap & Cetak PDF</a>
            @endif
            <p class="px-3 pt-5 pb-1.5 text-[11px] font-bold uppercase tracking-widest text-slate-500">Akun</p>
            <a href="{{ route('profile.edit') }}" class="{{ $nav(request()->routeIs('profile.*')) }}"><x-icon name="cog" class="w-5 h-5 shrink-0" /> Profil & Kata Sandi</a>
        </nav>

        <div class="p-3 border-t border-white/10">
            <div class="flex items-center gap-2.5 px-2 py-2 mb-1">
                <span class="avatar w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->isAdmin() ? 'Admin / Petugas' : (auth()->user()->kelas ?? 'Siswa') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full"><x-icon name="logout" class="w-5 h-5 shrink-0" /> Keluar</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white/75 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-4 md:px-6 sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:bg-slate-100 active:scale-90 transition" aria-label="Buka menu navigasi">
                    <x-icon name="menu" class="w-6 h-6" />
                </button>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden lg:flex items-center gap-1.5 text-xs font-medium text-slate-500 bg-slate-100 rounded-full px-3 py-1.5">
                    <x-icon name="clock" class="w-3.5 h-3.5" /> {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <span class="avatar w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm ring-2 ring-white shadow" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6 max-w-6xl w-full mx-auto">
            @yield('content')
        </main>

        <footer class="text-center text-xs text-slate-400 py-5 px-4">
            &copy; {{ date('Y') }} D-Laporr — Sistem Pengaduan Sarana Sekolah &middot; SMK Negeri 5 Surakarta
        </footer>
    </div>
</div>

<div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 md:hidden"></div>

@if (session('success') || session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.300ms
         class="toast fixed top-20 right-4 left-4 sm:left-auto z-50 sm:max-w-sm" role="{{ session('error') ? 'alert' : 'status' }}">
        <div {{ session('success') ? 'data-confetti' : '' }} class="card flex items-start gap-3 p-4 border-l-4 {{ session('error') ? 'border-l-red-500' : 'border-l-emerald-500' }} overflow-hidden">
            @if (session('error'))
                <x-icon name="warning" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" />
            @else
                <x-icon name="check" class="w-5 h-5 shrink-0 mt-0.5 text-emerald-500" />
            @endif
            <p class="text-sm text-slate-700 flex-1">{{ session('error') ?? session('success') }}</p>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition" aria-label="Tutup notifikasi"><x-icon name="close" class="w-4 h-4" /></button>
        </div>
    </div>
@endif
</body>
</html>
