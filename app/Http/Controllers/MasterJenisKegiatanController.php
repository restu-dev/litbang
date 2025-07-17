<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterJenisKegiatanController extends Controller
{
    public function index()
    {
        $title = 'Master Jenis Kegiatan';
        $active = 'master-jenis-kegiatan';

        return view('master-jenis-kegiatan.index', compact('title', 'active'));
    }

    public function loadTabelMasterJenisKegiatan()
    {
        $data = MasterJenisKegiatan::get();

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

            DB::table('master_jenis_kegiatan')->insert($data);
        }else{
            // update

            $data['user_updated'] = $nama_pegawai;
            $data['updated_at'] = date('Y-m-d H:i:s');

            MasterJenisKegiatan::where('id', $request->id_master)
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

        // Cek di jurnal_harian
        if (DB::table('jurnal_harian')->where('id_jenis_kegiatan', $id)->exists()) {
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
        $deleted = MasterJenisKegiatan::where('id', $id)->delete();

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
