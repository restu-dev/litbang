<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiAwal;
use Illuminate\Http\Request;

class RegistrasiAwalController extends Controller
{
    public function index()
    {
        $title = 'Registrasi Awal';
        $active = 'registrasi-awal';

        return view('registrasi-awal.index', compact('title', 'active'));
    }

    public function loadTabelRegistrasiAwal()
    {
        return RegistrasiAwal::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required'
        ]);

        $tahun_pelajaran = substr($request->kode_pendaftar, 1, -4);

        $registrasi_awal                    = new RegistrasiAwal();
        $registrasi_awal->tahun_pelajaran   = $tahun_pelajaran;
        $registrasi_awal->kode_pendaftar    = $request->kode_pendaftar;
        $registrasi_awal->save();

        // ServerCreated::dispatch('Scan QR From Mobile');

        return response()->json(['message' => 'data add'], 200);
    }
}
