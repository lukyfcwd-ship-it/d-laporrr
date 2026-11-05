<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Wasmer memberi variabel database bernama DB_NAME dan DB_USER, sedangkan Laravel membaca
        // DB_DATABASE dan DB_USERNAME. Jika yang terakhir kosong, pakai nilai dari Wasmer.
        if (! env('DB_DATABASE') && env('DB_NAME')) {
            config(['database.connections.mysql.database' => env('DB_NAME')]);
        }
        if (! env('DB_USERNAME') && env('DB_USER')) {
            config(['database.connections.mysql.username' => env('DB_USER')]);
        }

        // Hosting biasanya memasang HTTPS di depan aplikasi. Paksa semua alamat yang dibuat Laravel
        // (form, tautan, gambar) memakai https:// agar tidak diblokir browser. Aktifkan dengan FORCE_HTTPS=true.
        if (env('FORCE_HTTPS')) {
            URL::forceScheme('https');
        }
    }
}
