@props(['user', 'class' => 'w-9 h-9 text-sm'])
{{-- Inisial selalu ada di belakang; foto profil menutupinya. Jika foto gagal dimuat, foto dibuang dan inisial muncul lagi. --}}
<span class="avatar relative inline-flex items-center justify-center rounded-full overflow-hidden font-bold font-display shrink-0 {{ $class }}">
    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
    @if ($user->fotoUrl())
        <img src="{{ $user->fotoUrl() }}" alt="Foto profil {{ $user->name }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
    @endif
</span>
