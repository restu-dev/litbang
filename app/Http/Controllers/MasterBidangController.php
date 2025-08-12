<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterBidangController extends Controller
{
    public function index()
    {
        $title = 'Master Bidang';
        $active = 'master-bidang';

        return view('master-bidang.index', compact('title', 'active'));
    }

    public function loadTabelMasterBidang()
    {
        $data = MasterBidang::get();

        for($i=0;$i<count($data);$i++){
            $id = $data[$i]->id;
            $nama = $data[$i]->nama;
            $warna = $data[$i]->warna;

            if($warna==""){
                $tampil_warna = "-";
            }else{
                $tampil_warna = "<div style='width:20px; height:20px; background-color:$warna; border:1px solid #000;'></div>";
            }

            $data[$i]->tampil_warna=$tampil_warna;

            $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-nama='$nama' data-warna='$warna' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm edit_master'>
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
            'warna' => $request->warna,
        ];

        $nama_pegawai = session('no_pegawai');
        
        if($request->id_master==NULL){
            // insert

            $data['user_created'] = $nama_pegawai;
            $data['created_at'] = date('Y-m-d H:i:s');

            DB::table('master_bidang')->insert($data);
        }else{
            // update

            $data['user_updated'] = $nama_pegawai;
            $data['updated_at'] = date('Y-m-d H:i:s');

            MasterBidang::where('id', $request->id_master)
                ->update($data);
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

        // Cek di user_level
        if (DB::table('user_level')->where('id_bidang', $id)->exists()) {
            $usedIn[] = 'user_level';
        }

        // Cek di jurnal_harian
        if (DB::table('jurnal_harian')->where('id_bidang', $id)->exists()) {
            $usedIn[] = 'jurnal_harian';
        }

        // Jika ada yang pakai, gagalkan penghapusan
        if (!empty($usedIn)) {

            $hasil = array(
                'sukses' => 'T',
                'pesan' => 'Gagal dihapus: Data digunakan di tabel: ' . implode(', ', $usedIn)
            );

            return $hasil;
        }

        // Hapus jika aman
        $deleted = MasterBidang::where('id', $id)->delete();

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
