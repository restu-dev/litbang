<?php

namespace App\Http\Controllers;

use App\Models\Jenjang;
use App\Models\CalonSantri;
use App\Models\Keterangan;
use Illuminate\Http\Request;
use App\Models\MasterTahunAjar;
use Illuminate\Support\Facades\Auth;

class KeteranganController extends Controller
{
    public function index()
    {
        $title = 'Keterangan';
        $active = 'keterangan';

        return view('keterangan.index', compact('title', 'active'));
    }

    public function loadTableKeterangan ()
    {
        $data = Keterangan::get();
        return $data;
    }

    public function store(Request $request)
    {
        $data = [
            'keterangan' => $request->keterangan,
        ];

        if ($request->id_keterangan == NULL) {
            // insert
            Keterangan::create($data);
        } else {
            // update
            Keterangan::where('id', $request->id_keterangan)
                ->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
        ]);
    }

    public function destroy(Request $request)
    {
        Keterangan::destroy($request->id);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }

}
