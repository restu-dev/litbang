<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserNonCivitasController extends Controller
{
    public function index()
    {
        $title = 'User Non Civitas';
        $active = 'user-non-civitas';

        return view('user-non-civitas.index', compact('title', 'active'));
    }

    public function loadTabelUserNonCivitas()
    {
        $data = DB::select("SELECT a.*,
                                b.name AS nama_level 
                            FROM user_level a
                            LEFT JOIN level b ON b.id=a.id_level
                            WHERE a.yt_civitas='T'");

        for($i=0;$i<count($data);$i++){
            $id         = $data[$i]->id;
            $nama       = $data[$i]->nip;
            $id_level   = $data[$i]->id_level;

            $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-nama='$nama' data-level='$id_level' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm edit_master'>
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
            'nip' => $request->nama_user,
            'id_level' => $request->level,
            'yt_civitas' => 'T'
        ];
        
        if($request->id==NULL){
            // insert
            $data['created_at'] = date('Y-m-d H:i:s');

            UserLevel::insert($data);
        }else{
            // update
            $data['updated_at'] = date('Y-m-d H:i:s');

            UserLevel::where('id', $request->id)->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Insert!",
        ]);
    }

    public function destroy(Request $request)
    {
        $usedIn = [];
        $id = $request->id;

        /*
        // Cek di user_level
        if (DB::table('user_level')->where('id_bidang', $id)->exists()) {
            $usedIn[] = 'user_level';
        }

        // Cek di jurnal_harian
        if (DB::table('jurnal_harian')->where('id_bidang', $id)->exists()) {
            $usedIn[] = 'jurnal_harian';
        }
        */

        // Jika ada yang pakai, gagalkan penghapusan
        if (!empty($usedIn)) {

            $hasil = array(
                'sukses' => 'T',
                'pesan' => 'Gagal dihapus: Data digunakan di tabel: ' . implode(', ', $usedIn)
            );

            return $hasil;
        }

        // Hapus jika aman
        $deleted = UserLevel::where('id', $id)->delete();

        if ($deleted) {
            $hasil = array(
                'sukses' => 'Y',
                'pesan' => 'Data berhasil dihapus'
            );

            return $hasil;
          
        } else {
            $hasil = array(
                'sukses' => 'T',
                'pesan' => 'Data tidak ditemukan'
            );

            return $hasil;
        }

    }
}
