<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;

class ApprovalController extends Controller
{
    public function cekStrukturOrganisasi($no_pegawai)
    {
        // cek penempatan
        $pens = \DB::select("SELECT p.id AS id, 
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
        $struks = \DB::select("SELECT id_struktur as id,
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

    public function listPegawai($id_struktur)
    {
        $results = \DB::select("SELECT d.no_pegawai
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

    public function index()
    {
        $title = 'Approval';
        $active = 'approval';

        $no_pegawai = session('no_pegawai');
        // $no_pegawai = '20000497';
        $ada_struktur = $this->cekStrukturOrganisasi($no_pegawai);
    
        return view('approval.index', compact('title', 'active', 'ada_struktur'));
    }


    public function loadTabelProgramKerjaTahunanApprov(Request $request)
    {
        $filter_status_capaian = $request->filter_status_capaian;
        $filter_approvement = $request->filter_approvement;

        $Fstatus = '';
        if (!empty($filter_status_capaian)) {
            $Fstatus = "AND a.id_status_capaian='$filter_status_capaian'";
        }

        $Fapprove = '';
        if (!empty($filter_approvement)) {
            $Fapprove = "AND a.approvement='$filter_approvement'";
        }

        $id_struktur = session('id_struktur');
        // $id_struktur = '525';
        $penanggung = $this->listPegawai($id_struktur);

        $list = "'" . implode("','", $penanggung) . "'";

        $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai
                            FROM program_kerja_tahunan a
                            LEFT JOIN master_status_pencapaian b ON b.id=a.id_status_capaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.penanggung_jawab
                            WHERE a.id <> ''
                            {$Fstatus}
                            {$Fapprove}
                            AND penanggung_jawab IN ($list)
                            ORDER BY created_at ASC");

        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;

            $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Approval' type='button' class='btn btn-info btn-sm add_edit_data'>
                       <i class='fas fa-check'></i>
                      </button>
                     </div>";

            $data[$i]->aksi = $aksi;
        }

        return $data;
    }

    public function simpanApproval(Request $request)
    {
        $data = [
            'approvement' => $request->approvement,
        ];

        $nama_pegawai = session('no_pegawai');

        // update
        $data['user_updated'] = $nama_pegawai;
        $data['updated_at'] = date('Y-m-d H:i:s');

        ProgramKerjaTahunan::where('id', $request->id)
            ->update($data);

        $hasil = array(
            'sukses' => 'Y',
            'pesan' => 'Berhasil Update Data..'
        );

        return $hasil;
    }

   
}
