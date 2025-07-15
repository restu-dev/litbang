<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaTahunanController extends Controller
{
    public function index()
    {
        $title = 'Program Kerja Tahunan';
        $active = 'program-kerja-tahunan';

        return view('program-kerja-tahunan.index', compact('title', 'active'));
    }

    // loadTabelProgramKerjaTahunan
    public function loadTabelProgramKerjaTahunan(Request $request)
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

        $penanggung_jawab = session('no_pegawai');

        $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai
                            FROM program_kerja_tahunan a
                            LEFT JOIN master_status_pencapaian b ON b.id=a.id_status_capaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.penanggung_jawab
                            WHERE a.id <> ''
                            {$Fstatus}
                            {$Fapprove}
                            AND penanggung_jawab='$penanggung_jawab'
                            ORDER BY created_at ASC");

        for($i=0;$i<count($data);$i++){
            $id = $data[$i]->id;

            $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm add_edit_data'>
                       <i class='fas fa-edit'></i>
                      </button>
                      <button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn btn-danger btn-sm hapus_data'>
                        <i class='fa fa-trash'></i>
                      </button>
                     </div>";

            $data[$i]->aksi=$aksi;
        }

        return $data;
    }

    // simpanProgramKerjaTahunan
    public function simpanProgramKerjaTahunan(Request $request)
    {
        $id                         = $request->id;
        $program_kerja              = $request->program_kerja; 
        $target_frekuensi_tahunan   = $request->target_frekuensi_tahunan; 
        $indikator_kinerja          = $request->indikator_kinerja; 
        $status_capaian             = $request->status_capaian; 
        $keterangan                 = $request->keterangan; 

        DB::beginTransaction();

        try {
            $data = [
                "program_kerja" => $program_kerja,
                "penanggung_jawab" => session('no_pegawai'),
                "target_frekuensi_tahunan" => $target_frekuensi_tahunan,
                "indikator_kinerja" => $indikator_kinerja,
                "id_status_capaian" => $status_capaian,
                "keterangan" => $keterangan,
            ];

            if ($id == NULL) {
                 
                $data['approvement']   = "Belum";
                $data['user_created']  = session('no_pegawai');
                $data['created_at']    = date('Y-m-d H:i:s');

                ProgramKerjaTahunan::insert($data);

                DB::commit();
                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil Simpan Data..",
                );
            } else {
            
                $data['user_updated']  = session('no_pegawai');
                $data['updated_at']    = date('Y-m-d H:i:s');

                // update
                ProgramKerjaTahunan::where('id', $id)->update($data);

                DB::commit();
                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil Update Data..",
                );
            }

            return $hasil;
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();
            $baris = $e->getLine();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan . '-' . $baris,
            );

            return $hasil;
        }
    }

    // getDataEditProgramKerjaTahunanById
    public function getDataEditProgramKerjaTahunanById(Request $request)
    {
        $id = $request->id;

        $data = ProgramKerjaTahunan::where('id', $id)->first();

        $data['program_kerja'] = $data->program_kerja;
        $data['penanggung_jawab'] = $data->penanggung_jawab;
        $data['target_frekuensi_tahunan'] = $data->target_frekuensi_tahunan;
        $data['indikator_kinerja'] = $data->indikator_kinerja;
        $data['capaian_aktual'] = $data->capaian_aktual;
        $data['pro_capaian'] = $data->pro_capaian;
        $data['id_status_capaian'] = $data->id_status_capaian;
        $data['keterangan'] = $data->keterangan;
        $data['approvement'] = $data->approvement;

        return $data;
    }

    public function destroy(Request $request)
    {
        ProgramKerjaTahunan::destroy($request->id);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }
}
