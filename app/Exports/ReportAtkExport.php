<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Repositories\HelperRepositories;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportAtkExport implements FromCollection, WithHeadings
{
    public function getMonthTrx($id_gudang)
    {
        $where = ["id_gudang" => $id_gudang];
        $cekid = DB::table('rt.stok_awal_header')->where($where)->count();

        if ($cekid == 0) {
            $bulan = date("m-Y");
            // $bulan = "12-2023";
        } else {
            $lastbln = DB::table('rt.stok_awal_header')->where($where)->max('tgl');
            $bulan = date("m-Y", strtotime($lastbln));
        }
        return $bulan;
    }

    public function pecahBulan($tanggal, $return)
    {
        $date = explode("-", $tanggal);

        if ($return == "m") {
            return $date[0];
        } else if ($return == "y") {
            return $date[1];
        } else if ($return == "Y-m") {
            return $date[1] . '-' . $date[0];
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Bulan',
            'Barang',
            'Kode',
            'Satuan',
            'Stok Min',
            'Stok Awal',

            'Stok IN',

            'Out ID',
            'Out BD',
            'Out MA',
            'Out MTW',
            'Out ITQ',
            'Out RT',
            'Out ADJ',

            'Tot Out',

            'Stok Fisik',
        ];
    }

    public function dataReport()
    {
        $gudang = "1";
        $bulan_trans = $this->getMonthTrx(1);
        $bulan_trx = $this->pecahBulan($bulan_trans, "Y-m");

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
                                    ),0) AS stok_awal,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_in_header sa
                                        LEFT JOIN stok_in_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN stok_out_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='ID'
                                    ) AS stok_out_id,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='BD'
                                    ) AS stok_out_bd,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='MA'
                                    ) AS stok_out_ma,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='MTW'
                                    ) AS stok_out_mtw,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='ITQ'
                                    ) AS stok_out_itq,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN data_distribusi sb ON sa.id=sb.stok_out_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                        -- AND sb.yt_retur<>'Y'
                                        AND sb.iddivisi='RT'
                                    ) AS stok_out_rt,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='in'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='out'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS selisih,
                                    IFNULL((
                                        SELECT sb.keterangan 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ),'-') AS keterangan_selisih
            FROM master_barang a
            LEFT JOIN master_gudang b ON a.id_gudang=b.id
            LEFT JOIN master_satuan c ON a.id_satuan=c.id
            LEFT JOIN stok_awal_detail d ON a.id=d.id_barang
            LEFT JOIN stok_awal_header e ON d.id_header=e.id
            WHERE a.id_gudang='$gudang'
            GROUP BY a.id
        ");

        return $data;
    }

    public function collection()
    {
        $data = $this->dataReport();

        $return = [];
        foreach ($data as $value) {
            $id = $value->id;
            $bulan_transaksi = $value->bulan_trx;
            $nama_barang = $value->nama_barang;
            $kode_barang = $value->kode_barang;
            $nama_satuan = $value->nama_satuan;
            $nama_gudang = $value->nama_gudang;
            $stok_minimal = $value->stok_minimal;
            $tgl = $value->tgl;
            $stok_awal = $value->stok_awal;
            $stok_in = $value->stok_in;
            $adjusmen_in = $value->adjusmen_in;
            $stok_out = $value->stok_out;
            $stok_out_id = $value->stok_out_id;
            $stok_out_bd = $value->stok_out_bd;
            $stok_out_ma = $value->stok_out_ma;
            $stok_out_mtw = $value->stok_out_mtw;
            $stok_out_itq = $value->stok_out_itq;
            $stok_out_rt = $value->stok_out_rt;
            $adjusmen_out = $value->adjusmen_out;
            $selisih = $value->selisih;
            $keterangan_selisih = $value->keterangan_selisih;

            $stok_sistem = $stok_awal + $stok_in - $stok_out + $adjusmen_in - $adjusmen_out;
            $stok_fisik = $stok_sistem + $selisih;

            $stok_in_fin = $stok_in + $adjusmen_in;
            $stok_out_fin = $stok_out + $adjusmen_out;

            $return[] = array(
                'Bulan' => $bulan_transaksi,
                'Barang' => $nama_barang,
                'Kode' => $kode_barang,
                'Satuan' => $nama_satuan,
                'Stok Min' => $stok_minimal,
                'Stok Awal' => $stok_awal,

                'Stok IN' => $stok_in_fin,

                'Out ID' => $stok_out_id,
                'Out BD' => $stok_out_bd,
                'Out MA' => $stok_out_ma,
                'Out MTW' => $stok_out_mtw,
                'Out ITQ' => $stok_out_itq,
                'Out RT' => $stok_out_rt,
                'Out ADJ' => $adjusmen_out,

                'Tot Out' => $stok_out_fin,

                'Stok Fisik' => $stok_sistem,
            );
        }

        return collect($return);
    }
}
