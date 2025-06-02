<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');

        Gate::define('superadmin', function (User $user) {

            $no_pegawai = $user->no_pegawai;
            $nip = DB::table('simpia.Data_Induk_Pegawai')->where('no_pegawai', $no_pegawai)->first()->NIP;

            $id_level = DB::table('wifi.user_level')->where('nip', $nip)->first()->id_level;

            if ($id_level == 1) {
                return true;
            }
        });
    }
}
