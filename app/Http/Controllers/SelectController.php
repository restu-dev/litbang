<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\MasterFpb;
use App\Models\MasterUnit;
use App\Models\MasterColor;
use App\Models\MasterGroup;
use App\Models\MasterKelas;
use App\Models\MasterBidang;
use App\Models\MasterGudang;
use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use App\Models\MasterJenjang;
use App\Models\MasterJenisStok;
use App\Models\MasterPembayaran;
use App\Models\MasterDataPegawai;
use App\Models\MasterJenisBarang;
use App\Models\MasterTahunAjaran;
use Illuminate\Support\Facades\DB;
use App\Models\MasterStatusBelanja;
use App\Http\Repositories\HelperRepositories;
use App\Models\BidangIsct;
use App\Models\MasterPo;
use App\Models\MasterProdukSuplier;
use App\Models\MasterSuplier;
use Illuminate\Support\Facades\Auth;

class SelectController extends Controller
{
    protected $helper;

    public function __construct(HelperRepositories $helper)
    {
        $this->helper = $helper;
    }

    public function namaPegawai()
    {
        $results = \DB::select("SELECT d.no_pegawai AS id,
                                    CONCAT(d.nama_pegawai, ' [',IFNULL( nama_jenjang,''), ' - ', IFNULL(nama_struktur,'') , ']') AS text
                              FROM simpia.Data_Induk_Pegawai d, 
                              simpia.Unit u, simpia.Lembaga l, simpia.Struktur_Organisasi so, simpia.Penempatan_Kerja_Pegawai a 
                              LEFT OUTER JOIN simpia.Jenjang j
                              ON(a.kode_lembaga=j.kode_lembaga
                                 AND a.kode_unit=j.kode_unit
                                 AND a.kode_jenjang=j.kode_jenjang)
                              LEFT OUTER JOIN simpia.Status_Pegawai sp ON sp.kode_status_pegawai = a.kode_status_pegawai
                              WHERE a.tanggal_penempatan=(SELECT MAX(aa.tanggal_penempatan) FROM simpia.Penempatan_Kerja_Pegawai aa
                                                            WHERE a.no_pegawai=aa.no_pegawai)
                              AND (a.resign ='' OR a.resign IS NULL)
                              AND a.kode_lembaga=l.kode_lembaga
                              AND a.kode_lembaga=u.kode_lembaga
                              AND a.kode_unit=u.kode_unit
                              AND a.no_pegawai=d.no_pegawai
                              AND a.kode_jabatan=so.kode_jabatan
                              AND a.id_struktur=so.id_struktur
                              ORDER BY d.nama_pegawai ASC");

        return $results;
    }

    public function namaAsset()
    {
        $values = \DB::select("SELECT id as id, id as text FROM assets.Eq_Asset WHERE deleted='N' ORDER BY id DESC");
        return $values;
    }

  

}
