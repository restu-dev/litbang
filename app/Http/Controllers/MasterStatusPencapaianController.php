<?php

namespace App\Http\Controllers;

use App\Models\MasterStatusPencapaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterStatusPencapaianController extends Controller
{
    public function index()
    {
        $title = 'Master Jenis Kegiatan';
        $active = 'master-status-pencapaian';

        return view('master-status-pencapaian.index', compact('title', 'active'));
    }

    public function loadTabelMasterStatusPencapaian()
    {
        $data = MasterStatusPencapaian::get();

        for($i=0;$i<count($data);$i++){
            $id = $data[$i]->id;
            $nama = $data[$i]->nama;

            $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-nama='$nama' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm edit_master'>
                       <i class='fas fa-edit'></i>
                      </button>
                      <button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn btn-danger btn-sm hapus_master'>
                        <i class='fa fa-trash'></i>
                      </button>
                     </div>";

            $data[$i]->aksi=$aksi;
        }

        return $data;
    }

    public function store(Request $request)
    {

        $data = [
            'nama' => $request->nama_master,
        ];

        $nama_pegawai = session('no_pegawai');
        
        if($request->id_master==NULL){
            // insert

            $data['user_created'] = $nama_pegawai;
            $data['created_at'] = date('Y-m-d H:i:s');

            DB::table('master_status_pencapaian')->insert($data);
        }else{
            // update

            $data['user_updated'] = $nama_pegawai;
            $data['updated_at'] = date('Y-m-d H:i:s');

            MasterStatusPencapaian::where('id', $request->id_master)
                ->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
        ]);
    }

    public function destroy(Request $request)
    {
       
        MasterStatusPencapaian::destroy($request->id);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }
}
