<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProgramKerjaTahunan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Repositories\HelperRepositories;


class ApprovalController extends Controller
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

        $title = 'Approval ' . $nama_tahun_ajaran;
        $active = 'approval';

        $no_pegawai   = session('no_pegawai');
        $id_struktur  = session('id_struktur');

        if(empty($id_struktur)){
            return view('approval.index', [
                'title' => $title,
                'active' => $active,
                'tahunAjar' => $tahunAjar,
                'id_tahun_ajaran' => $id_tahun_ajaran,
                'nama_tahun_ajaran' => $nama_tahun_ajaran,
                'ada_struktur' => "T"
            ]);
        }

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

        return view('approval.index', [
            'title' => $title,
            'active' => $active,
            'tahunAjar' => $tahunAjar,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
            'ada_struktur' => $ada_struktur
        ]);
    }


    public function loadTabelProgramKerjaTahunanApprov(Request $request)
    {
        $id_tahun_ajaran = $request->id_tahun_ajaran;
        $filter_status_capaian = $request->filter_status_capaian;
        $filter_approvement = $request->filter_approvement;
        $filter_tahun = $request->filter_tahun;

        $Ftahun = '';
        if (!empty($filter_tahun)) {
            $Ftahun = "AND a.id_tahun_pelajaran='$filter_tahun'";
        }

        $Fstatus = '';
        if (!empty($filter_status_capaian)) {
            $Fstatus = "AND a.id_status_capaian='$filter_status_capaian'";
        }

        $Fapprove = '';
        if (!empty($filter_approvement)) {
            $Fapprove = "AND a.approvement='$filter_approvement'";
        }

        $nama_level = session('nama_level');

        if($nama_level=='Ketua Harian' || $nama_level=='Kepala Biro'){
            $penanggung = session('no_pegawai');

            $data_pegawai = "AND penanggung_jawab = '$penanggung'";

        }else{
            $id_struktur = session('id_struktur');
            // $id_struktur = '525';
            $penanggung = $this->helper->listPegawai($id_struktur);

            $list = "'" . implode("','", $penanggung) . "'";

            $data_pegawai = "AND penanggung_jawab IN ($list)";
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
                            AND d.id = '$id_tahun_ajaran'
                            {$data_pegawai}
                            ORDER BY created_at ASC");

        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;
            $pro_capaian = $data[$i]->pro_capaian;
            $approvement = $data[$i]->approvement;

            $data[$i]->pro_capaian = $pro_capaian . ' %';

            if($approvement=="Ya"){
                    $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Approval' type='button' class='btn btn-success btn-sm add_edit_data'>
                       <i class='fas fa-check'></i>
                      </button>
                    </div>";
            }else if($approvement=="Tidak"){
                    $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Approval' type='button' class='btn btn-danger btn-sm add_edit_data'>
                       <i class='fas fa-close'></i>
                      </button>
                    </div>";
            }else{
                    $aksi = "<div class='btn-group'>
                      <button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Approval' type='button' class='btn btn-warning btn-sm add_edit_data'>
                       <i class='fas fa-edit'></i>
                      </button>
                    </div>";
            }

          

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
