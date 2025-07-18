<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReportJurnalHarianController extends Controller
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

        $title = 'Report Jurnal Harian';
        $active = 'report-jurnal-harian';

        return view('report-jurnal-harian.index', [
            'title' => $title,
            'active' => $active,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
        ]);
    }


    // loadTabelReportJurnalHarian
    public function loadTabelReportJurnalHarian(Request $request)
    {
        $id_tahun_ajaran = $request->id_tahun_ajaran;
        $filter_jenis_kegiatan = $request->filter_jenis_kegiatan;
        $filter_program_kerja = $request->filter_program_kerja;
        $filter_status_pencapaian = $request->filter_status_pencapaian;
        $filter_bidang = $request->filter_bidang;
        $filter_ada_tidak_program_kerja = $request->filter_ada_tidak_program_kerja;

        $hari_ini = date('Y-m-d');
        $data_tanggal = $request->filter_tgl;

        $Ftgl = "AND (
                        a.tanggal_mulai BETWEEN '$hari_ini' AND '$hari_ini'
                        OR a.tanggal_selesai BETWEEN '$hari_ini' AND '$hari_ini'
                    )";

        if (!empty($data_tanggal)) {
            $pecah = explode("-", $data_tanggal);
            $tgl_awal = date('Y-m-d', strtotime($pecah[0]));
            $tgl_akhir = date('Y-m-d', strtotime($pecah[1]));

            $Ftgl = "AND (
                        a.tanggal_mulai BETWEEN '$tgl_awal' AND '$tgl_akhir'
                        OR a.tanggal_selesai BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    )";
        }

        $Fadaprogram = '';
        if (!empty($filter_ada_tidak_program_kerja)) {
            if($filter_ada_tidak_program_kerja=="Y"){
                $Fadaprogram = "AND a.id_program_kerja_tahunan <> ''";
            }else{
                $Fadaprogram = "AND a.id_program_kerja_tahunan IS NULL";
            }
        }

        $Fjenis = '';
        if (!empty($filter_jenis_kegiatan)) {
            $Fjenis = "AND a.id_jenis_kegiatan='$filter_jenis_kegiatan'";
        }

        $Fprogram = '';
        if (!empty($filter_program_kerja)) {
            $Fprogram = "AND a.id_program_kerja_tahunan='$filter_program_kerja'";
        }

        $Fstatus = '';
        if (!empty($filter_status_pencapaian)) {
            $Fstatus = "AND a.id_status_pencapaian='$filter_status_pencapaian'";
        }

        $Fbidang = '';
        if (!empty($filter_bidang)) {
            $Fbidang = "AND a.id_bidang='$filter_bidang'";
        }

        $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai,
                                d.nama AS nama_bidang,
                                e.nama AS nama_jenis_kegiatan,
                                f.program_kerja AS nama_program_kerja, f.id_tahun_pelajaran,
                                g.nama_pegawai,
                                h.nama AS nama_tahun_pelajaran
                            FROM jurnal_harian a
                            LEFT JOIN master_status_pencapaian b ON b.id=a.id_status_pencapaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.penanggung_jawab
                            LEFT JOIN master_bidang d ON d.id=a.id_bidang
                            LEFT JOIN master_jenis_kegiatan e ON e.id=a.id_jenis_kegiatan
                            LEFT JOIN program_kerja_tahunan f ON f.id=a.id_program_kerja_tahunan
                            LEFT JOIN simpia.Data_Induk_Pegawai g ON g.no_pegawai=a.penanggung_jawab
                            LEFT JOIN litbang.master_tahun_pelajaran h ON h.id=a.id_tahun_pelajaran
                            WHERE a.id <> ''
                            {$Fjenis}
                            {$Fprogram}
                            {$Fstatus}
                            {$Fbidang}
                            {$Ftgl}
                            {$Fadaprogram}
                            ORDER BY a.created_at ASC");

        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;
        }

        return $data;
    }

}
