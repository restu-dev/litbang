<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\HelperRepositories;

class DashboardController extends Controller
{
    protected $helper;

    public function __construct(HelperRepositories $helper)
    {
        $this->helper = $helper;
    }

    public function index()
    {
        $title = 'Dashboard';
        $active = 'dashboard';

        // Last login
        /*
        $last_login = DB::select("SELECT p.no_pegawai AS id_pegawai, 
                                p.NIP,
                                nama_pegawai, 
                                ur.user,
                                ur.status_ajukan_user,
                                ur.level_akses_rtpi,
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
                                AND ul.id_level <> ''
                                GROUP BY id_pegawai
                                LIMIT 7");
        */

        $data = compact(
            'title',
            'active'
        );

        return view('dashboard.index', $data);
    }

    public function loadChartSatu()
    {
        $results = DB::select("SELECT a.jenis,
                                IFNULL((
                                SELECT IFNULL(COUNT(id),0) AS jml 
                                FROM (
                                    SELECT id, 1 AS jenis, nip AS kode, STATUS FROM user_wifi
                                    UNION ALL
                                    SELECT id, 2 AS jenis, id_asset AS kode,STATUS FROM user_asset
                                    ) AS d
                                WHERE d.status='AKTIF' 
                                AND d.jenis=a.id
                                ),0) AS jml 
                            FROM jenis_user a");

        $labels = [];
        $jml = [];

        for ($i = 0; $i < count($results); $i++) {
            $labels[] = $results[$i]->jenis;
            $jml[] = $results[$i]->jml;
        }

        $data['labels'] = $labels;
        $data['jml'] = $jml;

        return $data;
    }

    public function tampilHalamanChartSatu()
    {
        return view('dashboard.partials.tampil_1');
    }

    public function loadGrafikJmlBarangOperasional(Request $request)
    {
        $gudang = "1";

        $bulan_trx = $request->bulan_trx;
        if(empty($bulan_trx)){
            $bulan_trx = $this->helper->getMonthTrx(1);
        }

        $bulan_trx = $this->helper->pecahBulan($bulan_trx, "Y-m");

        $data = DB::select("SELECT a.id,
                                    a.nama_barang,
                                    a.kode_barang,
                                    c.nama_satuan,
                                    b.nama AS nama_gudang,
                                    a.stok_minimal,
                                    e.tgl,
                                    DATE_FORMAT(e.tgl, '%m-%Y') as bulan_trx,
                                    IFNULL((
                                        SELECT IFNULL(sb.qty,0) 
                                        FROM stok_awal_header sa
                                        LEFT JOIN stok_awal_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ),0) AS stok_awal,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_in_header sa
                                        LEFT JOIN stok_in_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ) AS stok_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN stok_out_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ) AS stok_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='in'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ) AS adjusmen_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='out'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ) AS adjusmen_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ) AS selisih,
                                    IFNULL((
                                        SELECT sb.keterangan 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        AND sa.id_gudang='$gudang'
                                    ),'-') AS keterangan_selisih
            FROM master_barang a
            LEFT JOIN master_gudang b ON a.id_gudang=b.id
            LEFT JOIN master_satuan c ON a.id_satuan=c.id
            LEFT JOIN stok_awal_detail d ON a.id=d.id_barang
            LEFT JOIN stok_awal_header e ON d.id_header=e.id
            WHERE a.id_gudang='$gudang'
            GROUP BY a.id
        ");

        $return = [];
        foreach ($data as $key => $value) {
            $id = $value->id;
            $bulan_transaksi = $value->bulan_trx;
            $nama_barang = $value->nama_barang;
            $kode_barang = $value->kode_barang;

            $stok_awal = $value->stok_awal;
            $stok_in = $value->stok_in;
            $adjusmen_in = $value->adjusmen_in;
            $stok_out = $value->stok_out;

            $adjusmen_out = $value->adjusmen_out;
            $selisih = $value->selisih;

            $stok_sistem = $stok_awal + $stok_in - $stok_out + $adjusmen_in - $adjusmen_out;
            $stok_fisik = $stok_sistem + $selisih;

            $return[] = array(
                'x' => '<b>'.$nama_barang.'</b> ['. $kode_barang.']',
                'y' => $stok_fisik,
            );
        }

        return $return;
    }

}
