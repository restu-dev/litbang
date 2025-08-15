<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramKerjaTahunan;
use Illuminate\Support\Facades\Auth;
use App\Http\Repositories\HelperRepositories;

class ReportProgramKerjaTahunanController extends Controller
{
    protected $helper;

    public function __construct(HelperRepositories $helper)
    {
        $this->helper = $helper;
    }

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
        // $no_pegawai = '20000497';
        // $id_struktur = '525';

        $no_pegawai   = session('no_pegawai');
        $id_struktur  = session('id_struktur');

        $id_tahun_ajaran = $request->id_tahun_ajaran;

        $filter_status_capaian = $request->filter_status_capaian;
        $filter_approvement = $request->filter_approvement;
        $filter_tahun = $request->filter_tahun;
        $filter_bidang = $request->filter_bidang;

        $Ftahun = '';
        if (!empty($filter_tahun)) {
            $Ftahun = "AND a.id_tahun_pelajaran='$filter_tahun'";
        } else {
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

        /*
        2. reporting
            - user non civitas bisa akses semua reporting

            - user civitas
                1. yg tdak ada struktur dibawahnya hanya bisa lihat miliknya sendiri
                2. yg ada bisa melihat milik struktur dibawahnya
        */

        /*
        // bedakan user civitas dan non civitas
        if (Auth::guard('admin')->check()) {
            //    noncivitas
            $penanggung = $this->helper->listPegawai($id_struktur);

            $list = "'" . implode("','", $penanggung) . "'";

            $Fpegawai = "AND a.penanggung_jawab IN ($list)";

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
                            {$Fpegawai}
                            ORDER BY created_at ASC");
        } elseif (Auth::guard('web')->check()) {
            // civitas
            // 1. yg tdak ada struktur dibawahnya hanya bisa lihat miliknya sendiri
            // 2. yg ada bisa melihat milik struktur dibawahnya

            $ada_struktur = $this->helper->cekStrukturOrganisasi($no_pegawai);

            if ($ada_struktur == 'T') {
                $Fpegawai = "AND a.penanggung_jawab='$no_pegawai'";
            } else {
                $penanggung = $this->helper->listPegawai($id_struktur);

                $list = "'" . implode("','", $penanggung) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";
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
                            {$Fpegawai}
                            ORDER BY created_at ASC");
        } else {
            // Belum login, redirect ke login umum
            return redirect()->route('login');
        }
        */

        //  ----- update reques terbaru -------

        $nama_level = session('nama_level');

        // level ini bisa lihat dirisendiri dan bawahan jika di mapping bawahan ada
        if ($nama_level == 'Ketua Harian' || $nama_level == 'Kepala Biro') {

            // bedakan user civitas dan non civitas
            // Jika user civitas hanya bisa lihat dirisendiri /bawahan jika ada
            // Jika user non civitas bisa lihat dirisendiri dan bawahan -> ambil dari tabel mapping bawahan

            if (Auth::guard('admin')->check()) {
                // noncivitas
                $nip = session('nip');

                $penanggung = $this->helper->listPegawaiMappingBawahan($nip);

                $list = "'" . implode("','", $penanggung) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";
            } else {
                // civitas
                $ada_struktur = $this->helper->cekStrukturOrganisasi($no_pegawai);

                if ($ada_struktur == 'T') {
                    $Fpegawai = "AND a.penanggung_jawab='$no_pegawai'";
                } else {
                    $penanggung = $this->helper->listPegawai($id_struktur);

                    $list = "'" . implode("','", $penanggung) . "'";

                    $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                }
            }
        } else {
            // civitas yg level nya bukan Ketua Harian dan Kepala Biro

            $id_struktur = session('id_struktur');
            // $id_struktur = '525';
            $penanggung = $this->helper->listPegawai($id_struktur);

            $list = "'" . implode("','", $penanggung) . "'";

            $Fpegawai = "AND a.penanggung_jawab IN ($list)";
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
                            {$Fpegawai}
                            ORDER BY created_at ASC");


        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;

            $pro_capaian = $data[$i]->pro_capaian;

            $data[$i]->pro_capaian = $pro_capaian . ' %';

            $data[$i]->nama_pegawai = $data[$i]->nama_pegawai??$data[$i]->penanggung_jawab.'(Non Civitas)';
        }

        return $data;
    }
}
