<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $title = 'Menu';
        $active = 'menu';

        return view('menu.index', compact('title', 'active'));
    }

    public function loadTabelMenuHeader()
    {
        $results = DB::select("SELECT * FROM wifi.menu
                            WHERE yt_header='Y'
                            ORDER BY urut_header + 0 ASC");

        return $results;
    }

    public function loadTabelMenuParent(Request $request)
    {   
        $id_header = $request->id_header;

        $results = DB::select("SELECT * FROM wifi.menu
                            WHERE yt_parent='Y'
                            AND id_header='$id_header'
                            ORDER BY urut_parent + 0 ASC");
 
        return $results;
    }

    public function storeMenuHeader(Request $request)
    {
        $id = $request->id;
        $data = [
            'nama' => $request->nama,
            'url' => $request->url,
            'punya_sub' => $request->sub,
            'yt_header' => 'Y',
            'icon' => $request->icon,
            'urut_header' => $request->urut_header,
        ];

        if($id==NULL){
            // insert
            Menu::create($data);
        }else{
            // update
            Menu::where('id', $id)
                ->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
        ]);
    }

    public function storeMenuParent(Request $request)
    {
        $id_parent = $request->id_parent;

        $data = [
            'id_header' => $request->id_header,
            'nama' => $request->nama,
            'url' => $request->url,
            'yt_parent' => 'Y',
            'icon' => 'fa-circle',
            'urut_parent' => $request->urut_parent,
        ];

        if($id_parent==NULL){
            // insert
            Menu::create($data);
        }else{
            // update
            Menu::where('id', $id_parent)
                ->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
            'id_header' => $request->id_header,
            'nama' =>$request->nama,
        ]);
    }

    public function detailMenuHeader(Request $request)
    {
        $id_header = $request->id_header;

        $menu = Menu::where(["id"=>$id_header])->first();
        return $menu;
    }

    public function destroyParent(Request $request)
    {
        Menu::destroy($request->id);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }
}
