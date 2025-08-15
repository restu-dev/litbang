<?php

namespace App\Http\Controllers;

use App\Models\MasterTahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterTahunPelajaranController extends Controller
{
    public function index()
    {
        $title = 'Master Tahun Pelajaran';
        $active = 'master-tahun-pelajaran';

        return view('master-tahun-pelajaran.index', compact('title', 'active'));
    }

    public function loadTabelMasterTahunPelajaran()
    {
        $data = MasterTahunPelajaran::get();

        for($i=0;$i<count($data);$i++){
            
            $id = $data[$i]->id;
            $nama = $data[$i]->nama;
            $awal = $data[$i]->awal;
            $akhir = $data[$i]->akhir;

            $yt_edit = session('yt_edit');
            $yt_del = session('yt_del');

            $edit="";
            if($yt_edit=="Y"){
                $edit = "<button data-id='$id' data-nama='$nama' data-awal='$awal' data-akhir='$akhir' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm edit_master'>
                            <i class='fas fa-edit'></i>
                         </button>";
            }

            $hapus="";
            if($yt_del=="Y"){
                $hapus = "<button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn btn-danger btn-sm hapus_master'>
                                <i class='fa fa-trash'></i>
                          </button>";
            }

            $aksi = "<div class='btn-group'>
                     $edit
                      $hapus
                     </div>";

            $data[$i]->aksi=$aksi;
        }

        return $data;
    }

    public function store(Request $request)
    {

        $data = [
            'nama' => $request->nama_master,
            'awal' => $request->awal,
            'akhir' => $request->akhir,
        ];

        $nama_pegawai = session('no_pegawai');
        
        if($request->id_master==NULL){
            // insert
            $data['user_created'] = $nama_pegawai;
            $data['created_at'] = date('Y-m-d H:i:s');

            DB::table('master_tahun_pelajaran')->insert($data);
        }else{
            // update
            $data['user_updated'] = $nama_pegawai;
            $data['updated_at'] = date('Y-m-d H:i:s');

            MasterTahunPelajaran::where('id', $request->id_master)
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

        // Cek di program_kerja_tahunan
        if (DB::table('program_kerja_tahunan')->where('id_tahun_pelajaran', $id)->exists()) {
            $usedIn[] = 'program_kerja_tahunan';
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
        $deleted = MasterTahunPelajaran::where('id', $id)->delete();

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
