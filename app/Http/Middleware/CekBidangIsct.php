<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CekBidangIsct
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $no_pegawai = Auth::user()->no_pegawai;

        // get id_level
        $results = DB::select("SELECT a.nama_pegawai, a.NIP, b.id_bidang_isct
                                FROM simpia.Data_Induk_Pegawai a
                                LEFT JOIN wifi.user_level b ON a.NIP=b.nip
                                WHERE no_pegawai='$no_pegawai'");

        $id_bidang_isct = $results[0]->id_bidang_isct??"";

        if($id_bidang_isct!=""){
            // lanjut
            return $next($request);
        }else{
            return redirect('/403');
        }
    }
}
