<?php

namespace App\Http\Controllers;

use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalHarianController extends Controller
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
        
        $title = 'Jurnal Harian '. $nama_tahun_ajaran.' - '. session('nama_bidang');
        $active = 'jurnal-harian';

        return view('jurnal-harian.index', [
            'title' => $title,
            'active' => $active,
            'tahunAjar' => $tahunAjar,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
        ]);

    }

    // loadTabelJurnalHarian
    public function loadTabelJurnalHarian(Request $request)
    {
        $id_tahun_ajaran = $request->id_tahun_ajaran;
        $filter_jenis_kegiatan = $request->filter_jenis_kegiatan;
        $filter_program_kerja = $request->filter_program_kerja;
        $filter_status_pencapaian = $request->filter_status_pencapaian;


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

        $penanggung_jawab = session('no_pegawai');

        $data = DB::select("SELECT a.*,
                                b.nama AS nama_status_pencapaian,
                                c.nama_pegawai,
                                d.nama AS nama_bidang,
                                e.nama AS nama_jenis_kegiatan,
                                f.program_kerja AS nama_program_kerja, f.id_tahun_pelajaran
                            FROM jurnal_harian a
                            LEFT JOIN master_status_pencapaian b ON b.id=a.id_status_pencapaian
                            LEFT JOIN simpia.Data_Induk_Pegawai c ON c.no_pegawai=a.penanggung_jawab
                            LEFT JOIN master_bidang d ON d.id=a.id_bidang
                            LEFT JOIN master_jenis_kegiatan e ON e.id=a.id_jenis_kegiatan
                            LEFT JOIN program_kerja_tahunan f ON f.id=a.id_program_kerja_tahunan
                            WHERE a.id <> ''
                            {$Fjenis}
                            {$Fprogram}
                            {$Fstatus}
                            AND a.penanggung_jawab='$penanggung_jawab'
                            AND a.id_tahun_pelajaran='$id_tahun_ajaran'
                            -- AND (
                            --     a.id_program_kerja_tahunan IS NULL 
                            --     OR f.id_tahun_pelajaran = '$id_tahun_ajaran'
                            -- )
                            ORDER BY a.created_at ASC");

        for($i=0;$i<count($data);$i++){
            $id = $data[$i]->id;

            $yt_edit = session('yt_edit');
            $yt_del = session('yt_del');

            $edit="";
            if($yt_edit=="Y"){
                $edit = "<button data-id='$id' data-btn='edit' data-toggle='tooltip' data-placement='top' title='Edit' type='button' class='btn btn-info btn-sm add_edit_data'>
                            <i class='fas fa-edit'></i>
                         </button>";
            }

            $hapus="";
            if($yt_del=="Y"){
                $hapus = "<button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn btn-danger btn-sm hapus_data'>
                            <i class='fa fa-trash'></i>
                          </button>";
            }

            $aksi = "<div class='btn-group'>
                        $edit
                        $hapus
                     </div>";

            $file_dokumen = $data[$i]->file_dokumen;
            $file_foto    = $data[$i]->file_foto;

            $aksi_file_dokumen="";
            if(!empty($file_dokumen)){
                $aksi_file_dokumen = "<span style='cursor: pointer' data-id='$id' data-jenis='dokumen' class='preview_dokumen right badge badge-primary'>📑 $file_dokumen</span>";
            }

            $aksi_file_foto="";
            if(!empty($file_foto)){
                $aksi_file_foto = "<span style='cursor: pointer' data-id='$id' data-jenis='foto' class='preview_dokumen right badge badge-success'>📷 $file_foto</span>";
            }

            $data[$i]->aksi=$aksi;
            $data[$i]->file_dokumen=$aksi_file_dokumen;
            $data[$i]->file_foto=$aksi_file_foto;
        }

        return $data;
    }

    // simpanJurnalHarian
    public function simpanJurnalHarian(Request $request)
    {
        $id = $request->id;
        $id_bidang = session('id_bidang');
        $uraian_kegiatan = $request->uraian_kegiatan;
        $id_jenis_kegiatan = $request->id_jenis_kegiatan;
        $output_dokumen = $request->output_dokumen;
        $id_program_kerja_tahunan = $request->id_program_kerja_tahunan;
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;
        $id_status_pencapaian = $request->id_status_pencapaian;
        $keterangan = $request->keterangan;
        $id_tahun_ajaran = $request->id_tahun_ajaran;

        DB::beginTransaction();

        try {
            $data = [
                "penanggung_jawab" => session('no_pegawai'),
                "id_bidang" => $id_bidang,
                "uraian_kegiatan" => $uraian_kegiatan,
                "id_jenis_kegiatan" => $id_jenis_kegiatan,
                "output_dokumen" => $output_dokumen,
                "id_program_kerja_tahunan" => $id_program_kerja_tahunan,
                "tanggal_mulai" => $tanggal_mulai,
                "tanggal_selesai" => $tanggal_selesai,
                "id_status_pencapaian" => $id_status_pencapaian,
                "keterangan" => $keterangan,
                "id_tahun_pelajaran" => $id_tahun_ajaran
            ];


            $idProgram = $id_program_kerja_tahunan;
            $statusBaru = $id_status_pencapaian;

            // Simpan file_dokumen (PDF)
            if ($request->hasFile('file_dokumen')) {
                $request->validate([
                    'file_dokumen' => 'mimes:pdf|max:2048',
                ]);
                $file = $request->file('file_dokumen');
                $namaFile = time() . '_dokumen.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/dokumen'), $namaFile);

                $data['file_dokumen'] = $namaFile;
            }

            // Simpan file_foto (Image)
            if ($request->hasFile('file_foto')) {
                $request->validate([
                    'file_foto' => 'image|max:2048',
                ]);
                $file = $request->file('file_foto');
                $namaFoto = time() . '_foto.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/foto'), $namaFoto);

                $data['file_foto'] = $namaFoto;
            }

            if ($id == NULL) {
                 
                $data['user_created']  = session('no_pegawai');
                $data['created_at']    = date('Y-m-d H:i:s');

                JurnalHarian::insert($data);

                if($idProgram){
                    if ($statusBaru == '1') {
                        DB::table('program_kerja_tahunan')->where('id', $idProgram)->increment('capaian_aktual');
                    }

                    // ✅ Update pro_capaian
                    $program = DB::table('program_kerja_tahunan')->where('id', $idProgram)->first();
                    $proCapaian = ($program->capaian_aktual / max($program->target_frekuensi_tahunan, 1)) * 100;

                    DB::table('program_kerja_tahunan')
                    ->where('id', $idProgram)
                    ->update(['pro_capaian' => $proCapaian]);
                }

                DB::commit();
                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil Simpan Data..",
                );
            } else {
            
                $data['user_updated']  = session('no_pegawai');
                $data['updated_at']    = date('Y-m-d H:i:s');

                // var_dump($id_program_kerja_tahunan);

                if($idProgram){
                    // ✅ MODE EDIT
                    $jurnalLama = DB::table('jurnal_harian')->where('id', $id)->first();
                    $statusLama = $jurnalLama->id_status_pencapaian;

                    // 🔁 Perubahan status dari done ke bukan done
                    if ($statusLama == '1' && $statusBaru != '1') {
                        DB::table('program_kerja_tahunan')->where('id', $idProgram)->decrement('capaian_aktual');
                    }

                    // 🔁 Perubahan status dari bukan done ke done
                    if ($statusLama != '1' && $statusBaru == '1') {
                        DB::table('program_kerja_tahunan')->where('id', $idProgram)->increment('capaian_aktual');
                    }

                    // ✅ Update pro_capaian
                    $program = DB::table('program_kerja_tahunan')->where('id', $idProgram)->first();
                    $proCapaian = ($program->capaian_aktual / max($program->target_frekuensi_tahunan, 1)) * 100;

                    DB::table('program_kerja_tahunan')
                        ->where('id', $idProgram)
                        ->update(['pro_capaian' => $proCapaian]);
                }

                // update
                JurnalHarian::where('id', $id)->update($data);

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

    // getDataEditJurnalHarianById
    public function getDataEditJurnalHarianById(Request $request)
    {
        $id = $request->id;

        $data = JurnalHarian::where('id', $id)->first();

        $data['uraian_kegiatan'] = $data->uraian_kegiatan;
        $data['id_jenis_kegiatan'] = $data->id_jenis_kegiatan;
        $data['output_dokumen'] = $data->output_dokumen;
        $data['file_dokumen'] = $data->file_dokumen;
        $data['file_foto'] = $data->file_foto;
        $data['id_program_kerja_tahunan'] = $data->id_program_kerja_tahunan;
        $data['tanggal_mulai'] = $data->tanggal_mulai;
        $data['tanggal_selesai'] = $data->tanggal_selesai;
        $data['id_status_pencapaian'] = $data->id_status_pencapaian;
        $data['keterangan'] = $data->keterangan;

        return $data;
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $jurnal = JurnalHarian::where('id', $id)->first();

        if (!$jurnal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus file_dokumen
        if ($jurnal->file_dokumen && file_exists(public_path('/upload/dokumen/'.$jurnal->file_dokumen))) {
            unlink(public_path('/upload/dokumen/' . $jurnal->file_dokumen));
        }

        // Hapus file_foto
        if ($jurnal->file_foto && file_exists(public_path('/upload/foto/'.$jurnal->file_foto))) {
            unlink(public_path('/upload/foto/' . $jurnal->file_foto));
        }

        $jurnal = DB::table('jurnal_harian')->where('id', $id)->first();
        $idProgram = $jurnal->id_program_kerja_tahunan;

        // Hapus dari database
        JurnalHarian::where('id', $id)->delete();

        if($idProgram){
            // Jika status = done, kurangi capaian_aktual dan update pro_capaian
            if ($jurnal->id_status_pencapaian == '1') {
                DB::table('program_kerja_tahunan')->where('id', $idProgram)->decrement('capaian_aktual');

                // Ambil ulang data
                $program = DB::table('program_kerja_tahunan')->where('id', $idProgram)->first();
                $proCapaian = ($program->capaian_aktual / max($program->target_frekuensi_tahunan, 1)) * 100;

                DB::table('program_kerja_tahunan')
                    ->where('id', $idProgram)
                    ->update(['pro_capaian' => $proCapaian]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }

    public function previewDokumen(Request $request)
    {
        $id = $request->id;
        $jenis = $request->jenis;
        $jurnal_harian = JurnalHarian::where('id',$id)->first();

        $data['jenis'] = $jenis;
        $data['jurnal_harian'] = $jurnal_harian;

         return view('jurnal-harian.preview-dokumen', ["data" => $data]);
    }
}
