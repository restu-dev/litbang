<?php

namespace App\Http\Controllers;

use \PDF;
use App\Models\Bidang;
use App\Models\UserAsset;
use App\Models\Keterangan;
use App\Models\CalonSantri;
use Illuminate\Http\Request;
use App\Models\MasterTahunAjar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserAssetController extends Controller
{
    public function index()
    {
        $title = 'User Asset';
        $active = 'user_asset';
        $bidang = Bidang::get();

        return view('user_asset.index', compact('title', 'active', 'bidang'));
    }

    public function dataAssetIt($u, $d, $g, $r, $p, $t, $s)
    {
        $lm = " limit 500 ";
        if (!empty($c)) $lm = "";

        $FU = "";
        if (!empty($u)) $FU = " AND unit_id = '{$u}'";

        $FD = "";
        if (!empty($d)) $FD = " AND divisi_id = '{$d}'";

        $FG = "";
        if (!empty($g)) $FG = " AND gedung_id = '{$g}'";

        $FR = "";
        if (!empty($r)) $FR = " AND ruang_id = '{$r}'";

        $FP = "";
        if (!empty($p)) $FP = " AND pemakai_id = '{$p}'";

        $FT = "";
        if (!empty($t)) $FT = " AND a.tipe_id = '{$t}'";

        $FS = "";
        if (!empty($s)) $FS = " AND a.spec = '{$s}'";

        $results_db = \DB::select("SELECT es.*, 
                                          a.id, a.tipe, a.merk, a.spec, a.no_surat, a.idx, a.file_pembelian, concat(a.tipe, ' ' , a.merk, ' ', a.spec) as nama_asset, 
                                          ep.*
                                        
                                    FROM wifi.user_asset es

                                    LEFT JOIN assets.Eq_Asset a ON a.id=es.id_asset

                                    LEFT JOIN (
                                        SELECT DATE_FORMAT(tanggal_pakai,'%d/%m/%Y') as tanggal_pakai, eq_id
                                        , lembaga_id, nama_lembaga, unit_id, alias_unit AS nama_unit, gedung_id, nama_gedung, ruang_id, nama_ruang
                                        , pemakai_id, nama_pegawai, divisi_id, d.nama as nama_divisi, g.kode_gedung, r.kode_ruang, r.kode_ruang_new
                                        FROM assets.Eq_Posisi p
                                        LEFT JOIN simpia.Lembaga l ON p.lembaga_id=l.kode_lembaga
                                        LEFT JOIN simpia.Unit u ON p.unit_id=u.kode_unit
                                        LEFT JOIN simpia.Gedung g ON p.unit_id=g.kode_unit and p.gedung_id=g.kode_gedung
                                        LEFT JOIN simpia.Ruang r ON p.unit_id=r.kode_unit and p.gedung_id=r.kode_gedung and p.ruang_id=r.kode_ruang
                                        LEFT JOIN simpia.Data_Induk_Pegawai i ON p.pemakai_id=i.no_pegawai
                                        LEFT JOIN simpia.Divisi d ON p.divisi_id=d.id
                                        WHERE p.deleted = 'N' and group_id='A' and moved='N'
                                    ) as ep ON es.id_asset=ep.eq_id

                                    WHERE es.yt_hapus = 'T' 
                                    {$FU}
                                    {$FD}
                                    {$FG}
                                    {$FR}
                                    {$FP}
                                    {$FT}
                                    {$FS}
                                    ORDER BY CAST(es.id_asset AS UNSIGNED) DESC {$lm}");

        return $results_db;
    }

    public function loadTableUserAsset(Request $request)
    {
       
        // $result = DB::select("SELECT * FROM user_asset");

        $result = $this->dataAssetIt('', '', '', '', '', '', '');

        for ($i = 0; $i < count($result); $i++) {
            $id = $result[$i]->id;
            $status = $result[$i]->status;
            $id_asset = $result[$i]->id_asset;
            $keterangan = $result[$i]->keterangan;
            

            if ($status == 'AKTIF') {
                $result[$i]->status = "<div class='btn-group'>
                                        <button type='button' class='btn btn-success'>$status</button>
                                        <button type='button' class='btn btn-success dropdown-toggle dropdown-icon' data-toggle='dropdown' aria-expanded='false'>
                                            <span class='sr-only'>Toggle Dropdown</span>
                                        </button>

                                        <div class='dropdown-menu' role='menu' style=''>
                                            <a data-id='$id' class='hapus_user_asset dropdown-item' href='javascript: void(0)'>Hapus</a>
                                            <a data-id='$id' data-idasset='$id_asset' data-keterangan='$keterangan' class='edit_user_asset dropdown-item' href='javascript: void(0)'>Edit</a>
                                            <div class='dropdown-divider'></div>
                                            <a data-id='$id' class='non_aktif_user_asset dropdown-item' href='javascript: void(0)'>Non Aktifkan</a>
                                        </div>
                                    </div>";
            } else {
                $result[$i]->status = "<div class='btn-group'>
                                        <button type='button' class='btn btn-danger'>$status</button>
                                        <button type='button' class='btn btn-danger dropdown-toggle dropdown-icon' data-toggle='dropdown' aria-expanded='false'>
                                            <span class='sr-only'>Toggle Dropdown</span>
                                        </button>
                                        <div class='dropdown-menu' role='menu' style=''>
                                            <a data-id='$id' class='hapus_user_asset dropdown-item' href='javascript: void(0)'>Hapus</a>
                                            <div class='dropdown-divider'></div>
                                            <a data-id='$id' class='aktif_user_asset dropdown-item' href='javascript: void(0)'>Aktifkan</a>
                                        </div>
                                    </div>";
            }
        }

        return $result;
    }

    public function saveUserAsset(Request $request)
    {
        DB::beginTransaction();

        $id = $request->id;

        try {

            $data_add = [
                'id_asset' => $request->nama_asset,
                'keterangan' => $request->keterangan,
                'status' => "AKTIF",
                'aktive_date' => date('Y-m-d')
            ];

            if ($id == NULL) {
                // cek data sudah ada atau belum
                $cek = UserAsset::where('id_asset', $request->nama_asset)->count();

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
                    $data_add['user_created_at'] = session('nama_pegawai');
                    
                    UserAsset::create($data_add);

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
                    'keterangan' => $request->keterangan,
                    'user_updated_at' => session('nama_pegawai'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // update
                UserAsset::where('id_asset', $request->id)
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

    public function statusUserAsset(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        if ($status == 'nonaktif') {
            UserAsset::where('id_asset', $id)
                ->update([
                    "status" => "NON AKTIF",
                    "non_aktive_date" => date('Y-m-d')
                ]);
        } else {
            UserAsset::where('id_asset', $id)
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

    public function destroyUserAsset(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();

        try {

            // update yt_hapus => Y
            UserAsset::where('id',$id)->update(['yt_hapus'=>'Y']);

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

    public function dataUserAssetById($id)
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

        $data_user = $this->dataUserAssetById($id);
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

        $data_user = $this->dataUserAssetById($id);
        $data_keterangan = Keterangan::get();

        $no_surat = $data_user['no_surat'];

        $pdf = PDF::loadView(
            'user_wifi.partials.surat_pengajuan_user_wifi_dev',
            compact('data_user', 'data_keterangan')
        )->setPaper('A4', 'portrait');

        return $pdf->stream($no_surat . '.pdf');
    }

    public function kirimSuratPengajuanUserAsset(Request $request)
    {
        $id = $request->id;
        $data = $this->dataUserAssetById($id);
        $this->kirimWaSurat($data,$id);
    }

    public function kirimUserPengajuanUserAsset(Request $request)
    {
        $id = $request->id;
        $data = $this->dataUserAssetById($id);
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
