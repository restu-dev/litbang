<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ServerCreated;
use App\Models\RegistrasiAwal;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            // unit
            $id_unit =  Auth::user()->unit->id;
            $kode_unit =  Auth::user()->unit->kode_unit;
            $name_unit =  Auth::user()->unit->alias_unit;
            // jenjang
            $id_jenjang =  Auth::user()->jenjang->id;
            $kode_jenjang =  Auth::user()->jenjang->kode_jenjang;
            $name_jenjang =  Auth::user()->jenjang->alias_jenjang;
            // user
            $name =  Auth::user()->name;

            return response()->json([
                'status' => 'Sukses',
                'data' => [
                    'id_unit' => $id_unit,
                    'kode_unit' => $kode_unit,
                    'name_unit' => $name_unit,
                    'id_jenjang' => $id_jenjang,
                    'kode_jenjang' => $kode_jenjang,
                    'name_jenjang' => $name_jenjang,
                    'name' => $name
                ]
            ], 200);
        }else{
            return response()->json([
                'status'  => 'Gagal',
                'message' => 'Login gagal'
            ], 400);
        }

    }

    // getDataRegistrasiAwal
    public function getDataRegistrasiAwal()
    {
        $data = RegistrasiAwal::all();
        
        return response()->json([
            'status' => 'Sukses',
            'data' => $data
        ], 200);
    }

    public function addRegistrasiAwal(Request $request)
    {
        if($request->kode_pendaftar==""){
            return response()->json([
                'status'  => 'Gagal',
                'message' => 'Gagal simpan!'
            ], 400);
        }else{
            $tahun_pelajaran = substr($request->kode_pendaftar, 1, -4);

            $registrasi_awal                    = new RegistrasiAwal();
            $registrasi_awal->tahun_pelajaran   = $tahun_pelajaran;
            $registrasi_awal->kode_pendaftar    = $request->kode_pendaftar;
            $registrasi_awal->save();

            ServerCreated::dispatch('registrasi-awal');

            return response()->json([
                'status'  => 'Sukses',
                'message' => 'Berhasil simpan '. $request->kode_pendaftar
            ], 200);
        }

    }
}
