<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $title = 'Level';
        $active = 'level';

        return view('level.index', compact('title', 'active'));
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

        if($request->id_level==NULL){
            // insert
            Level::create($data);
        }else{
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
