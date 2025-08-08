<?php

namespace App\Http\Repositories;

use App\Models\Price;
use App\Models\MasterPo;
use App\Models\MasterLpb;
use App\Models\UserLevel;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\MasterSatuan;
use App\Models\DistribusiAtk;
use App\Models\MasterJenisStok;
use App\Models\MasterJenisBarang;
use Illuminate\Support\Facades\DB;
use App\Models\DistribusiDetailAtk;
use App\Models\MasterDaftarBelanja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HelperRepositories
{
    public function getBiadangIsct($nip)
    {
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;
        $bidang = DB::table('simpia.Bidang_Isct')->where('id', $id_bidang_isct)->first()->nama;

        $data['id_bidang_isct']= $id_bidang_isct;
        $data['nama_bidang']= $bidang;

        return $data;
    }

    public function pecahTgl($date)
    {
        $pecah      = explode('/', $date);
        $tgl        = $pecah[0];
        $bulan      = $pecah[1];
        $thn        = $pecah[2];

        $hasil      = $thn . '-' . $bulan . '-' . $tgl;
        return $hasil;
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

    // rubah format tgl jam datatabase
    public function formatTglJamDariDb($tgljam)
    {
        $data = date("d-m-Y H:i:s", strtotime($tgljam));
        return $data;
    }

    // format tgl to DB
    public function explodeTglToDb($tgl)
    {
        $old_date = explode('/', $tgl);
        $new_data = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

        return $new_data;
    }

    // pecah bulan
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

    // get pegawai by id
    public function getPegawaiById($no_pegawai)
    {
        $results = DB::select("SELECT d.no_pegawai AS id,
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
                              AND a.no_pegawai='$no_pegawai'");

        return $results;
    }

    // get divisi by id
    public function getDivisiById($id_divisi)
    {
        $results = DB::select("SELECT id,nama_bidang AS text FROM simpia.master_divisi
                                WHERE parent_id<>''
                                AND id='$id_divisi'");

        return $results;
    }

    // cek ada tidak struktur organisasi dibawahnya
    public function cekStrukturOrganisasi($no_pegawai)
    {
        // cek penempatan
        $pens = DB::select("SELECT p.id AS id, 
                                     p.no_pegawai AS no_pegawai,
                                     p.id_struktur AS id_struktur
                              FROM simpia.Penempatan_Kerja_Pegawai p 
                              LEFT OUTER JOIN simpia.Jenjang jg ON (p.kode_lembaga=jg.kode_lembaga AND p.kode_unit=jg.kode_unit AND p.kode_jenjang=jg.kode_jenjang),
                              simpia.Struktur_Organisasi s, simpia.Unit u,simpia.Jabatan j, simpia.Status_Pegawai ss
                              WHERE p.id_struktur=s.id_struktur
                              AND p.kode_jabatan=s.kode_jabatan
                              AND u.kode_lembaga=p.kode_lembaga
                              AND u.kode_unit=p.kode_unit
                              AND p.kode_jabatan=j.kode_jabatan
                              AND p.kode_status_pegawai=ss.kode_status_pegawai
                              AND p.no_pegawai='$no_pegawai'
                              ORDER BY id DESC LIMIT 1");
        foreach ($pens as $p) {
            $id_struktur = $p->id_struktur;
        }

        // cek struktur
        $struks = DB::select("SELECT id_struktur as id,
                                       nama_struktur,
                                       set_struktur,
                                       kode_lembaga,
                                       kode_unit,
                                       kode_jenjang,
                                       kode_jabatan
                              FROM simpia.Struktur_Organisasi
                              WHERE set_struktur='$id_struktur'");
                              
        foreach ($struks as $s) {
            $ada = $s->id;
        }

        if (!empty($ada)) {
            $sukses = "Y";
            Session::put('id_struktur', $id_struktur);
        } else {
            $sukses = "T";
        }

        return $sukses;
    }

    // list pegawai di so
    public function listPegawai($id_struktur)
    {
        $results = DB::select("SELECT d.no_pegawai
                                    /*
                                    d.nama_pegawai,d.jenis_kelamin,d.NIP,d.alamat,d.no_identitas,d.jenis_identitas,d.tinggi,
                                    d.berat_badan,d.agama,d.telp,d.hp,d.email,
                                    p.tanggal_penempatan,p.kode_status_pegawai,p.id_struktur,p.kode_jabatan,p.kode_jenjang,p.kode_unit,p.kode_lembaga,p.resign,p.keterangan_resign,
                                    so.nama_struktur,so.set_struktur,d.no_pegawai as searchValue,
                                    j.nama_jabatan,j.level_jabatan,jg.nama_jenjang,u.nama_unit,
                                    sp.nama_status_pegawai,sp.keterangan_status_pegawai
                                    */
				                FROM simpia.Struktur_Organisasi so,
					                (SELECT simpia.struktur_list(id_struktur) AS id_struktur, @level AS level 
                                        FROM (SELECT @start_with:=?, @id:=@start_with, @level:=0) vars, 
                                        simpia.Struktur_Organisasi WHERE @id IS NOT NULL) sof,
                                    simpia.Jabatan j,
                                    simpia.Status_Pegawai sp,
                                    simpia.Unit u,
                                    simpia.Penempatan_Kerja_Pegawai p LEFT OUTER JOIN simpia.Jenjang jg ON (p.kode_lembaga=jg.kode_lembaga AND p.kode_unit=jg.kode_unit AND p.kode_jenjang=jg.kode_jenjang),
                                    simpia.Data_Induk_Pegawai d 
				                WHERE p.no_pegawai=d.no_pegawai
					            AND p.tanggal_penempatan=(SELECT max(aa.tanggal_penempatan) from simpia.Penempatan_Kerja_Pegawai aa
						                                  WHERE p.no_pegawai=aa.no_pegawai)
					            AND (p.resign ='' OR p.resign IS NULL)
					            AND p.kode_status_pegawai=sp.kode_status_pegawai
					            AND p.id_struktur=so.id_struktur
					            AND (sof.id_struktur=so.id_struktur or so.id_struktur=?)
					            AND p.kode_lembaga=u.kode_lembaga
					            AND p.kode_unit=u.kode_unit
					            AND p.kode_jabatan=so.kode_jabatan
					            AND so.kode_jabatan=j.kode_jabatan
					            AND p.kode_status_pegawai=sp.kode_status_pegawai 
                                AND (sof.level='1')
					            GROUP BY d.no_pegawai,d.nama_pegawai,d.jenis_kelamin,d.NIP,d.alamat,d.no_identitas,d.jenis_identitas,d.tinggi,
						        d.berat_badan,d.agama,d.telp,d.hp,d.email,
						        p.tanggal_penempatan,p.kode_status_pegawai,p.id_struktur,p.kode_jabatan,p.kode_jenjang,p.kode_unit,
						        p.kode_lembaga,p.resign,p.keterangan_resign,
						        so.nama_struktur,so.set_struktur,d.no_pegawai,
						        j.nama_jabatan,j.level_jabatan,jg.nama_jenjang,u.nama_unit,
						        sp.nama_status_pegawai,sp.keterangan_status_pegawai
					            ORDER BY d.nama_pegawai", [$id_struktur, $id_struktur]);

        $no_pegawai = [];
        
        for($i=0;$i<count($results);$i++){
            $no_pegawai[] = $results[$i]->no_pegawai;
        }

        return $no_pegawai;
    }
  

}
