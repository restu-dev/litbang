<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReportProgramKerjaTahunanController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::now()->toDateString();

        $tahunAjar = DB::table('master_tahun_pelajaran')
        ->whereDate('awal', '<=', $hariIni)
        ->whereDate('akhir', '>=', $hariIni)
        ->first();

        $id_tahun_ajaran = $tahunAjar ? $tahunAjar->id : null;
        $nama_tahun_ajaran = $tahunAjar ? $tahunAjar->nama : null;

        $title = 'Report Program Kerja Tahunan';
        $active = 'report-program-kerja-tahunan';

        return view('report-program-kerja-tahunan.index', [
            'title' => $title,
            'active' => $active,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
        ]);
    }


    // loadTabelReportProgramKerjaTahunan
    public function loadTabelReportProgramKerjaTahunan(Request $request)
    {
        $id_tahun_ajaran = $request->id_tahun_ajaran;

        $filter_status_capaian = $request->filter_status_capaian;
        $filter_approvement = $request->filter_approvement;
        $filter_tahun = $request->filter_tahun;
        $filter_bidang = $request->filter_bidang;

        $Ftahun = '';
        if (!empty($filter_tahun)) {
            $Ftahun = "AND a.id_tahun_pelajaran='$filter_tahun'";
        }else{
            $Ftahun = "AND a.id_tahun_pelajaran='$id_tahun_ajaran'";
        }

        $Fstatus = '';
        if (!empty($filter_status_capaian)) {
            $Fstatus = "AND a.id_status_capaian='$filter_status_capaian'";
        }

        $Fapprove = '';
        if (!empty($filter_approvement)) {
            $Fapprove = "AND a.approvement='$filter_approvement'";
        }

        $Fbidang = '';
        if (!empty($filter_bidang)) {
            $Fbidang = "AND a.id_bidang='$filter_bidang'";
        }


        $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai,
                                d.nama AS nama_tahun_pelajaran
                            FROM program_kerja_tahunan a
                            LEFT JOIN master_status_pencapaian b ON b.id=a.id_status_capaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.penanggung_jawab
                            LEFT JOIN litbang.master_tahun_pelajaran d ON d.id=a.id_tahun_pelajaran
                            WHERE a.id <> ''
                            {$Fstatus}
                            {$Fapprove}
                            {$Ftahun}
                            {$Fbidang}
                            ORDER BY created_at ASC");

        for($i=0;$i<count($data);$i++){
            $id = $data[$i]->id;


        }

        return $data;
    }

}
