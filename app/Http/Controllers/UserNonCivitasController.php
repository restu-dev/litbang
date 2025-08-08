<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        for ($i = 0; $i < count($data); $i++) {
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
                       <button data-id='$id' data-nama='$nama' data-toggle='tooltip' data-placement='top' title='Mapping' class='btn btn-primary btn-sm mapping_bawahan'>
                        <i class='fa fa-gear'></i>
                      </button>
                     </div>";

            $data[$i]->aksi = $aksi;
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

        if ($request->id == NULL) {
            // insert
            $data['created_at'] = date('Y-m-d H:i:s');

            // cek unik nip
            $cek = UserLevel::where('nip', $request->nama_user)->count();

            // jika nip sudah dipake gagal
            if ($cek > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Gagal, user " . $request->nama_user . " sudah terpake!",
                ]);
            }

            $pass = $request->pass_user;

            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            $data['pass_user'] = $hashed_pass;

            UserLevel::insert($data);
        } else {
            // update
            $data['updated_at'] = date('Y-m-d H:i:s');

            // cek pass -> ada / tidak
            $pass = $request->pass_user;

            if (!empty($pass)) {
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

                $data['pass_user'] = $hashed_pass;
            }

            UserLevel::where('id', $request->id)->update($data);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil simpan data!",
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

    public function tampilMappingBawahan(Request $request)
    {
        $id = $request->id;

        $data = UserLevel::where('id', $id)->first();

        return view('user-non-civitas.mapping-bawahan', compact('id', 'data'));
    }

    public function getBawahan($id_user_level_bawahan)
    {

        if (!empty($id_user_level_bawahan)) {
            $bawahan = DB::table('user_level')
                ->where('id', $id_user_level_bawahan)
                ->first();

            $yt_civitas = $bawahan->yt_civitas;

            if ($yt_civitas == "Y") {
                // get pegawai by nip
                $nip = $bawahan->nip;
                $bawahan = DB::table('simpia.Data_Induk_Pegawai')->where('NIP', $nip)->first()->nama_pegawai;
            } else {
                $bawahan->nip = $bawahan->nip . " (Non Civitas)";
            }

            return $bawahan;
        }
    }

    public function tabelMappingBawahan(Request $request)
    {
        $id_user_level = $request->id_user_level;

        $results_db = DB::select("SELECT *, a.id AS id_mapping_bawahan_non_civitas
                                FROM mapping_bawahan_non_civitas a
                                LEFT JOIN user_level b ON a.id_user_level=b.id
                                WHERE b.id='$id_user_level'");

        for ($i = 0; $i < count($results_db); $i++) {

            $id = $results_db[$i]->id_mapping_bawahan_non_civitas;
            $id_user_level_bawahan = $results_db[$i]->id_user_level_bawahan;

            $bawahan = $this->getBawahan($id_user_level_bawahan);
            $results_db[$i]->bawahan = $bawahan;
            
            $aksi = "<div class='btn-group'>

                        <button data-id='$id' type='button' class='hapus_data_bawahan btn btn-danger'>
                          <i class='fas fa-trash'></i>
                        </button>
                        
                      </div>";

            $results_db[$i]->aksi = $aksi;
        }

        return $results_db;
    }

    public function saveDataBawahan(Request $request)
    {

        $id_user_level              = $request->id_user_level;
        $id_user_level_bawahan      = $request->id_user_level_bawahan;

        DB::beginTransaction();

        try {

            if ($id_user_level_bawahan == "") {

                $hasil = array(
                    'sukses' => 'T',
                    'status' => 'warning',
                    'message' => "Gagal, Bawahan tidak boleh kosong!",
                );

                DB::commit();

                return $hasil;
            }

            $data_add = [
                'id_user_level' => $id_user_level,
                'id_user_level_bawahan' => $id_user_level_bawahan,
                'user_created' => session('no_pegawai'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // cek duplikat data
            $cek =   DB::table("mapping_bawahan_non_civitas")->where([
                'id_user_level' => $id_user_level,
                'id_user_level_bawahan' => $id_user_level_bawahan,
            ])->count();

            if ($cek > 0) {
                $hasil = array(
                    'sukses' => 'T',
                    'status' => 'warning',
                    'message' => "Gagal, Data sudah terinput!",
                );

                DB::commit();

                return $hasil;
            }

            // insert
            DB::table("mapping_bawahan_non_civitas")->insert($data_add);

            DB::commit();

            $hasil = array(
                'sukses' => 'Y',
                'status' => 'success',
                'message' => "Berhasil input data..",
            );

            return $hasil;
        } catch (\Exception $e) {
            DB::rollback();

            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'status' => 'warning',
                'message' => $pesan,
            );

            return $hasil;
        }
    }

    public function hapusDataBawahan(Request $request)
    {
        $id = $request->id;

        // Hapus dari database
        $deleted = DB::table('mapping_bawahan_non_civitas')->where('id', $id)->delete();

        if ($deleted) {
            $hasil = array(
                'sukses' => 'Y',
                'message' => 'Data berhasil dihapus'
            );

            return $hasil;
        } else {
            $hasil = array(
                'sukses' => 'T',
                'message' => 'Data tidak ditemukan'
            );

            return $hasil;
        }
    }
}
