<?php

namespace App\Http\Controllers;

use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JurnalHarianController extends Controller
{
    public function index()
    {
        $title = 'Jurnal Harian - '. session('nama_bidang');
        $active = 'jurnal-harian';

        return view('jurnal-harian.index', compact('title', 'active'));
    }

    // loadTabelJurnalHarian
    public function loadTabelJurnalHarian(Request $request)
    {
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
                                f.program_kerja AS nama_program_kerja
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
                            ORDER BY a.created_at ASC");

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
                "keterangan" => $keterangan
            ];

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

                DB::commit();
                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil Simpan Data..",
                );
            } else {
            
                $data['user_updated']  = session('no_pegawai');
                $data['updated_at']    = date('Y-m-d H:i:s');

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

        // Hapus dari database
        JurnalHarian::where('id', $id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);

        return response()->json([
            'status' => 'success',
            'message' => "Deleted!",
        ]);
    }
}
