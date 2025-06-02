<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function jumlahLevel($id_level)
     {
        $data = DB::select("SELECT COUNT(id) AS jumlah 
                            FROM wifi.user_level
                            WHERE id_level='$id_level'");

        $jumlah = "0";
        if (!empty($data)) {
            $jumlah = $data[0]->jumlah;
        }


        return $jumlah;
     }

    public function index()
    {
        $title = 'User';
        $active = 'user';
        $data_level = Level::get();
        for($i=0;$i<count($data_level);$i++){
            $id_level = $data_level[$i]->id;
            $data_level[$i]->jumlah = $this->jumlahLevel($id_level);
        }

        $data_bidang = DB::select("SELECT * FROM simpia.Bidang_Isct");

        return view('user.index', compact('title', 'active','data_level', 'data_bidang'));
    }

    public function namaStruktur($idstruktur)
    {
        $data = DB::select("SELECT * FROM simpia.Struktur_Organisasi
                            WHERE id_struktur='$idstruktur'");

        $struktur="";
        if(!empty($data)){
            $struktur=$data[0]->nama_struktur;
        }

        return $struktur;
    }

    public function namaLevel($nip)
    {
        $data = DB::select("SELECT a.nip,a.id_level,b.name AS nama_level
                FROM wifi.user_level a
                LEFT JOIN wifi.level b ON a.id_level=b.id
                WHERE a.nip='$nip'");


        $id_level = "";
        $nama_level = "Belum memiliki akses";
        if (!empty($data)) {
            $id_level = $data[0]->id_level;
            $nama_level = $data[0]->nama_level;
        }

        $hasil['id_level'] = $id_level;
        $hasil['nama_level'] = empty($nama_level) ? "Belum memiliki akses" : $nama_level;

        return $hasil;
    }

    public function namaBidang($nip)
    {
        $data = DB::select("SELECT a.nip,a.id_level,b.nama AS nama_bidang_isct
                FROM wifi.user_level a
                LEFT JOIN simpia.Bidang_Isct b ON a.id_bidang_isct=b.id
                WHERE a.nip='$nip'");

        $id_bidang_isct = "";
        $nama_bidang_isct = "Bidang belum disetting";

        if (!empty($data)) {
            $id_bidang_isct = $data[0]->id_bidang_isct??"";
            $nama_bidang_isct = $data[0]->nama_bidang_isct?? "Bidang belum disetting";
        }

        $hasil['id_bidang_isct'] = $id_bidang_isct;
        $hasil['nama_bidang_isct'] = empty($nama_bidang_isct) ? "Bidang belum disetting" : $nama_bidang_isct;

        return $hasil;
    }

    public function loadTabelUser(Request $request)
    {
        $level = $request->level;
        $Flevel = "";
        if (!empty($level)) {
            $Flevel = "AND ul.id_level='$level'";
        }

        $results = DB::select("SELECT p.no_pegawai AS id_pegawai, 
                                p.NIP,
                                nama_pegawai, 
                                ur.user,
                                ur.status_ajukan_user,
                                ur.password2,
                                k.*,
                                ul.id_level,
                                ul.last_login AS last_login_rt
                                FROM simpia.Data_Induk_Pegawai p
                                LEFT JOIN (
                                    SELECT nama_jabatan, 
                                        nama_jenjang, 
                                        nama_unit, p.* 
                                    FROM simpia.Penempatan_Kerja_Pegawai p
                                    LEFT JOIN simpia.Jabatan j ON p.kode_jabatan=j.kode_jabatan
                                    LEFT JOIN simpia.Jenjang j2 ON p.kode_jenjang=j2.kode_jenjang
                                    LEFT JOIN simpia.Unit u ON p.kode_unit=u.kode_unit
                                    WHERE resign IS NULL OR resign=''
                                ) AS k ON p.no_pegawai=k.no_pegawai
                                LEFT JOIN simpia.users ur ON ur.no_pegawai=p.no_pegawai
                                LEFT JOIN wifi.user_level ul ON p.NIP=ul.nip
                                WHERE tanggal_penempatan LIKE (SELECT MAX(tanggal_penempatan) FROM simpia.Penempatan_Kerja_Pegawai WHERE no_pegawai=p.no_pegawai)
                                AND  ur.status_ajukan_user='YA'
                                {$Flevel}
                                -- AND p.NIP='121.06.390'
                                GROUP BY id_pegawai,p.NIP,nama_pegawai,p.no_identitas");

        for ($i = 0; $i < count($results); $i++) {

            $nip = $results[$i]->NIP;
            $idpegawai = $results[$i]->id_pegawai;
            $id_struktur = $results[$i]->id_struktur;

            $nama_struktur = $this->namaStruktur($id_struktur);
            $results[$i]->nama_struktur = $nama_struktur;

            $level = $this->namaLevel($nip);
            $nama_level = $level["nama_level"];
            $results[$i]->nama_level = $nama_level;

            $bidang = $this->namaBidang($nip);
            $nama_bidang_isct = $bidang["nama_bidang_isct"];
            

            if ($nama_level == "Belum memiliki akses") {
                $fa = "btn-danger";
                $detail_aksei = "<a data-nip='$nip' data-idpegawai='$idpegawai' data-level='$nama_level' class='edit_akses dropdown-item' href='javascript: void(0)'>Add Akses</a>";
            } else {
                $fa = "btn-success";
                $detail_aksei = "<a data-nip='$nip' data-idpegawai='$idpegawai' data-level='$nama_level' class='edit_akses dropdown-item' href='javascript: void(0)'>Edit Akses</a>
                                 <a data-nip='$nip' data-idpegawai='$idpegawai' data-bidang='$nama_bidang_isct' class='edit_bidang_isct dropdown-item' href='javascript: void(0)'>Add Bidang</a>
                                 <a data-nip='$nip' data-idpegawai='$idpegawai' class='hapus_akses dropdown-item' href='javascript: void(0)'>Hapus Akses</a>";
            }

            $results[$i]->aksi = "<div class='btn-group'>
                            <button type='button' class='btn $fa btn-flat'>Aksi</button>
                            <button type='button' class='btn $fa btn-flat dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                                <span class='sr-only'>Toggle Dropdown</span>
                            </button>

                            <div class='dropdown-menu' role='menu'>
                              $detail_aksei
                            </div>
                        </div>";
        }

        return $results;
    }

    public function hapusAkses(Request $request)
    {
        $nip = $request->nip;

        UserLevel::where('nip',$nip)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Hapus Akses!",
        ]);
    }

    public function simpanLevelUser(Request $request)
    {
        $idpegawai = $request->idpegawai;
        $idlevel = $request->idlevel;

        $data = UserLevel::where('nip', $idpegawai);

        if (empty($data->count())) {
            // insert
            UserLevel::insert([
                'nip' => $idpegawai,
                'id_level' => $idlevel
            ]);
        } else {
            if (empty($idlevel)) {
                // jika level kosong maka hapus data
                $data->delete();
            } else {
                // ada data => update
                $data->update([
                    'id_level' => $idlevel
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil Update Level User..",
        ]);
    }

    public function simpanBidangUser(Request $request)
    {
        $idpegawai = $request->idpegawai;
        $idbidang = $request->idbidang;

        $data = UserLevel::where('nip', $idpegawai);

        // ada data => update
        $data->update([
            'id_bidang_isct' => $idbidang
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Berhasil Update Level User..",
        ]);
    }

}
