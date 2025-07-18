<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProgramKerjaTahunanController extends Controller
{
    public function index()
    {
        $no_pegawai = session('no_pegawai');
        $hariIni = Carbon::now()->toDateString();

        $tahunAjar = DB::table('master_tahun_pelajaran')
        ->whereDate('awal', '<=', $hariIni)
        ->whereDate('akhir', '>=', $hariIni)
        ->first();

        $id_tahun_ajaran = $tahunAjar ? $tahunAjar->id : null;
        $nama_tahun_ajaran = $tahunAjar ? $tahunAjar->nama : null;
        $awal = $tahunAjar ? $tahunAjar->awal : null;

        $title = 'Program Kerja Tahunan '. $nama_tahun_ajaran.' - '. session('nama_bidang');;
        $active = 'program-kerja-tahunan';

        // Ambil 1 tahun ajaran sebelumnya secara otomatis
        $tahunLalu="";
        if($awal){
            $tahunLalu = DB::table('master_tahun_pelajaran')
            ->where('awal', '<', $awal)
            ->orderByDesc('awal')
            ->first();
        }

        $id_tahun_ajaran_lalu = $tahunLalu ? $tahunLalu->id : null;
        $nama_tahun_ajaran_lalu = $tahunLalu ? $tahunLalu->nama : null;

        $adaProgramKerja="";
        if($id_tahun_ajaran){
            $adaProgramKerja = DB::table('program_kerja_tahunan')
            ->where('id_tahun_pelajaran', $id_tahun_ajaran)
            ->where('penanggung_jawab', $no_pegawai)
            ->exists();
        }

        return view('program-kerja-tahunan.index', [
            'title' => $title,
            'active' => $active,
            'tahunAjar' => $tahunAjar,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
            'id_tahun_ajaran_lalu' => $id_tahun_ajaran_lalu,
            'nama_tahun_ajaran_lalu' => $nama_tahun_ajaran_lalu,
            'sudahAdaProgramKerja' => $adaProgramKerja
        ]);
    }

    public function cloneDariTahunLalu(Request $request)
    {
        $no_pegawai = session('no_pegawai');
        $id_tahun_lalu = $request->id_tahun_lalu;
        $id_tahun_sekarang = $request->id_tahun_sekarang;

        // 🔍 Cek apakah data program kerja tahun sekarang sudah ada
        $sudahAda = DB::table('program_kerja_tahunan')
        ->where('id_tahun_pelajaran', $id_tahun_sekarang)
        ->where('penanggung_jawab', $no_pegawai)
        ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Data program kerja untuk tahun ajaran ini sudah ada. Duplikasi dicegah.');
        }

        // Ambil data dari tahun lalu
        $dataTahunLalu = DB::table('program_kerja_tahunan')
        ->where('id_tahun_pelajaran', $id_tahun_lalu)
        ->where('penanggung_jawab', $no_pegawai)
        ->get();

        if ($dataTahunLalu->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data program kerja tahun lalu.');
        }

        // Lakukan cloning data
        foreach ($dataTahunLalu as $item) {
            DB::table('program_kerja_tahunan')->insert([
                'id_tahun_pelajaran' => $id_tahun_sekarang,
                'program_kerja' => $item->program_kerja,
                'penanggung_jawab' => $item->penanggung_jawab,
                'target_frekuensi_tahunan' => $item->target_frekuensi_tahunan,
                'indikator_kinerja' => $item->indikator_kinerja,
                'id_status_capaian' => $item->id_status_capaian,
                'keterangan' => $item->keterangan,
                'user_created' => $no_pegawai,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Program kerja dari tahun lalu berhasil disalin.');
    }

    // loadTabelProgramKerjaTahunan
    public function loadTabelProgramKerjaTahunan(Request $request)
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

        $penanggung_jawab = session('no_pegawai');

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
                            AND penanggung_jawab='$penanggung_jawab'
                            AND d.id='$id_tahun_ajaran'
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
        $tahun_pelajaran            = $request->tahun_pelajaran;
        $program_kerja              = $request->program_kerja; 
        $target_frekuensi_tahunan   = $request->target_frekuensi_tahunan; 
        $indikator_kinerja          = $request->indikator_kinerja; 
        $status_capaian             = $request->status_capaian; 
        $keterangan                 = $request->keterangan;
        $id_bidang                  = session('id_bidang');

        DB::beginTransaction();

        try {
            $data = [
                "id_tahun_pelajaran" => $tahun_pelajaran,
                "id_bidang" => $id_bidang,
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
        $no_pegawai = $data->penanggung_jawab;
        $nama_pegawai = DB::table('simpia.Data_Induk_Pegawai')->where('no_pegawai',$no_pegawai)->first()->nama_pegawai;

        $data['id_tahun_pelajaran'] = $data->id_tahun_pelajaran;
        $data['program_kerja'] = $data->program_kerja;
        $data['penanggung_jawab'] = $data->penanggung_jawab;
        $data['nama_penanggung_jawab'] = $nama_pegawai;
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
        $usedIn = [];
        $id = $request->id;

        // Cek di jurnal_harian
        if (DB::table('jurnal_harian')->where('id_program_kerja_tahunan', $id)->exists()) {
            $usedIn[] = 'jurnal_harian';
        }

        // Jika ada yang pakai, gagalkan penghapusan
        if (!empty($usedIn)) {

            $hasil = array(
                'sukses' => 'T',
                'pesan' => 'Gagal dihapus: Data digunakan di tabel: ' . implode(', ', $usedIn)
            );

            return $hasil;
        }

        // Hapus jika aman
        $deleted = ProgramKerjaTahunan::where('id', $id)->delete();

        if ($deleted) {
            $hasil = array(
                'sukses' => 'Y',
                'pesan' => 'Data berhasil dihapus'
            );

            return $hasil;
        } else {
            $hasil = array(
                'sukses' => 'T',
                'pesan' => 'Data tidak ditemukan'
            );

            return $hasil;
        }
    }
}
