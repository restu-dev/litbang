<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CekMenuAkses
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
        $results = DB::select("SELECT a.nama_pegawai, a.NIP, b.id_level
                                FROM simpia.Data_Induk_Pegawai a
                                LEFT JOIN wifi.user_level b ON a.NIP=b.nip
                                WHERE no_pegawai='$no_pegawai'");

        $id_level = $results[0]->id_level;
        
        $url =  $request->route()->uri(2);

        $data = DB::select("SELECT m.id AS id_main,a.id_level,a.id_menu,a.yt_add,a.yt_edit,
                                a.yt_del,a.yt_print,m.id,m.url,m.nama AS nama_menu,m.punya_sub,m.icon,l.name AS nama_level 
                            FROM akses a
                            LEFT OUTER JOIN menu m ON m.id=a.id_menu 
                            LEFT OUTER JOIN level l ON l.id=a.id_level
                            WHERE a.yt_tampil='Y' 
                            AND a.id_level='$id_level'
                            AND m.url='$url'
                            ORDER BY m.urut_parent ASC");

        $ada = count($data);

        if($ada>0){
            // set session
            foreach($data as $d){
                session()->put('yt_add', $d->yt_add);
                session()->put('yt_edit', $d->yt_edit);
                session()->put('yt_del', $d->yt_del);
                session()->put('yt_print', $d->yt_print);
                session()->put('url_aktif', $d->url);
            }

            // lanjut
            return $next($request);
        }else{
            return redirect('/403');
        }
    }
}
