<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Repositories\HelperRepositories;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportKesehatanMutasiObatExport implements FromCollection, WithHeadings
{

    protected $bulan_trx, $kode_lembaga, $kode_unit;

    function __construct($bulan_trx, $kode_lembaga, $kode_unit)
    {
        $this->bulan_trx = $bulan_trx;
        $this->kode_lembaga = $kode_lembaga;
        $this->kode_unit = $kode_unit;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Nama Obat',
            'Stok Minimal',
            'Harga Terakhir',
            'Stok Awal',
            'Stok Masuk',

            'keluar Santri',
            'keluar Civitas',
            'keluar Umum',
            'keluar Lain',

            'Total Keluar',
            'Stok Akhir'
        ];
    }

    public function getPrice($kode_obat)
    {
        $kode_lembaga = '001';
        $kode_unit = '002';

        $where = array('kode_obat' => $kode_obat, 'kode_lembaga' => $kode_lembaga, 'kode_unit' => $kode_unit);
        $tanggal = DB::table('kesehatan.Price_Product')->where($where)->max('date');

        $whereIs = array('kode_obat' => $kode_obat, 'kode_lembaga' => $kode_lembaga, 'kode_unit' => $kode_unit, 'date' => $tanggal);
        $sql = DB::table('kesehatan.Price_Product')->where($whereIs)->select('price')->get();

        $return = '0';
        if (!empty($sql)) {
            foreach ($sql as $key => $value) {
                $return = $value->price;
            }
        }

        return $return;
    }


    public function collection()
    {
        $bulan_trx = $this->bulan_trx;
        $kode_lembaga = $this->kode_lembaga;
        $kode_unit = $this->kode_unit;

        $results = DB::select("SELECT 
				CONCAT(Data_Induk_Obat.kode_obat,' - ', Data_Induk_Obat.nama_obat) as nama_kode_obat,
				Data_Induk_Obat.kode_obat as kode_obat,
				Data_Induk_Obat.kode_obat as id,
				Data_Induk_Obat.nama_obat as nama_obat,
				Data_Induk_Obat.nama_obat as text,
				Data_Induk_Obat.stok_minimal as stok_minimal,
				Data_Induk_Obat.id_jenis_obat as id_jenis_obat,
				Data_Induk_Obat.id_sediaan_obat as id_sediaan_obat,
				Data_Induk_Obat.id_satuan_obat as id_satuan_obat,
				Data_Jenis_Obat.jenis_obat as jenis_obat,
				Data_Sediaan_Obat.sediaan_obat as sediaan_obat,
				Data_Satuan_Obat.satuan_obat as satuan_obat,
				IFNULL((
					SELECT IFNULL(qty, 0)
					FROM kesehatan.stok_awal
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				),0) as stok_awal,
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_in
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				) as stok_in,
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_out
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				) as stok_out,
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_out_penguna
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND jenis_pasien='santri'
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
					AND kode_lembaga_rm='$kode_lembaga'
					AND kode_unit_rm='$kode_unit'
					AND kode_lembaga_cm='$kode_lembaga'
					AND kode_unit_cm='$kode_unit'
				) as stok_out_santri,
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_out_penguna
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND jenis_pasien='civitas'
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
					AND kode_lembaga_rm='$kode_lembaga'
					AND kode_unit_rm='$kode_unit'
					AND kode_lembaga_cm='$kode_lembaga'
					AND kode_unit_cm='$kode_unit'
				) as stok_out_civitas,
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_out_penguna
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND jenis_pasien='umum'
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
					AND kode_lembaga_rm='$kode_lembaga'
					AND kode_unit_rm='$kode_unit'
					AND kode_lembaga_cm='$kode_lembaga'
					AND kode_unit_cm='$kode_unit'
				) as stok_out_umum,
				(
					SELECT IFNULL(SUM(Stok_Out_Detail.qty),0) 
					FROM kesehatan.Stok_Out_Header
					JOIN kesehatan.Stok_Out_Detail ON kesehatan.Stok_Out_Header.kode=kesehatan.Stok_Out_Detail.id_header
					WHERE Stok_Out_Header.tgl LIKE '$bulan_trx-%'
					AND Stok_Out_Detail.kode_obat=Data_Induk_Obat.kode_obat
					AND Stok_Out_Header.kode_lembaga='$kode_lembaga'
					AND Stok_Out_Header.kode_unit='$kode_unit'
					AND id_rm=''
				) as stok_out_lain,
				IFNULL((
					SELECT IFNULL(qty, 0)
					FROM kesehatan.stok_awal
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				) +
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_in
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				) -
				(
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.stok_out
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				), 0)
				as stok_sistem,
				IFNULL((
					SELECT IFNULL(SUM(qty),0)
					FROM kesehatan.selisih
					WHERE tgl LIKE '$bulan_trx-%'
					AND kode_obat=Data_Induk_Obat.kode_obat
					AND kode_lembaga='$kode_lembaga'
					AND kode_unit='$kode_unit'
				),0) as selisih
				FROM kesehatan.Data_Induk_Obat
				LEFT OUTER JOIN kesehatan.Data_Jenis_Obat ON kesehatan.Data_Induk_Obat.id_jenis_obat=kesehatan.Data_Jenis_Obat.kode_jenis_obat
				LEFT OUTER JOIN kesehatan.Data_Sediaan_Obat ON kesehatan.Data_Induk_Obat.id_sediaan_obat=kesehatan.Data_Sediaan_Obat.kode_sediaan_obat
				LEFT OUTER JOIN kesehatan.Data_Satuan_Obat ON kesehatan.Data_Induk_Obat.id_satuan_obat=kesehatan.Data_Satuan_Obat.kode_satuan_obat
				WHERE Data_Induk_Obat.kode_lembaga='$kode_lembaga'
				AND Data_Induk_Obat.kode_unit='$kode_unit'
			");

        foreach ($results as $key => $value) {
            $kode_obat         = $value->kode_obat;
            $nama_kode_obat    = $value->nama_kode_obat;
            $stok_minimal      = $value->stok_minimal;
            $stok_out          = $value->stok_out;
            $stok_out_santri   = $value->stok_out_santri;
            $stok_out_civitas  = $value->stok_out_civitas;
            $stok_out_umum     = $value->stok_out_umum;
            $stok_out_lain     = $value->stok_out_lain;
            $stok_out_jenis_tot = $stok_out_santri + $stok_out_civitas + $stok_out_umum + $stok_out_lain;
            $stok_in           = $value->stok_in;
            $stok_awal         = $value->stok_awal;

            $harga             = $this->getPrice($kode_obat);

            $return[] = array(
                'nama_kode_obat'        => $nama_kode_obat,
                'stok_minimal'          => $stok_minimal,
                'harga'                 => $harga,
                'stok_awal'             => $stok_awal,
                'stok_in'               => $stok_in,
                'stok_out_santri'       => $stok_out_santri,
                'stok_out_civitas'      => $stok_out_civitas,
                'stok_out_umum'         => $stok_out_umum,
                'stok_out_lain'         => $stok_out_lain,
                'stok_out_jenis_tot'    => $stok_out_jenis_tot,
                'stok_out'              => $stok_out
            );
        }

        return collect($return);
    }

    
}
