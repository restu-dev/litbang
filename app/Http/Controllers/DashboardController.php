<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\HelperRepositories;
use App\Models\MasterTahunPelajaran;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    protected $helper;

    public function __construct(HelperRepositories $helper)
    {
        $this->helper = $helper;
    }

    public function index()
    {
        $no_pegawai   = session('no_pegawai');

        $title = 'Dashboard';
        $active = 'dashboard';

        if (Auth::guard('admin')->check()) {
            //    noncivitas
            $ada_struktur = "Y";
        } elseif (Auth::guard('web')->check()) {
            // civitas
            $ada_struktur = $this->helper->cekStrukturOrganisasi($no_pegawai);
        } else {
            // Belum login, redirect ke login umum
            return redirect()->route('login');
        }

        $data = compact(
            'title',
            'active',
            'ada_struktur'
        );

        return view('dashboard.index', $data);
    }

    // dashboardTampilDataAbsensi
    public function dashboardTampilDataAbsensi()
    {

        $id_struktur  = session('id_struktur');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apimanajemen.pesantrenalirsyad.org/absensi/get-data-abseni-sim-manajemen',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('struktur' => $id_struktur),
            CURLOPT_HTTPHEADER => array(
                'X-Authorization: xrB8frqy2USXJdjfAArfPwXV3L5IDan1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // Ubah ke array
        $result = json_decode($response, true);

        // Cek jika sukses dan data tersedia
        $dataPegawai = [];
        if ($result && isset($result['success']) && $result['success'] === true) {
            $dataPegawai = $result['data'];
        }

        // Kirim ke view
        return view('dashboard.partials.tampil_data_absensi', compact('dataPegawai'));
    }

    // dashboardTampilDataAktivitas
    public function dashboardTampilDataAktivitas(Request $request)
    {
        // $no_pegawai = '20000497';
        // $id_struktur = '525';

        $no_pegawai   = session('no_pegawai');
        $id_struktur  = session('id_struktur');

        /*
        // bedakan user civitas dan non civitas
        if (Auth::guard('admin')->check()) {
            //    noncivitas

            $filter_pegawai = $request->filter_pegawai;

            if (!empty($filter_pegawai)) {
                $list = "'" . implode("','", $filter_pegawai) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";
            } else {
                $penanggung = $this->helper->listPegawai($id_struktur);

                $list = "'" . implode("','", $penanggung) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";
            }

            $data = DB::select("SELECT a.*,
                                        b.nama AS nama_status_pencapaian,
                                        c.nama_pegawai,
                                        d.nama AS nama_bidang,
                                        e.nama AS nama_jenis_kegiatan,
                                        f.program_kerja AS nama_program_kerja,
                                        f.id_tahun_pelajaran,
                                        h.nama AS nama_tahun_pelajaran
                                    FROM jurnal_harian a
                                    LEFT JOIN master_status_pencapaian b ON b.id = a.id_status_pencapaian
                                    LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai = a.penanggung_jawab
                                    LEFT JOIN master_bidang d ON d.id = a.id_bidang
                                    LEFT JOIN master_jenis_kegiatan e ON e.id = a.id_jenis_kegiatan
                                    LEFT JOIN program_kerja_tahunan f ON f.id = a.id_program_kerja_tahunan
                                    LEFT JOIN litbang.master_tahun_pelajaran h ON h.id = a.id_tahun_pelajaran
                                    INNER JOIN (
                                        SELECT penanggung_jawab,
                                            MAX(GREATEST(
                                                IFNULL(updated_at, '0000-00-00 00:00:00'),
                                                IFNULL(created_at, '0000-00-00 00:00:00')
                                            )) AS waktu_terakhir
                                        FROM jurnal_harian
                                        GROUP BY penanggung_jawab
                                    ) AS last_aktivitas
                                    ON a.penanggung_jawab = last_aktivitas.penanggung_jawab
                                    AND GREATEST(
                                        IFNULL(a.updated_at, '0000-00-00 00:00:00'),
                                        IFNULL(a.created_at, '0000-00-00 00:00:00')
                                    ) = last_aktivitas.waktu_terakhir
                                     {$Fpegawai}
                                    ORDER BY c.nama_pegawai ASC
                            LIMIT 50");
        } elseif (Auth::guard('web')->check()) {
            // civitas
            // 1. yg tdak ada struktur dibawahnya hanya bisa lihat miliknya sendiri
            // 2. yg ada bisa melihat milik struktur dibawahnya

            $ada_struktur = $this->helper->cekStrukturOrganisasi($no_pegawai);

            if ($ada_struktur == 'T') {
                $Fpegawai = "AND a.penanggung_jawab='$no_pegawai'";
            } else {

                $filter_pegawai = $request->filter_pegawai;

                if (!empty($filter_pegawai)) {
                    $list = "'" . implode("','", $filter_pegawai) . "'";

                    $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                } else {
                    $penanggung = $this->helper->listPegawai($id_struktur);

                    $list = "'" . implode("','", $penanggung) . "'";

                    $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                }
            }

            $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai,
                                d.nama AS nama_bidang,
                                e.nama AS nama_jenis_kegiatan,
                                f.program_kerja AS nama_program_kerja,
                                f.id_tahun_pelajaran,
                                h.nama AS nama_tahun_pelajaran
                            FROM jurnal_harian a
                            LEFT JOIN master_status_pencapaian b ON b.id = a.id_status_pencapaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai = a.penanggung_jawab
                            LEFT JOIN master_bidang d ON d.id = a.id_bidang
                            LEFT JOIN master_jenis_kegiatan e ON e.id = a.id_jenis_kegiatan
                            LEFT JOIN program_kerja_tahunan f ON f.id = a.id_program_kerja_tahunan
                            LEFT JOIN litbang.master_tahun_pelajaran h ON h.id = a.id_tahun_pelajaran
                            INNER JOIN (
                                SELECT penanggung_jawab,
                                    MAX(GREATEST(
                                        IFNULL(updated_at, '0000-00-00 00:00:00'),
                                        IFNULL(created_at, '0000-00-00 00:00:00')
                                    )) AS waktu_terakhir
                                FROM jurnal_harian
                                GROUP BY penanggung_jawab
                            ) AS last_aktivitas
                            ON a.penanggung_jawab = last_aktivitas.penanggung_jawab
                            AND GREATEST(
                                IFNULL(a.updated_at, '0000-00-00 00:00:00'),
                                IFNULL(a.created_at, '0000-00-00 00:00:00')
                            ) = last_aktivitas.waktu_terakhir
                            {$Fpegawai}
                            ORDER BY c.nama_pegawai ASC");
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
                                d.nama AS nama_bidang,
                                e.nama AS nama_jenis_kegiatan,
                                f.program_kerja AS nama_program_kerja,
                                f.id_tahun_pelajaran,
                                h.nama AS nama_tahun_pelajaran
                            FROM jurnal_harian a
                            LEFT JOIN master_status_pencapaian b ON b.id = a.id_status_pencapaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai = a.penanggung_jawab
                            LEFT JOIN master_bidang d ON d.id = a.id_bidang
                            LEFT JOIN master_jenis_kegiatan e ON e.id = a.id_jenis_kegiatan
                            LEFT JOIN program_kerja_tahunan f ON f.id = a.id_program_kerja_tahunan
                            LEFT JOIN litbang.master_tahun_pelajaran h ON h.id = a.id_tahun_pelajaran
                            INNER JOIN (
                                SELECT penanggung_jawab,
                                    MAX(GREATEST(
                                        IFNULL(updated_at, '0000-00-00 00:00:00'),
                                        IFNULL(created_at, '0000-00-00 00:00:00')
                                    )) AS waktu_terakhir
                                FROM jurnal_harian
                                GROUP BY penanggung_jawab
                            ) AS last_aktivitas
                            ON a.penanggung_jawab = last_aktivitas.penanggung_jawab
                            AND GREATEST(
                                IFNULL(a.updated_at, '0000-00-00 00:00:00'),
                                IFNULL(a.created_at, '0000-00-00 00:00:00')
                            ) = last_aktivitas.waktu_terakhir
                            {$Fpegawai}
                            ORDER BY c.nama_pegawai ASC");


        return view('dashboard.partials.tampil_data_aktivitas', compact('data'));
    }

    // dashboardTampilDataAgenda
    public function dashboardTampilDataAgenda(Request $request)
    {
        $hariIni = Carbon::now()->toDateString();

        $tahunAjar = DB::table('master_tahun_pelajaran')
            ->whereDate('awal', '<=', $hariIni)
            ->whereDate('akhir', '>=', $hariIni)
            ->first();

        $id_tahun_ajaran = $tahunAjar ? $tahunAjar->id : null;

        $data = DB::table('agenda')
            ->join('master_bidang', 'agenda.id_bidang', '=', 'master_bidang.id')
            ->select('agenda.*', 'master_bidang.nama as nama_bidang')
            ->where('agenda.id_tahun_pelajaran', $id_tahun_ajaran)
            ->orderBy('agenda.tgl_awal', 'desc')
            ->get();

        return view('dashboard.partials.tampil_data_agenda', compact('data'));
    }

    // dashboardTampilDataChartJmlAktivitas
    public function dashboardTampilDataChartJmlAktivitas(Request $request)
    {
        $data['filter_tahun'] = $request->filter_tahun ?? "";
        $data['filter_ada_tidak_program_kerja'] = $request->filter_ada_tidak_program_kerja ?? "";
        $data['filter_jenis_kegiatan'] = $request->filter_jenis_kegiatan ?? "";
        $data['filter_status_pencapaian'] = $request->filter_status_pencapaian ?? "";
        $data['filter_bidang'] = $request->filter_bidang ?? "";

        return view('dashboard.partials.tampil_chart_jml_aktivitas', compact('data'));
    }

    // chartJmlAktivitas
    public function chartJmlAktivitas(Request $request)
    {

        $filter_tahun   = $request->filter_tahun;
        $filter_ada_tidak_program_kerja = $request->filter_ada_tidak_program_kerja;
        $filter_jenis_kegiatan = $request->filter_jenis_kegiatan;
        $filter_status_pencapaian = $request->filter_status_pencapaian;
        $filter_bidang  = $request->filter_bidang;

        // cek ada tidak struktur organisasi dibawahnya
        function cekStrukturOrganisasi($no_pegawai)
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
        function listPegawai($id_struktur)
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
            for ($i = 0; $i < count($results); $i++) {
                $no_pegawai[] = $results[$i]->no_pegawai;
            }

            return $no_pegawai;
        }

        function listPegawaiMappingBawahan($nip)
        {
            // get id dari user_level by nip
            $id_user_level = DB::table('user_level')->where('nip', $nip)->first()->id;

            $results = DB::select("SELECT *, a.id AS id_mapping_bawahan_non_civitas
                                FROM mapping_bawahan_non_civitas a
                                LEFT JOIN user_level b ON a.id_user_level=b.id
                                WHERE b.id='$id_user_level'");

            $no_pegawai = [];

            for ($i = 0; $i < count($results); $i++) {
                // $no_pegawai[] = $results[$i]->no_pegawai;
                $nip_bawahan = $results[$i]->nip;
                $id_user_level_bawahan = $results[$i]->id_user_level_bawahan;

                $user_level = DB::table('user_level')->where('id', $id_user_level_bawahan)->first();
                // cek bawahan civitas atau bukan
                // jika civitas cari no_pegawai by nip
                // jika bukan no_pegawai = nip

                $nip_bawahan = $user_level->nip;

                $yt_civitas = $user_level->yt_civitas;

                if ($yt_civitas == "Y") {

                    $data_pegawai = DB::table('simpia.Data_Induk_Pegawai')
                        ->where('NIP', $nip_bawahan)
                        ->selectRaw('CAST(no_pegawai AS UNSIGNED) AS no_pegawai')
                        ->orderByDesc(DB::raw('CAST(no_pegawai AS UNSIGNED)'))
                        ->limit(1)
                        ->value('no_pegawai');

                    $no_pegawai[] = $data_pegawai;
                } else {
                    $no_pegawai[] = $user_level->nip;
                }
            }

            return $no_pegawai;
        }


        function loadTabelReportJurnalHarian($bulan, $status, $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang)
        {
            // $no_pegawai = '20000497';
            // $id_struktur = '525';

            $no_pegawai   = session('no_pegawai');
            $id_struktur  = session('id_struktur');

            $filter_tahun = $filter_tahun;
            $filter_ada_tidak_program_kerja = $filter_ada_tidak_program_kerja;
            $filter_jenis_kegiatan = $filter_jenis_kegiatan;
            // $filter_status_pencapaian = $filter_status_pencapaian;
            $filter_status_pencapaian = $status;
            $filter_bidang = $filter_bidang;


            if (!empty($filter_tahun)) {
                $bulan = $filter_tahun . '-' . $bulan;
                $Ftgl = "AND DATE(a.created_at) LIKE '$bulan-%'";
            } else {
                $tahun_ini = date('Y');
                $bulan = $tahun_ini . '-' . $bulan;
                $Ftgl = "AND DATE(a.created_at) LIKE '$bulan-%'";
            }

            $Fadaprogram = '';
            if (!empty($filter_ada_tidak_program_kerja)) {
                if ($filter_ada_tidak_program_kerja == "Y") {
                    $Fadaprogram = "AND a.id_program_kerja_tahunan <> ''";
                } else {
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

            /*
            // bedakan user civitas dan non civitas
            if (Auth::guard('admin')->check()) {
                //    noncivitas

                $penanggung = listPegawai($id_struktur);

                $list = "'" . implode("','", $penanggung) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";

                $data = DB::select("SELECT count(a.id) AS jml
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
                            {$Fpegawai}
                            ORDER BY a.created_at ASC");
            } elseif (Auth::guard('web')->check()) {
                // civitas
                // 1. yg tdak ada struktur dibawahnya hanya bisa lihat miliknya sendiri
                // 2. yg ada bisa melihat milik struktur dibawahnya

                $ada_struktur = cekStrukturOrganisasi($no_pegawai);

                if ($ada_struktur == 'T') {
                    $Fpegawai = "AND a.penanggung_jawab='$no_pegawai'";
                } else {
                    $penanggung = listPegawai($id_struktur);

                    $list = "'" . implode("','", $penanggung) . "'";

                    $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                }

                $data = DB::select("SELECT count(a.id) AS jml
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
                            {$Fpegawai}
                            ORDER BY a.created_at ASC");
            } else {
                // Belum login, redirect ke login umum
                return redirect()->route('login');
            }
            */


            $nama_level = session('nama_level');

            // level ini bisa lihat dirisendiri dan bawahan jika di mapping bawahan ada
            if ($nama_level == 'Ketua Harian' || $nama_level == 'Kepala Biro') {

                // bedakan user civitas dan non civitas
                // Jika user civitas hanya bisa lihat dirisendiri /bawahan jika ada
                // Jika user non civitas bisa lihat dirisendiri dan bawahan -> ambil dari tabel mapping bawahan

                if (Auth::guard('admin')->check()) {
                    // noncivitas
                    $nip = session('nip');

                    $penanggung = listPegawaiMappingBawahan($nip);

                    $list = "'" . implode("','", $penanggung) . "'";

                    $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                } else {
                    // civitas
                    $ada_struktur = cekStrukturOrganisasi($no_pegawai);

                    if ($ada_struktur == 'T') {
                        $Fpegawai = "AND a.penanggung_jawab='$no_pegawai'";
                    } else {
                        $penanggung = listPegawai($id_struktur);

                        $list = "'" . implode("','", $penanggung) . "'";

                        $Fpegawai = "AND a.penanggung_jawab IN ($list)";
                    }
                }
            } else {
                // civitas yg level nya bukan Ketua Harian dan Kepala Biro

                $id_struktur = session('id_struktur');
                // $id_struktur = '525';
                $penanggung = listPegawai($id_struktur);

                $list = "'" . implode("','", $penanggung) . "'";

                $Fpegawai = "AND a.penanggung_jawab IN ($list)";
            }

            $data = DB::select("SELECT count(a.id) AS jml
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
                            {$Fpegawai}
                            ORDER BY a.created_at ASC");

            return $data[0]->jml ?? "0";
        }

        $done = [];
        $process = [];
        $cancel = [];
        $failed = [];
        $scheduled = [];
        $in_review = [];
        $revision = [];
        $hold = [];

        for ($i = 1; $i <= 12; $i++) {

            $bln = str_pad($i, 2, "0", STR_PAD_LEFT);

            $done[] = loadTabelReportJurnalHarian($bln, '1', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $process[] = loadTabelReportJurnalHarian($bln, '2', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $cancel[] = loadTabelReportJurnalHarian($bln, '4', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $failed[] = loadTabelReportJurnalHarian($bln, '5', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $scheduled[] = loadTabelReportJurnalHarian($bln, '6', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $in_review[] = loadTabelReportJurnalHarian($bln, '7', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $revision[] = loadTabelReportJurnalHarian($bln, '8', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
            $hold[] = loadTabelReportJurnalHarian($bln, '9', $filter_tahun, $filter_ada_tidak_program_kerja, $filter_jenis_kegiatan, $filter_status_pencapaian, $filter_bidang);
        }

        $data['labels'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'];

        // status pencapaian
        $data['done']    = $done; // 1
        $data['process'] = $process; // 2
        $data['cancel'] = $cancel; // 4
        $data['failed'] = $failed; // 5
        $data['scheduled'] = $scheduled; // 6
        $data['in_review'] = $in_review; // 7
        $data['revision'] = $revision; // 8
        $data['hold'] = $hold; // 9

        // var_dump($data);

        return $data;
    }


    // ---------------

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
        return view('dashboard.partials.tampil_data_chart_jml_aktivitas');
    }

    public function loadGrafikJmlBarangOperasional(Request $request)
    {
        $gudang = "1";

        $bulan_trx = $request->bulan_trx;
        if (empty($bulan_trx)) {
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
                'x' => '<b>' . $nama_barang . '</b> [' . $kode_barang . ']',
                'y' => $stok_fisik,
            );
        }

        return $return;
    }

    public function ubahPassword()
    {
        $title = 'Ubah Password';
        $active = 'ubah-password';

        return view('dashboard.ubah-password', compact('title', 'active'));
    }

    public function simpanUbahPassword(Request $request)
    {
        $nip        = session('nip');
        $yt_civitas = $request->yt_civitas;
        $pass_lama  = $request->pass_lama;
        $pass_baru  = $request->pass_baru;
        $aplikasi   = "Litbang";

        DB::beginTransaction();

        try {
            $lanjut = true;
            $sukses = "Y";
            $pesan  = "Berhasil ubah password..";

            // civitas
            if ($yt_civitas == 'Y') {
                // ambil no_pegawai
                $no_pegawai = DB::table('simpia.Data_Induk_Pegawai')->where('NIP', $nip)->first()->no_pegawai ?? "";

                if ($lanjut) {
                    if (empty($nip)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, NIP tidak ditemukan!";
                    }
                }

                $db_pass = DB::table('simpia.users')->where('no_pegawai', $no_pegawai)->first()->password2 ?? "";

                if ($lanjut) {
                    if (empty($db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, memperbarui password!";
                    }
                }

                if ($lanjut) {
                    if (strlen($pass_baru) <= 6) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Password Baru tidak boleh <= 6 karakter";
                    }
                }

                if ($lanjut) {
                    if (!password_verify($pass_lama, $db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, Password Lama Tidak Sesuai!";
                    }
                }

                if ($lanjut) {
                    if (password_verify($pass_baru, $db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, Password Baru Tidak Boleh Sama Dengan Password Lama!";
                    }
                }

                $hashed_pass = password_hash($pass_baru, PASSWORD_DEFAULT);

                if ($lanjut) {

                    // ubah password
                    DB::table('simpia.users')->where('no_pegawai', $no_pegawai)->update([
                        "password2" => $hashed_pass,
                    ]);

                    // simpan log
                    DB::table('simpia.Log_Reset_Pass_Karyawan')->insert([
                        "no_pegawai" => $no_pegawai,
                        "nama_aplikasi" => $aplikasi,
                        "pesan" => $pesan
                    ]);
                }
            } else {
                // non civitas

                $db_pass = DB::table('litbang.user_level')->where('nip', $nip)->first()->pass_user ?? "";

                if ($lanjut) {
                    if (empty($db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, memperbarui password!";
                    }
                }

                if ($lanjut) {
                    if (strlen($pass_baru) <= 6) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Password Baru tidak boleh <= 6 karakter";
                    }
                }

                if ($lanjut) {
                    if (!password_verify($pass_lama, $db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, Password Lama Tidak Sesuai!";
                    }
                }

                if ($lanjut) {
                    if (password_verify($pass_baru, $db_pass)) {
                        $lanjut = false;
                        $sukses = "T";
                        $pesan  = "Gagal, Password Baru Tidak Boleh Sama Dengan Password Lama!";
                    }
                }

                $hashed_pass = password_hash($pass_baru, PASSWORD_DEFAULT);

                if ($lanjut) {

                    // ubah password
                    DB::table('litbang.user_level')->where('nip', $nip)->update([
                        "pass_user" => $hashed_pass,
                    ]);
                }
            }

            DB::commit();

            $hasil = array(
                "sukses" => $sukses,
                "pesan" => $pesan,
            );

            return response()->json($hasil);
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();
            $baris = $e->getLine();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan . '-' . $baris,
            );

            return response()->json($hasil);
        }
    }
}
