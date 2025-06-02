<?php

namespace App\Http\Controllers;

use \PDF;
use App\Models\Bidang;
use App\Models\UserWifi;
use App\Models\Keterangan;
use App\Models\CalonSantri;
use Illuminate\Http\Request;
use App\Models\MasterTahunAjar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserWifiController extends Controller
{
    public function index()
    {
        $title = 'User Wifi';
        $active = 'user_wifi';
        $bidang = Bidang::get();

        return view('user_wifi.index', compact('title', 'active', 'bidang'));
    }

    public function loadTableUserWifi(Request $request)
    {
        $status = $request->status;
        $bidang = $request->bidang;
        $unit = $request->unit;

        $Fstatus = '';
        if ($status != NULL) {
            $Fstatus = "AND a.status='$status'";
        }

        $Fbidang = '';
        if ($bidang != NULL) {
            $Fbidang = "AND a.bidang='$bidang'";
        }

        $Funit = '';
        if ($unit != NULL) {
            $Funit = "AND (SELECT sb.kode_unit 
                                FROM simpia.Penempatan_Kerja_Pegawai sa
                                LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                                WHERE sa.no_pegawai=b.no_pegawai
                                ORDER BY sa.tanggal_penempatan DESC
                                LIMIT 1)='$unit'";
        }

        $result = DB::select("SELECT a.*,b.hp, b.jenis_kelamin,c.nama AS nama_bidang,
                            (
                                SELECT sb.nama_struktur 
                                FROM simpia.Penempatan_Kerja_Pegawai sa
                                LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                                WHERE sa.no_pegawai=b.no_pegawai
                                ORDER BY sa.tanggal_penempatan DESC
                                LIMIT 1
                            ) AS jabatan,
                            (
                                SELECT sb.kode_unit 
                                FROM simpia.Penempatan_Kerja_Pegawai sa
                                LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                                WHERE sa.no_pegawai=b.no_pegawai
                                ORDER BY sa.tanggal_penempatan DESC
                                LIMIT 1
                            ) AS unit
                            FROM wifi.user_wifi a
                            LEFT JOIN simpia.Data_Induk_Pegawai b ON a.nip=b.NIP
                            LEFT JOIN simpia.Bidang_Isct c ON a.bidang=c.id
                            WHERE a.nip<>''
                            AND a.yt_hapus='T'
                            $Fstatus
                            $Fbidang
                            $Funit
                            ");

        for ($i = 0; $i < count($result); $i++) {
            $id = $result[$i]->id;
            $status = $result[$i]->status;
            $kode_unit = $result[$i]->unit;

            $nama = $result[$i]->nama;
            $user = $result[$i]->user;
            $bidang = $result[$i]->bidang;
            $password = $result[$i]->password;
            $keperluan = $result[$i]->keperluan;

            if ($kode_unit == '001') {
                $result[$i]->unit = 'PUTRA';
            } else {
                $result[$i]->unit = 'PUTRI';
            }

            $data_pass = $result[$i]->password;
            $pass = "<div class='row'>
                        <div class='col'>
                            <input size='6' id='data_pass_$id' disabled type='password' value='$data_pass'>
                        </div>
                        <div class='col'>
                            <input type='checkbox' onclick='tampilPassword($id)'> Tampil
                        </div>
                     </div>";

            $result[$i]->pass= $pass;
            
            // <a data-id='$id' class='hapus_user_wifi dropdown-item' href='javascript: void(0)'>Hapus</a>

            if ($status == 'AKTIF') {
                $result[$i]->status = "<div class='btn-group'>
                                        <button type='button' class='btn btn-success'>$status</button>
                                        <button type='button' class='btn btn-success dropdown-toggle dropdown-icon' data-toggle='dropdown' aria-expanded='false'>
                                            <span class='sr-only'>Toggle Dropdown</span>
                                        </button>

                                        <div class='dropdown-menu' role='menu' style=''>
                                            <a data-id='$id' data-nama='$nama' data-user='$user' data-bidang='$bidang' data-password='$password' data-keperluan='$keperluan' class='edit_user_wifi dropdown-item' href='javascript: void(0)'>Edit</a>
                                            <a data-id='$id' data-nama='$nama' class='cetak_surat dropdown-item' href='javascript: void(0)'>Cetak Surat</a>
                                            <a data-id='$id' class='kirim_surat dropdown-item' href='javascript: void(0)'>Kirim Surat</a>
                                            <a data-id='$id' class='kirim_user dropdown-item' href='javascript: void(0)'>Kirim User</a>
                                            <div class='dropdown-divider'></div>
                                            <a data-id='$id' class='non_aktif_user_wifi dropdown-item' href='javascript: void(0)'>Non Aktifkan</a>
                                        </div>
                                    </div>";
            } else {
                $result[$i]->status = "<div class='btn-group'>
                                        <button type='button' class='btn btn-danger'>$status</button>
                                        <button type='button' class='btn btn-danger dropdown-toggle dropdown-icon' data-toggle='dropdown' aria-expanded='false'>
                                            <span class='sr-only'>Toggle Dropdown</span>
                                        </button>
                                        <div class='dropdown-menu' role='menu' style=''>
                                            <div class='dropdown-divider'></div>
                                            <a data-id='$id' class='aktif_user_wifi dropdown-item' href='javascript: void(0)'>Aktifkan</a>
                                        </div>
                                    </div>";
            }
        }

        return $result;
    }

    public function loadKodePegawai(Request $req)
    {
        $data = DB::select("SELECT * 
                            FROM simpia.Data_Induk_Pegawai
                            WHERE no_pegawai='$req->no_pegawai'");
        return $data;
    }

    public function getBulanRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function getNoSuratIsct($id_bidang)
    {

        // bulan romawi
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date("d");
        $bulan = date("m");
        $tahun = date("Y");
        $bulan_romawi = $this->getBulanRomawi($bulan);

        // kode bidang
        $row_bid = DB::table('simpia.Bidang_Isct')->where(['id' => $id_bidang])->first();
        $kode_bidang = $row_bid->kode;

        // urut surat
        $count_urut = UserWifi::count();
        $urut = $count_urut+1;

        // kode surat
        // 0001/SPB.01-PIAT/X/2023
        $kode_surat = $urut.'/SPm.' . $kode_bidang . '-PIAT/' . $bulan_romawi . '/' . $tahun;

        return $kode_surat;
    }

    public function saveUserWifi(Request $request)
    {
        DB::beginTransaction();

        $id = $request->id;

        try {

            $data_add = [
                'nip' => $request->nip,
                'nama' => $request->nama,
                'bidang' => $request->bidang,
                'user' => $request->user,
                'password' => $request->password,
                'keperluan' => $request->keperluan,
                'status' => "AKTIF",
                'aktive_date' => date('Y-m-d')
            ];

            if ($id == NULL) {
                // cek data sudah ada atau belum
                $cek = UserWifi::where('nip', $request->nip)->count();

                if($cek>0){

                    DB::commit();

                    $hasil = array(
                        'sukses' => 'T',
                        'status' => 'error',
                        'message' => "Data Sudah pernah tersimpan dalam sistem!",
                    );

                    return $hasil;

                }else{
                    // insert
                    $kode_surat = $this->getNoSuratIsct($request->bidang);
                    $data_add['no_surat'] = $kode_surat;
                    $data_add['user_created_at'] = session('nama_pegawai');
                    
                    UserWifi::create($data_add);

                    DB::commit();

                    $hasil = array(
                        'sukses' => 'Y',
                        'status' => 'success',
                        'message' => "Berhasil input data..",
                    );

                    return $hasil;
                }

                
            } else {

                $data_edit = [
                    'user' => $request->user,
                    'bidang' => $request->bidang,
                    'password' => $request->password,
                    'keperluan' => $request->keperluan,
                    'user_updated_at' => session('nama_pegawai'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // update
                UserWifi::where('id', $request->id)
                    ->update($data_edit);

                DB::commit();

                $hasil = array(
                    'sukses' => 'Y',
                    'status' => 'success',
                    'message' => "Berhasil update data..",
                );

                return $hasil;
            }

        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'status' => 'success',
                'message' => $pesan,
            );

            return $hasil;
        }
    }

    public function statusUserWifi(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        if ($status == 'nonaktif') {
            UserWifi::where('id', $id)
                ->update([
                    "status" => "NON AKTIF",
                    "non_aktive_date" => date('Y-m-d')

                ]);
        } else {
            UserWifi::where('id', $id)
                ->update([
                    "status" => "AKTIF",
                    "aktive_date" => date('Y-m-d')
                ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Success !",
        ]);
    }

    public function destroyUserWifi(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {

            // update yt_hapus => Y
            UserWifi::where('id',$id)->update(['yt_hapus'=>'Y']);

            DB::commit();

            $hasil = array(
                'sukses' => 'Y',
                'status' => 'success',
                'message' => "Berhasil update data..",
            );

            return $hasil;
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'status' => 'success',
                'message' => $pesan,
            );

            return $hasil;
        }
    }

    public function dataUserWifiById($id)
    {
        $results = DB::select("SELECT a.*,a.nama as nama_pegawai,b.hp, b.jenis_kelamin,c.nama AS nama_bidang,
                    (
                        SELECT sb.nama_struktur 
                        FROM simpia.Penempatan_Kerja_Pegawai sa
                        LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                        WHERE sa.no_pegawai=b.no_pegawai
                        ORDER BY sa.tanggal_penempatan DESC
                        LIMIT 1
                    ) AS jabatan,
                    (
                        SELECT sb.kode_unit 
                        FROM simpia.Penempatan_Kerja_Pegawai sa
                        LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                        WHERE sa.no_pegawai=b.no_pegawai
                        ORDER BY sa.tanggal_penempatan DESC
                        LIMIT 1
                    ) AS unit
                    FROM wifi.user_wifi a
                    LEFT JOIN simpia.Data_Induk_Pegawai b ON a.nip=b.NIP
                    LEFT JOIN simpia.Bidang_Isct c ON a.bidang=c.id
                    WHERE a.nip<>''
                    AND a.id='$id'");

        foreach ($results as $d) {
            $tgl = $d->created_at;
            $tgl_conv = date_format(date_create($tgl), "Y/m/d");

            $data['nip'] = $d->nip;
            $data['nama'] = $d->nama_pegawai;
            $data['jabatan'] = $d->jabatan;
            $data['bidang'] = $d->nama_bidang;
            $data['jabatan'] = $d->jabatan;
            $data['keperluan'] = $d->keperluan;
            $data['tgl'] = $tgl_conv;
            $data['hp'] = $d->hp;
            $data['no_surat'] = $d->no_surat;
            $data['user'] = $d->user;
            $data['password'] = $d->password;
        }

        return $data;
    }

    public function tampilSurat(Request $request)
    {
        $id = $request->id;

        $data_user = $this->dataUserWifiById($id);
        $data_keterangan = Keterangan::get();

        $no_surat = $data_user['no_surat'];

        $pdf = PDF::loadView(
            'user_wifi.partials.surat_pengajuan_user_wifi',
            compact('data_user', 'data_keterangan', 'no_surat')
        )->setPaper('A4', 'portrait');

        return $pdf->stream($no_surat.'.pdf');
    }

    public function tampilSuratDev(Request $request)
    {
        $id = $request->id;

        $data_user = $this->dataUserWifiById($id);
        $data_keterangan = Keterangan::get();

        $no_surat = $data_user['no_surat'];

        $pdf = PDF::loadView(
            'user_wifi.partials.surat_pengajuan_user_wifi_dev',
            compact('data_user', 'data_keterangan')
        )->setPaper('A4', 'portrait');

        return $pdf->stream($no_surat . '.pdf');
    }

    public function kirimSuratPengajuanUserWifi(Request $request)
    {
        $id = $request->id;
        $data = $this->dataUserWifiById($id);
        $this->kirimWaSurat($data,$id);
    }

    public function kirimUserPengajuanUserWifi(Request $request)
    {
        $id = $request->id;
        $data = $this->dataUserWifiById($id);
        $this->kirimWaUser($data, $id);
    }

    // kirim wa Surat
    public function kirimWaSurat($data,$id)
    {
        $no_wa = $data['hp'];
        $nama = $data['nama'];
        $jabatan = $data['jabatan'];
        $bidang = $data['bidang'];
        $keperluan = $data['keperluan'];
        $tgl = $data['tgl'];


        $firstcarakter = substr($no_wa, 0, 1);
        if ($firstcarakter == "0") {
            $depan = ltrim($no_wa, '0');
            $finalno = "62" . $depan;
        } else {
            $finalno = $no_wa;
        }

        $link = "https://jaringan.pesantrenalirsyad.org/tampil-surat/$id";

        $where = ["id" => 3];
        $row = \DB::table("smsd.wa_center")->where($where)->first();
        $api_key = $row->api_key;
        $main_api = $row->main_api_watzap;
        $api_key2 = $row->api_key2;

        $dataSending = array();
        $dataSending["api_key"] = $main_api;
        $dataSending["number_key"] = $api_key2;
        $dataSending["phone_no"] = $finalno;
        $dataSending["message"] = "*Form Permintaan Sambungan Internet*
1. Nama Pemohon: $nama
2. Jabatan : $jabatan
3. bidang : $bidang
*link Download Formulir :* $link";

        // kirim wa
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        //cek sukses kirim
        if ($err) {
            $status = "Gagal";
        } else {
            $status = "Berhasil";
        }

        return;
    }

    public function dataKeterangan()
    {
        $results = Keterangan::get();

        $redaksi = "";
        for($i=0;$i<count($results);$i++){
            $no = $i+1;
            $keterangan = $results[$i]->keterangan;

            $pesan = $no.'. '. $keterangan. ' 
';
            $redaksi .= $pesan;
        }

        return $redaksi;
    }

    // kirim wa Surat
    public function kirimWaUser($data, $id)
    {
        $no_wa = $data['hp'];
        $nama = $data['nama'];
        $jabatan = $data['jabatan'];
        $bidang = $data['bidang'];
        $keperluan = $data['keperluan'];
        $tgl = $data['tgl'];
        $no_surat = $data['no_surat'];
        $user = $data['user'];
        $password = $data['password'];


        $firstcarakter = substr($no_wa, 0, 1);
        if ($firstcarakter == "0") {
            $depan = ltrim($no_wa, '0');
            $finalno = "62" . $depan;
        } else {
            $finalno = $no_wa;
        }

        $keterangan = $this->dataKeterangan();

        $where = ["id" => 3];
        $row = \DB::table("smsd.wa_center")->where($where)->first();
        $api_key = $row->api_key;
        $main_api = $row->main_api_watzap;
        $api_key2 = $row->api_key2;

        $dataSending = array();
        $dataSending["api_key"] = $main_api;
        $dataSending["number_key"] = $api_key2;
        $dataSending["phone_no"] = $finalno;
        $dataSending["message"] = "*Form Permintaan Sambungan Internet*
1. No Surat: $no_surat
2. Nama Pemohon: $nama
3. Jabatan : $jabatan
4. Bidang : $bidang
-------------------
*USER : $user*
*PASSWORD : $password*
-------------------

Ketentuan Pemakaian :
$keterangan
";


        // kirim wa
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        //cek sukses kirim
        if ($err) {
            $status = "Gagal";
        } else {
            $status = "Berhasil";
        }

        return;
    }
}
