<?php

namespace App\Http\Controllers;

use App\Models\MasterMappingUserAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterMappingUserAgendaController extends Controller
{
    public function index()
    {
        $title = 'Master Mapping User Agenda';
        $active = 'master-mapping-user-agenda';

        return view('master-mapping-user-agenda.index', compact('title', 'active'));
    }

    public function getUserByIdlevel($id_user_level)
    {

        if (!empty($id_user_level)) {

            $bawahan = DB::table('user_level')
                ->where('id', $id_user_level)
                ->first();

            $yt_civitas = $bawahan->yt_civitas;

            if ($yt_civitas == "Y") {
                // get pegawai by nip
                $nip = $bawahan->nip;
                $bawahan = DB::table('simpia.Data_Induk_Pegawai')->where('NIP', $nip)->first()->nama_pegawai;
            } else {
                $bawahan = $bawahan->nip . " (Non Civitas)";
            }

            return $bawahan;
        }
    }

    public function loadTabelMasterMappingUserAgenda()
    {
        $data = MasterMappingUserAgenda::get();

        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;
            $id_user_level = $data[$i]->id_user_level;
            $jenis = $data[$i]->jenis;

            $nama = $this->getUserByIdlevel($id_user_level);

            $data[$i]->nama = $nama;

            $aksi = "<div class='btn-group'>

                      <button data-id='$id' data-level='$id_user_level' data-jenis='$jenis' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm edit_master'>
                       <i class='fas fa-edit'></i>
                      </button>

                      <button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn btn-danger btn-sm hapus_master'>
                        <i class='fa fa-trash'></i>
                      </button>

                     </div>";

            $data[$i]->aksi = $aksi;
        }

        return $data;
    }

    public function store(Request $request)
    {

        $data = [
            'id_user_level' => $request->nama_master,
            'jenis' => $request->jenis,
        ];

        // cek sudah ada user
        $cek = MasterMappingUserAgenda::where('id_user_level', $request->nama_master)->count();

        if ($cek > 0) {
            if ($request->id_master == NULL) {
                return response()->json([
                    'success' => 'T',
                    'status' => 'warning',
                    'message' => "User sudah ada!",
                ]);
            }
        }

        $nama_pegawai = session('no_pegawai');

        if ($request->id_master == NULL) {
            // insert

            $data['user_created'] = $nama_pegawai;
            $data['created_at'] = date('Y-m-d H:i:s');

            DB::table('mapping_user_agenda')->insert($data);
        } else {
            // update

            $data['user_updated'] = $nama_pegawai;
            $data['updated_at'] = date('Y-m-d H:i:s');

            MasterMappingUserAgenda::where('id', $request->id_master)
                ->update($data);
        }

        return response()->json([
            'success' => 'Y',
            'status' => 'success',
            'message' => "Data berhasil disimpan..",
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        // Hapus jika aman
        $deleted = MasterMappingUserAgenda::where('id', $id)->delete();

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
