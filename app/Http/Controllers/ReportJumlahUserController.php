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

class ReportJumlahUserController extends Controller
{
    public function index()
    {
        $title = 'Report Data User';
        $active = 'report-data-user';
        $bidang = Bidang::get();

        return view('report_jumlah_user.index', compact('title', 'active', 'bidang'));
    }

    public function loadTampilDataReportJumlahUser(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        // user
        $data['aktif_user'] = $this->dataUser('AKTIF', $tahun, $bulan);
        $data['daftar_user'] = $this->dataUser('DAFTAR', $tahun, $bulan);
        $data['non_aktif_user'] = $this->dataUser('NON AKTIF', $tahun, $bulan);

        // aset
        $data['aktif_asset'] = $this->dataAsset('AKTIF', $tahun, $bulan);
        $data['daftar_asset'] = $this->dataAsset('DAFTAR', $tahun, $bulan);
        $data['non_aktif_asset'] = $this->dataAsset('NON AKTIF', $tahun, $bulan);

        return view('report_jumlah_user.data',['data'=>$data,'tahun'=>$tahun, 'bulan'=>$bulan]);
    }

    public function dataUser($status,$tahun,$bulan)
    {
        $status = $status;
        $tahun = $tahun;
        $bulan = $bulan;

        $where = "";

        if($status == "AKTIF"){
            $where = "AND status='AKTIF'";
        }
        
        if($status == "DAFTAR"){
            $where = "AND status='AKTIF'
                      AND YEAR(aktive_date)='$tahun'
                      AND MONTH(aktive_date)='$bulan'";
        }
        
        if($status== 'NON AKTIF'){
            $where = "AND status='NON AKTIF'
                      AND YEAR(non_aktive_date)='$tahun'
                      AND MONTH(non_aktive_date)='$bulan'";
        }

        $result = DB::select("SELECT * 
                                FROM user_wifi
                                WHERE yt_hapus='T'
                                $where
                            ");

        return count($result);
    }

    public function dataAsset($status, $tahun, $bulan)
    {
        $status = $status;
        $tahun = $tahun;
        $bulan = $bulan;

        if ($status == "AKTIF") {
            $where = "AND status='AKTIF'";
        }

        if ($status == "DAFTAR") {
            $where = "AND status='AKTIF'
                      AND YEAR(aktive_date)='$tahun'
                      AND MONTH(aktive_date)='$bulan'";
        }
        
        if ($status == "NON AKTIF") {
            $where = "AND status='NON AKTIF'
                      AND YEAR(non_aktive_date)='$tahun'
                      AND MONTH(non_aktive_date)='$bulan'";
        }

        $result = DB::select("SELECT * 
                                FROM user_asset
                                WHERE yt_hapus='T'
                                $where");

        return count($result);
    }

    public function loadTableDetailDataUser(Request $request)
    {
        $status = $request->status;
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $where = "";

        if ($status == "AKTIF") {
            $where = "AND status='AKTIF'";
        }

        if ($status == "DAFTAR") {
            $where = "AND status='AKTIF'
                      AND YEAR(aktive_date)='$tahun'
                      AND MONTH(aktive_date)='$bulan'";
        }

        if ($status == 'NON AKTIF') {
            $where = "AND status='NON AKTIF'
                      AND YEAR(non_aktive_date)='$tahun'
                      AND MONTH(non_aktive_date)='$bulan'";
        }

        $result = DB::select("SELECT * FROM (SELECT a.nip AS kode,a.status ,a.nama,
                                                (
                                                    SELECT sb.kode_unit 
                                                    FROM simpia.Penempatan_Kerja_Pegawai sa
                                                    LEFT JOIN simpia.Struktur_Organisasi sb ON sa.id_struktur=sb.id_struktur
                                                    WHERE sa.no_pegawai=b.no_pegawai
                                                    ORDER BY sa.tanggal_penempatan DESC
                                                    LIMIT 1
                                                ) AS unit,
                                                a.keperluan AS keterangan, aktive_date, non_aktive_date, a.yt_hapus
                                                FROM wifi.user_wifi a
                                                LEFT JOIN simpia.Data_Induk_Pegawai b ON a.nip=b.NIP
                                                LEFT JOIN simpia.Bidang_Isct c ON a.bidang=c.id
                                                WHERE a.nip<>''
                                            ) AS hasil
                                            WHERE yt_hapus='T'
                                            $where
                                            ");

        for ($i = 0; $i < count($result); $i++) {
            $status = $result[$i]->status;
            $kode_unit = $result[$i]->unit;

            if ($kode_unit == '001') {
                $result[$i]->unit = 'PUTRA';
            } else {
                $result[$i]->unit = 'PUTRI';
            }
        }

        return $result;
    }

    public function loadTableDetailDataAsset(Request $request)
    {
        $status = $request->status;
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $where = "";

        if ($status == "AKTIF") {
            $where = "AND status='AKTIF'";
        }

        if ($status == "DAFTAR") {
            $where = "AND status='AKTIF'
                      AND YEAR(aktive_date)='$tahun'
                      AND MONTH(aktive_date)='$bulan'";
        }

        if ($status == 'NON AKTIF') {
            $where = "AND status='NON AKTIF'
                      AND YEAR(non_aktive_date)='$tahun'
                      AND MONTH(non_aktive_date)='$bulan'";
        }

        $result = DB::select("SELECT * FROM ( SELECT es.id_asset AS kode, es.status, CONCAT(a.tipe, ' ' , a.merk, ' ', a.spec) AS nama, 
                                                ep.unit_id AS unit, es.keterangan, es.aktive_date, es.non_aktive_date, es.yt_hapus

                                                FROM wifi.user_asset es

                                                LEFT JOIN assets.Eq_Asset a ON a.id=es.id_asset

                                                LEFT JOIN (
                                                    SELECT DATE_FORMAT(tanggal_pakai,'%d/%m/%Y') AS tanggal_pakai, eq_id
                                                    , lembaga_id, nama_lembaga, unit_id, alias_unit AS nama_unit, gedung_id, nama_gedung, ruang_id, nama_ruang
                                                    , pemakai_id, nama_pegawai, divisi_id, d.nama AS nama_divisi, g.kode_gedung, r.kode_ruang, r.kode_ruang_new
                                                    FROM assets.Eq_Posisi p
                                                    LEFT JOIN simpia.Lembaga l ON p.lembaga_id=l.kode_lembaga
                                                    LEFT JOIN simpia.Unit u ON p.unit_id=u.kode_unit
                                                    LEFT JOIN simpia.Gedung g ON p.unit_id=g.kode_unit AND p.gedung_id=g.kode_gedung
                                                    LEFT JOIN simpia.Ruang r ON p.unit_id=r.kode_unit AND p.gedung_id=r.kode_gedung AND p.ruang_id=r.kode_ruang
                                                    LEFT JOIN simpia.Data_Induk_Pegawai i ON p.pemakai_id=i.no_pegawai
                                                    LEFT JOIN simpia.Divisi d ON p.divisi_id=d.id
                                                    WHERE p.deleted = 'N' AND group_id='A' AND moved='N'
                                                ) AS ep ON es.id_asset=ep.eq_id
                                            ) AS hasil
                                            WHERE yt_hapus='T'
                                            $where
                                            ");

        for ($i = 0; $i < count($result); $i++) {
            $status = $result[$i]->status;
            $kode_unit = $result[$i]->unit;

            if ($kode_unit == '001') {
                $result[$i]->unit = 'PUTRA';
            } else {
                $result[$i]->unit = 'PUTRI';
            }
        }

        return $result;
    }

}
