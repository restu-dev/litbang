<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\DetailFpb;
use App\Models\MasterFpb;
use App\Models\UserLevel;
use App\Models\RiwayatFpb;
use App\Models\MasterBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Repositories\HelperRepositories;

class FpbController extends Controller
{
    protected $helper;

    public function __construct(HelperRepositories $helper)
    {
        $this->helper = $helper;
    }

    public function index()
    {
        $nip = session('nip');
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;

        $bidang = DB::table('simpia.Bidang_Isct')->where('id', $id_bidang_isct)->first()->nama;

        $title = 'Fpb ' . $bidang;
        $active = 'fpb';

        return view('fpb.index', compact('title', 'active', 'bidang'));
    }

    public function finalKode()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $data = DB::select("SELECT no_surat AS jumlah
							FROM wifi.Form_Pengajuan_Header
							WHERE YEAR(tgl)='$tahun'
							AND MONTH(tgl)='$bulan'");

        if (empty($data)) {
            $kode = 1;
        } else {
            $kode = count($data) + 1;
        }

        $final_kode = substr("0000", strlen($kode)) . $kode;

        return $final_kode;
    }

    public function getNoSuratIsct($id_bidang)
    {
        // 0001/SPB.01-PIAT/X/2023

        // bulan romawi
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date("d");
        $bulan = date("m");
        $tahun = date("Y");
        $bulan_romawi = $this->helper->getBulanRomawi($bulan);

        // kode bidang
        $row_bid = DB::table('simpia.Bidang_Isct')->where(['id' => $id_bidang])->first();
        $kode_bidang = $row_bid->kode;

        // kode surat
        // 0001/SPB.01-PIAT/X/2023
        $kode_surat = $this->finalKode() . '/SPB.' . $kode_bidang . '-PIAT/' . $bulan_romawi . '/' . $tahun;

        return $kode_surat;
    }

    public function inputFpb()
    {
        /*
        $title = 'Input Fpb';
        $active = 'Input fpb';

        return view('fpb.generate-master-fpb', compact('title', 'active'));
        */

        // generate NO FPB
        // redirect halaman Input FPB
        $no_pegawai = Auth::user()->no_pegawai;

        $results = DB::select("SELECT a.nama_pegawai, a.NIP, b.id_bidang_isct
                                FROM simpia.Data_Induk_Pegawai a
                                LEFT JOIN wifi.user_level b ON a.NIP=b.nip
                                WHERE no_pegawai='$no_pegawai'");

        $id_bidang_isct = $results[0]->id_bidang_isct ?? "";

        if ($id_bidang_isct != "") {
            // create header

            $nomor_surat = $this->getNoSuratIsct($id_bidang_isct);
            $tgl_fpb = date('Y-m-d');
            $data_posisi = $this->getJabatan($no_pegawai);

            $id_master_fpb = DB::table('Form_Pengajuan_Header')->insertGetId([
                'no_surat' => $nomor_surat,
                'tgl' => $tgl_fpb,
                'nama' => $no_pegawai,
                'jabatan' => $data_posisi['jabatan'],
                'divisi' => $id_bidang_isct,
                'wa' =>  $data_posisi['hp']
            ]);

            // get no FPB By id
            $no_surat_fpb = DB::table('Form_Pengajuan_Header')->where('id', $id_master_fpb)->first()->no_surat;

            // Berhasil
            // Redirect kehalaman Input
            return redirect()->to("/tampil-input-fpb?data=$no_surat_fpb");
        } else {
            // Gagal
            // Redirect kehalaman Input
            return redirect('/403');
        }
    }

    public function tampilInputFpb(Request $request)
    {
        $no_surat = $request->data;
        $data_surat = DB::table('Form_Pengajuan_Header')->where('no_surat', $no_surat);
        $id_master = $data_surat->first()->id ?? "";
        $divisi = $data_surat->first()->divisi ?? "";

        // cek bidang
        $nip = session('nip');
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;

        if ($divisi == $id_bidang_isct) {

            $bidang = DB::table('simpia.Bidang_Isct')->where('id', $id_bidang_isct)->first()->nama;

            $title = 'Input Fpb ' . $bidang;
            $active = 'input-fpb';

            $data = DB::table('Form_Pengajuan_Header')->where('id', $id_master)->first();

            return view('fpb.input-detail-fpb', ["data" => $data, "title" => $title, "active" => $active]);
        } else {
            return redirect('/403');
        }
    }



    // ----------------------


    public function generateNoSurat()
    {
        DB::beginTransaction();

        try {
            $no_pegawai = Auth::user()->no_pegawai;

            $results = DB::select("SELECT a.nama_pegawai, a.NIP, b.id_bidang_isct
                                FROM simpia.Data_Induk_Pegawai a
                                LEFT JOIN rt.user_level b ON a.NIP=b.nip
                                WHERE no_pegawai='$no_pegawai'");

            $id_bidang_isct = $results[0]->id_bidang_isct ?? "";

            if ($id_bidang_isct != "") {
                // create header

                $nomor_surat = $this->getNoSuratIsct($id_bidang_isct);
                $tgl_fpb = date('Y-m-d');

                $id_master_fpb = MasterFpb::insertGetId([
                    'no_surat' => $nomor_surat,
                    'tgl_fpb' => $tgl_fpb,
                    'no_pegawai' => $no_pegawai,
                    'bidang' => $id_bidang_isct,
                    'unit' => session('kode_unit')
                ]);

                DB::commit();

                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil",
                    'id_master_fpb' => $id_master_fpb
                );

                return $hasil;
            } else {
                DB::rollback();

                $hasil = array(
                    'sukses' => 'T',
                    'pesan' => "Gagal add FPB",
                    'id_master_fpb' => ''
                );

                return $hasil;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan,
            );

            return $hasil;
        }
    }

    public function tampilInputDetailFpb(Request $request)
    {
        $id_master_fpb = $request->id_master_fpb;
        $data = MasterFpb::where('id', $id_master_fpb)->first();

        return view('fpb.input-detail-fpb', ['data' => $data]);
    }

    public function getJmlBarangPengajuan($nosurat)
    {
        $data = DB::table('Form_Pengajuan_Detail')->where('no_surat', $nosurat)->count();
        return $data;
    }

    public function getNamaPengaju($nopegawai)
    {
        $data = DB::table('simpia.Data_Induk_Pegawai')->where('no_pegawai', $nopegawai)->first()->nama_pegawai;
        return $data;
    }

    public function getJabatanPost(Request $request)
    {
        $no_pegawai = $request->kode_pegawai;
        return $this->getJabatan($no_pegawai);
    }

    public function getJabatan($no_pegawai)
    {
        $results = \DB::select("SELECT d.no_pegawai AS id,hp,
                                    CONCAT(IFNULL( nama_struktur,''), ' ', IFNULL(nama_jenjang,'')) AS jabatan
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
                              AND d.no_pegawai='$no_pegawai'");

        foreach ($results as $d) {
            $data['jabatan'] = $d->jabatan ?? "";
            $data['hp'] = $d->hp ?? "";
        }

        return $data;
    }

    // tampilViewCetakFpb
    public function tampilViewCetakFpb(Request $request)
    {
        $title = 'Fpb';
        $active = 'fpb';

        $no_surat = $request->data;
        $data_surat = DB::table('Form_Pengajuan_Header')->where('no_surat', $no_surat);

        $no_surat = $data_surat->first()->no_surat ?? "";
        $bidang = $data_surat->first()->divisi ?? "";

        // cek bidang
        $nip = session('nip');
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;

        if ($bidang == $id_bidang_isct) {
            return view('fpb.surat-fpb', compact('title', 'active', 'no_surat'));
        } else {
            return redirect('/403');
        }
    }

    public function getNamaBidang($no_surat)
    {
        $results = DB::select("SELECT a.no_surat, b.nama as bidang
                              FROM wifi.Form_Pengajuan_Header a
                              LEFT JOIN simpia.Bidang_Isct b ON b.id=a.divisi
                              WHERE a.no_surat='$no_surat'");

        foreach ($results as $d) {
            $data['bidang'] = $d->bidang;
        }

        return $data;
    }

    public function formHeader($no_surat)
    {
        $results = DB::select("SELECT a.*,b.nama_pegawai AS nama_pegawai,c.nama AS nama_divisi 
                           FROM Form_Pengajuan_Header a
                           LEFT JOIN simpia.Data_Induk_Pegawai b ON b.no_pegawai=a.nama
                           LEFT JOIN simpia.Divisi c ON c.id=a.divisi
                           WHERE no_surat='$no_surat'");

        foreach ($results as $d) {
            $data['id'] = $d->id;
            $data['no_surat'] = $d->no_surat;
            $data['tgl'] = date("d-m-Y", strtotime($d->tgl));
            $data['nama_pegawai'] = $d->nama_pegawai;
            $data['jabatan'] = $d->jabatan;
            $data['nama_divisi'] = $d->nama_divisi;
            $data['wa'] = $d->wa;
        }

        return $data;
    }

    public function formDetail($no_surat)
    {
        $results = DB::select("SELECT a.*,c.nama_pegawai,b.nama AS nama_perangkat 
                           FROM Form_Pengajuan_Detail a
                           LEFT JOIN assets.Eq_Type b ON b.id=a.jenis_perangkat
                           LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.nama_penguna
                           WHERE no_surat='$no_surat'");

        return $results;
    }

    // viewCetakFpb
    public function viewCetakFpb(Request $request)
    {
        $no_surat = $request->data;

        $bidang = $this->getNamaBidang($no_surat);
        $header = $this->formHeader($no_surat);
        $detail = $this->formDetail($no_surat);
        $pdf = new PDF('utf-8', array(62, 29));

        // var_dump($detail);

        $pdf = PDF::loadView('fpb.view-cetak', ["bidang" => $bidang, "header" => $header, "detail" => $detail]);
        return $pdf->stream('FPBIT_' . $no_surat . '.pdf');
    }

    public function loadTabelMasterFpb(Request $request)
    {
        $no_pegawai = session('no_pegawai');
        $data_tanggal = $request->tgl_fpb;

        $Ftgl = "";
        if (!empty($data_tanggal)) {
            $pecah = explode("-", $data_tanggal);
            $tgl_awal = date('Y-m-d', strtotime($pecah[0]));
            $tgl_akhir = date('Y-m-d', strtotime($pecah[1]));

            $Ftgl = "AND tgl BETWEEN '$tgl_awal' AND '$tgl_akhir'";
        }

        $nip = session('nip');
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;

        $results =  DB::select("SELECT * 
                                FROM Form_Pengajuan_Header
                                WHERE divisi='$id_bidang_isct'
                                {$Ftgl}
                                AND nama='$no_pegawai'
                                AND yt_hapus='T'
                                ORDER BY tgl DESC");

        for ($i = 0; $i < count($results); $i++) {

            $id = $results[$i]->id;
            $tgl = $results[$i]->tgl;
            $no_surat = $results[$i]->no_surat;
            $no_pegawai = $results[$i]->nama;
            $status = $results[$i]->status;
            $results[$i]->tgl = date('d/m/Y', strtotime($tgl));

            $btn_cetak = "";
            if ($status != "") {
                $btn_cetak = "<button data-id='$id' data-surat='$no_surat' type='button' class='btn btn-info cetak_fpb'>
                            <i class='fas fa-print'></i>
                        </button>";
            }

            $btn_hapus = "";
            if ($status == "") {
                $btn_hapus = "<button data-id='$id' type='button' class='btn btn-danger hapus_master'>
                                        <i class='fas fa-trash'></i>
                                    </button>";
            }

            $jml_barang = $this->getJmlBarangPengajuan($no_surat);
            $results[$i]->jml_barang = $jml_barang;

            $nama_pegawai = $this->getNamaPengaju($no_pegawai);
            $results[$i]->nama_pegawai = $nama_pegawai;

            $link = "/tampil-input-fpb?data=$no_surat";

            $results[$i]->aksi = "<div class='btn-group btn-group-sm'>
                                   
                                    <a href='$link' class='btn btn-warning'><i class='fas fa-eye'></i></a>

                                    $btn_hapus
                                    
                                    $btn_cetak

                                </div>";
        }

        return $results;
    }

    public function loadTabelDetailFpb(Request $request)
    {
        $idmaster = $request->idmaster;

        $data_master = DB::table("Form_Pengajuan_Header")->where('id', $idmaster);
        $status = $data_master->first()->status;
        $no_surat = $data_master->first()->no_surat;

        $results = DB::select("SELECT a.*,
                                      b.nama AS nama_perangkat 
                              FROM Form_Pengajuan_Detail a
                              LEFT JOIN assets.Eq_Type b ON b.id=a.jenis_perangkat
                              WHERE no_surat='$no_surat'");

        for ($i = 0; $i < count($results); $i++) {

            $id = $results[$i]->id;

            if ($status == '') {
                $results[$i]->aksi = "<div class='btn-group btn-group-sm'>
                                            <button data-id='$id' type='button' class='btn btn-warning edit_pengajuan_detail'>
                                                <i class='fas fa-pencil'></i>
                                            </button>

                                            <button data-id='$id' type='button' class='btn btn-danger hapus_pengajuan_detail'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </div>";
            } else {
                $results[$i]->aksi = "";
            }
        }

        return $results;
    }

    // viewImgFpb
    public function viewImgFpb(Request $request)
    {
        $id = $request->id;
        $detail_fpb = DetailFpb::where('id', $id)->first();

        $img = $detail_fpb->image;
        $nama_barang = MasterBarang::where('id', $detail_fpb->id_barang)->first()->nama_barang;

        return view('fpb.view-img', ['img' => $img, 'nama_barang' => $nama_barang]);
    }

    // hapusMasterFpb
    public function hapusMasterFpb(Request $request)
    {
        DB::beginTransaction();

        try {

            $id = $request->id;

            $data_master = DB::table("Form_Pengajuan_Header")->where('id', $id);
            $status = $data_master->first()->status;
            $no_surat = $data_master->first()->no_surat;

            // cek status
            if ($status != "") {
                DB::rollback();

                $hasil = array(
                    'sukses' => 'T',
                    'pesan' => "Gagal hapus data, data sudah diajukan!",
                    'id_master_fpb' => ''
                );

                return $hasil;
            }

            $jml_barang = $this->getJmlBarangPengajuan($no_surat);

            if ($jml_barang == 0) {

                $data_master->update(['yt_hapus' => 'Y']);

                DB::commit();

                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil hapus data..",
                );

                return $hasil;
            } else {
                DB::rollback();

                $hasil = array(
                    'sukses' => 'T',
                    'pesan' => "Gagal hapus data, ada data pada FBP ini, hapus terlebih dahulu..",
                    'id_master_fpb' => ''
                );

                return $hasil;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan,
            );

            return $hasil;
        }
    }

    // Update Status Anggaran Bulanan
    // ketika insert / delete
    public function updateStatusAnggaran($status, $id_anggaran)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-rab.pesantrenalirsyad.org/anggaran/update-status-anggaran-bulanan',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('id_anggaran_bulanan' => $id_anggaran, 'status' => $status),
            CURLOPT_HTTPHEADER => array(
                'X-Authorization: 7FpXKnhSTUZeoPcPhc3rpJdRwAgzlkMjSdZzTYXETD7TnIG2dzEhph3jN2OObVyY',
                'Cookie: XSRF-TOKEN=eyJpdiI6Im1JMEorTmQ5djAybUlJa0xlRnBSSEE9PSIsInZhbHVlIjoiQ0cyeEN3NzJ1Tk5WRWk4U0ZkWVA5SGZIUGVzWGdmOUVyQ0Z5WDNEMXVoRTZISFQxdE12MEFQMGQ3MVhsTDkyRlZIcDE3d0xWRnZrUjlIRmJGTll4dnpXdkFmU042Nk1RSzRSRm9lMDdxdHZ0N1JpcER2N0huZklJNGdhMHpxWUwiLCJtYWMiOiJlMzdlMmY4ZDRjODliOWNhZDk4N2FiMjk3YTU5YTMzZDczMzhiNDhiOGM4ODBkNTM2OTI2OGQ1NWE1ZDRiODM3In0%3D; laravel_session=eyJpdiI6IlVNOW1LS0xneVdDTCs3OU95d0VLRXc9PSIsInZhbHVlIjoidm16aVBESSthZzVHZ0hBd1hWcmlKbm5JUmlWaXpZRW9UYVQvUU9SZll2NXJhdW1FY01yV296emtzalJuSU5iL1M5WnlNa2VFK1kxZEI5bVJmTmJUelBvRXU0Wmg1YUlGelo3M1FhOFpmL0xyWmtON2wxOGF6cWF4YnB3cTVmMDUiLCJtYWMiOiJhMzRkMjdiNjY3MzRiNTVjNmUxN2I3ZjE4YTQ5MmYxZTE3OTQ3NDdlZWFkOWRhY2NjODIyZTIyNjhiMGUyNDkwIn0%3D'
            ),
        ));

        $response = curl_exec($curl);
    }

    // simpanDetailFpb
    public function simpanDetailFpb(Request $request)
    {
        DB::beginTransaction();

        try {
            // var_dump($request->all());

            $validator = $request->validate([
                'id_master_fpb' => 'required',
                'no_surat' => 'required',
                'in_jenis_perangkat' => 'required',
                'in_spesifikasi' => 'required',
                'in_jumlah' => 'required',
                'in_uraian_kebutuhan' => 'required',
                'in_jenis_pengajuan' => 'required',
                'in_penguna' => 'required',
                'in_jabatan_penguna' => 'required',
            ]);

            $no_surat = $request->no_surat;
            $yt_anggaran = $request->in_yt_anggaran;
            $id_anggaran = $request->in_nama_anggaran;
            $anggaran = preg_replace('/[^\p{L}\p{N}\s]/u', '', $request->in_anggaran);
            $jumlah = $request->in_jumlah;
            $nama_biaya = $request->nama_biaya;

            if ($yt_anggaran == "Ya") {

                // cek id_anggaran sudah tersimpan blm di surat ini
                $cek_id_anggaran = DB::table('wifi.Form_Pengajuan_Detail')->where(['no_surat' => $no_surat, 'id_anggaran' => $id_anggaran])->count();

                if ($cek_id_anggaran > 0) {
                    if (empty($request->id)) {
                        DB::rollback();

                        $hasil = array(
                            'sukses' => 'T',
                            'pesan' => $nama_biaya . " Sudah terinput!",
                        );

                        return $hasil;
                    }
                }

                if ($id_anggaran == "") {
                    DB::rollback();

                    $hasil = array(
                        'sukses' => 'T',
                        'pesan' => "Jika Anggaran Ya, maka nama anggaran harus diisi!",
                    );

                    return $hasil;
                }

                if ($anggaran == "") {
                    DB::rollback();

                    $hasil = array(
                        'sukses' => 'T',
                        'pesan' => "Jika Anggaran Ya, maka nominal anggaran harus diisi!",
                    );

                    return $hasil;
                }

                if ($jumlah == "") {
                    DB::rollback();

                    $hasil = array(
                        'sukses' => 'T',
                        'pesan' => "Jika Anggaran Ya, maka nominal jumlah harus diisi!",
                    );

                    return $hasil;
                }

                if ($nama_biaya == "") {
                    DB::rollback();

                    $hasil = array(
                        'sukses' => 'T',
                        'pesan' => "Jika Anggaran Ya, maka biaya jumlah harus diisi!",
                    );

                    return $hasil;
                }

                $final_anggaran = $jumlah * $anggaran;

            }

            if ($validator) {
                $id = $request->id;

                if (empty($id)) {
                    //add
                    $proses = DB::table('wifi.Form_Pengajuan_Detail')->insert([
                        'no_surat' => $request->no_surat,
                        'jenis_perangkat' => $request->in_jenis_perangkat,
                        'spesifikasi' => $request->in_spesifikasi,
                        'jumlah' => $jumlah,
                        'yt_anggaran' => $yt_anggaran,
                        'harga_peritem' => $anggaran,
                        'anggaran' => $final_anggaran??"",
                        'uraian_kebutuhan' => $request->in_uraian_kebutuhan,
                        'jenis_pengajuan' => $request->in_jenis_pengajuan,
                        'nama_penguna' => $request->in_penguna,
                        'jabatan_penguna' => $request->in_jabatan_penguna??"",
                        'id_anggaran' => $id_anggaran??"",
                        'nama_anggaran' => $nama_biaya??""
                    ]);
                } else {
                    //edit
                    // cek id_anggaran sama / beda dg sebelumnya
                    $id_anggaran_db = DB::table('wifi.Form_Pengajuan_Detail')->where('id', $id)->first()->id_anggaran;
                    if($id_anggaran_db!= $id_anggaran){
                        // jika beda cek id_anggaran yg diinput sudah ter input atau blm
                        $cek_id_anggaran = DB::table('wifi.Form_Pengajuan_Detail')->where(['no_surat' => $no_surat, 'id_anggaran' => $id_anggaran])->count();

                        if ($cek_id_anggaran > 0) {
                            DB::rollback();

                            $hasil = array(
                                'sukses' => 'T',
                                'pesan' => $nama_biaya . " Sudah terinput!",
                            );

                            return $hasil;
                        }
                    }


                    $proses = DB::table('wifi.Form_Pengajuan_Detail')->where('id', $id)->update([
                        'no_surat' => $request->no_surat,
                        'jenis_perangkat' => $request->in_jenis_perangkat,
                        'spesifikasi' => $request->in_spesifikasi,
                        'jumlah' => $request->in_jumlah,
                        'yt_anggaran' => $yt_anggaran,
                        'harga_peritem' => $anggaran,
                        'anggaran' => $final_anggaran??"",
                        'uraian_kebutuhan' => $request->in_uraian_kebutuhan,
                        'jenis_pengajuan' => $request->in_jenis_pengajuan,
                        'nama_penguna' => $request->in_penguna,
                        'jabatan_penguna' => $request->in_jabatan_penguna,
                        'id_anggaran' => $id_anggaran ?? "",
                        'nama_anggaran' => $nama_biaya ?? ""
                    ]);
                }

                if ($proses) {

                    DB::commit();

                    $hasil = array(
                        'sukses' => 'Y',
                        'pesan' => "Berhasil simpan data..",
                    );

                    return $hasil;
                } else {
                    DB::rollback();

                    $hasil = array(
                        'sukses' => 'T',
                        'pesan' => 'Gagal simpan data!',
                    );

                    return $hasil;
                }
            } else {
                DB::rollback();

                $hasil = array(
                    'sukses' => 'T',
                    'pesan' => 'Gagal Simpan [error]',
                );

                return $hasil;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan,
            );

            return $hasil;
        }
    }

    // getDataDetailFpbById
    public function getDataDetailFpbById(Request $request)
    {
        $id = $request->id;

        $result = DB::table('wifi.Form_Pengajuan_Detail')->where('id', $id)->first();

        $data['id'] = $result->id;
        $data['no_surat'] = $result->no_surat;
        $data['jenis_perangkat'] = $result->jenis_perangkat;
        $data['spesifikasi'] = $result->spesifikasi;
        $data['jumlah'] = $result->jumlah;
        $data['yt_anggaran'] = $result->yt_anggaran;
        $data['harga_peritem'] = $result->harga_peritem;
        $data['anggaran'] = $result->anggaran;
        $data['uraian_kebutuhan'] = $result->uraian_kebutuhan;
        $data['jenis_pengajuan'] = $result->jenis_pengajuan;
        $data['nama_penguna'] = $result->nama_penguna;
        $data['jabatan_penguna'] = $result->jabatan_penguna;
        $data['id_anggaran'] = $result->id_anggaran;
        $data['nama_anggaran'] = $result->nama_anggaran;

        return $data;
    }

    // hapusDetailFpb
    public function hapusDetailFpb(Request $request)
    {
        DB::beginTransaction();

        try {

            // cek status draft
            // jika Draft bisa dihapus

            $id = $request->id;
            $data_detail = DB::table('wifi.Form_Pengajuan_Detail')->where('id', $id);

            if ($data_detail->first()->status == '') {

                $data_detail->delete();

                DB::commit();

                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil hapus data..",
                );

                return $hasil;
            } else {
                DB::rollback();

                $hasil = array(
                    'sukses' => 'T',
                    'pesan' => "Gagal hapus data, status bukan Draft",
                    'id_master_fpb' => ''
                );

                return $hasil;
            }
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan,
            );

            return $hasil;
        }
    }

    // ajukanFpb
    public function ajukanFpb(Request $request)
    {
        $no_surat = $request->no_surat;

        DB::beginTransaction();

        try {

            $cek = DB::table("wifi.Form_Pengajuan_Header")->where('no_surat', $no_surat)->count();
            
            if ($cek == 0) {
                DB::commit();
                $data = array(
                    'sukses' => "T",
                    'pesan' => "Belum ada detail pengajuan " . $no_surat,
                );

                return $data;
            } else {
                $where = ['no_surat' => $no_surat];

                DB::table('wifi.Form_Pengajuan_Header')->where($where)->update([
                    'status' => 'ajukan',
                ]);

                DB::table('assets.Eq_Status_Surat')->insert([
                    'id_master_status' => '1',
                    'kode_surat' => $no_surat,
                    'created' => date("Y-m-d H:i:s"),
                    'u_created' => 'fpbit',
                ]);

                // update status anggaran
                $data_detail = DB::table("wifi.Form_Pengajuan_Detail")->where('no_surat', $no_surat)->get();
                for($i=0;$i<count($data_detail);$i++){
                    $id_anggaran = $data_detail[$i]->id_anggaran;
                    if(!empty($id_anggaran)){
                        $this->updateStatusAnggaran('PROSES', $id_anggaran);
                    }
                }

                DB::commit();
                $data = array(
                    'sukses' => "Y",
                    'pesan' => "Berhasil malakukan pengajuan " . $no_surat,
                );
            }

            DB::commit();

            $hasil = array(
                'sukses' => 'Y',
                'pesan' => "Berhasil ajukan data..",
            );

            return $hasil;
        } catch (\Exception $e) {
            DB::rollback();
            $pesan = $e->getMessage();

            $hasil = array(
                'sukses' => 'T',
                'pesan' => $pesan,
            );

            return $hasil;
        }
    }
}
