<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AksesController extends Controller
{
    public function index()
    {
        $title = 'Akses';
        $active = 'akses';
        $level = Level::get();

        return view('akses.index', compact('title', 'active', 'level'));
    }

    public function tampilLevelAkses(Request $request)
    {
        $id_level = $request->id_level;
        $name_level = $request->name_level;
        return view('akses.partials.tampil_akses', compact('id_level', 'name_level'));
    }

    public function simpanAksesMenu(Request $req)
    {
        parse_str($req->data, $output);

        $idlevel = $output['idlevel'];
        $menu    = DB::select("SELECT * FROM menu");

        DB::table('akses')->where(['id_level' => $idlevel])->delete();

        foreach ($menu as $m) {
            $idmenu    = $m->id;
            $menu     = $m->nama;

            $akses    = isset($output["akses_$idmenu"]) ? "Y" : "T";
            $add      = isset($output["add_$idmenu"]) ? "Y" : "T";
            $edit     = isset($output["edit_$idmenu"]) ? "Y" : "T";
            $delete   = isset($output["delete_$idmenu"]) ? "Y" : "T";
            $print    = isset($output["print_$idmenu"]) ? "Y" : "T";

            DB::table('akses')->insert(
                [
                    'id_level' => $idlevel,
                    'id_menu' => $idmenu,
                    'yt_tampil' => $akses,
                    'yt_add' => $add,
                    'yt_edit' => $edit,
                    'yt_del' => $delete,
                    'yt_print' => $print,
                ]
            );
        }
    }

    public function loadTabelLevel()
    {
        return Level::get();
    }

    public function store(Request $request)
    {

        $data = [
            'name' => $request->nama_level,
        ];

        if ($request->id_level == NULL) {
            // insert
            Level::create($data);
        } else {
            // update
            Level::where('id', $request->id_level)
                ->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
        ]);
    }

    public function destroy(Request $request)
    {
        // var_dump('masuk sini');

        Level::destroy($request->id);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }
}
