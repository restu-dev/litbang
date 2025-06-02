<?php

namespace App\Http\Repositories;

use DB;
use App\Models\Price;
use App\Models\MasterPo;
use App\Models\MasterLpb;
use App\Models\UserLevel;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\MasterSatuan;
use App\Models\DistribusiAtk;
use App\Models\MasterJenisStok;
use App\Models\MasterJenisBarang;
use App\Models\DistribusiDetailAtk;
use App\Models\MasterDaftarBelanja;
use Illuminate\Support\Facades\Auth;

class HelperRepositories
{
    public function getBiadangIsct($nip)
    {
        $id_bidang_isct = UserLevel::where('nip', $nip)->first()->id_bidang_isct;
        $bidang = DB::table('simpia.Bidang_Isct')->where('id', $id_bidang_isct)->first()->nama;

        $data['id_bidang_isct']= $id_bidang_isct;
        $data['nama_bidang']= $bidang;

        return $data;
    }

    public function getBulanRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    // rubah format tgl jam datatabase
    public function formatTglJamDariDb($tgljam)
    {
        $data = date("d-m-Y H:i:s", strtotime($tgljam));
        return $data;
    }

    // format tgl to DB
    public function explodeTglToDb($tgl)
    {
        $old_date = explode('/', $tgl);
        $new_data = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

        return $new_data;
    }

    // get bulan opname transaksi/ opname stok
    public function getMonthTrx($id_gudang)
    {
        $where = ["id_gudang" => $id_gudang];
        $cekid = DB::table('rt.stok_awal_header')->where($where)->count();

        if ($cekid == 0) {
            $bulan = date("m-Y");
            // $bulan = "12-2023";
        } else {
            $lastbln = DB::table('rt.stok_awal_header')->where($where)->max('tgl');
            $bulan = date("m-Y", strtotime($lastbln));
        }
        return $bulan;
    }

    // pecah bulan
    public function pecahBulan($tanggal, $return)
    {
        $date = explode("-", $tanggal);

        if ($return == "m") {
            return $date[0];
        } else if ($return == "y") {
            return $date[1];
        } else if ($return == "Y-m") {
            return $date[1] . '-' . $date[0];
        }
    }

    public function dataOpname($bulan_trx, $gudang)
    {
        $bulan_trx = $this->pecahBulan($bulan_trx, "Y-m");
        // $bulan_trx = $this->pecahBulan($bulan_trx, "2023-12");

        $Fg = "";
        if (!empty($gudang)) {
            // $Fg = "WHERE a.id_gudang='$gudang'
            //         AND e.tgl LIKE '$bulan_trx-%'";

            $Fg = "WHERE a.id_gudang='$gudang'
                    AND e.tgl LIKE '$bulan_trx-%'";
        }

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
                                    ),0) AS stok_awal,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_in_header sa
                                        LEFT JOIN stok_in_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN stok_out_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='in'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='out'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS selisih,
                                    IFNULL((
                                        SELECT sb.keterangan 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ),'-') AS keterangan_selisih
            FROM master_barang a
            LEFT JOIN master_gudang b ON a.id_gudang=b.id
            LEFT JOIN master_satuan c ON a.id_satuan=c.id
            LEFT JOIN stok_awal_detail d ON a.id=d.id_barang
            LEFT JOIN stok_awal_header e ON d.id_header=e.id
            {$Fg}
        ");

        foreach ($data as $key => $value) {
            $id = $value->id;
            $bulan_transaksi = $value->bulan_trx;
            $nama_barang = $value->nama_barang;
            $kode_barang = $value->kode_barang;
            $nama_satuan = $value->nama_satuan;
            $nama_gudang = $value->nama_gudang;
            $stok_minimal = $value->stok_minimal;
            $tgl = $value->tgl;
            $stok_awal = $value->stok_awal;
            $stok_in = $value->stok_in;
            $stok_out = $value->stok_out;
            $adjusmen_in = $value->adjusmen_in;
            $adjusmen_out = $value->adjusmen_out;
            $selisih = $value->selisih;
            $keterangan_selisih = $value->keterangan_selisih;

            $stok_sistem = $stok_awal + $stok_in - $stok_out + $adjusmen_in - $adjusmen_out;
            $stok_fisik = $stok_sistem + $selisih;

            $stok_in_fin = $stok_in + $adjusmen_in;
            $stok_out_fin = $stok_out + $adjusmen_out;

            $aksi_opname = "<button data-id='$id' data-idgudang='$gudang' data-bulan='$bulan_transaksi' data-barang='$nama_barang' data-stsistem='$stok_sistem' data-stfisik='$stok_fisik' data-ket='$keterangan_selisih' type='button' class='edit_opname_stok btn btn-sm btn-warning btn-block'><i class='fa fa-pencil'></i></button>";

            $aksi_adjusment = "<div class='btn-group'>
                    <button type='button' class='btn btn-success btn-flat'>Aksi</button>
                    <button type='button' class='btn btn-success btn-flat dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                      <span class='sr-only'>Toggle Dropdown</span>
                    </button>
                    <div class='dropdown-menu' role='menu'>
                      <a data-id='$id' data-idgudang='$gudang' data-barang='$nama_barang' class='adj-in dropdown-item' href='javascript:void(0)'>Adjusment In</a>
                      <a data-id='$id' data-idgudang='$gudang' data-barang='$nama_barang' class='adj-out dropdown-item' href='javascript:void(0)'>Adjusment Out</a>
                    </div>
                  </div>";

            $return[] = array(
                'id' => $id,
                'aksi_opname' => $aksi_opname,
                'aksi_adjusment' => $aksi_adjusment,
                'bulan_transaksi' => $bulan_transaksi,
                'nama_barang' => $nama_barang,
                'kode_barang' => $kode_barang,
                'nama_satuan' => $nama_satuan,
                'nama_gudang' => $nama_gudang,
                'tgl' => $tgl,
                'stok_minimal' => $stok_minimal,
                'stok_awal' => $stok_awal,
                'stok_in' => $stok_in_fin,
                'stok_out' => $stok_out_fin,
                'stok_sistem' => $stok_sistem,
                'stok_fisik' => $stok_fisik,
                'selisih' => $selisih,
                'keterangan_selisih' => $keterangan_selisih
            );
        }

        return $return;
    }

    // posting transaksi / opname stok
    public function postingTrx($bulan_trx, $gudang)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data_opname = $this->dataOpname($bulan_trx, $gudang);

        DB::beginTransaction();

        try {
            $bulan_trx = $this->pecahBulan($bulan_trx, "Y-m");

            $bulan = date("m", strtotime($bulan_trx));
            $tahun = date("Y", strtotime($bulan_trx));
            $bulanposting = $bulan + 1;

            if ($bulanposting > 12) {
                $bulanposting = "01";
                $tahunposting = $tahun + 1;
            } else {
                $tahunposting = $tahun;
            }

            $tanggal_posting = $tahunposting . "-" . $bulanposting . "-" . date("d");

            $keterangan = "Proses Posting Id Gudang " . $gudang . " Tanggal " . date("Y-m-d");
            // $keterangan = "Proses Posting Tanggal 2023-11-29";

            //stok awal header
            $id_stokawal_header = DB::table('rt.stok_awal_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => $tanggal_posting,
                'keterangan' => $keterangan,
                'user' => Auth::user()->no_pegawai
            ]);

            //stok in header
            $id_stokin_header = DB::table('rt.stok_in_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => $tanggal_posting,
                'keterangan' => $keterangan,
                'user' => Auth::user()->no_pegawai
            ]);

            //stok out header
            $id_stokout_header = DB::table('rt.stok_out_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => $tanggal_posting,
                'keterangan' => $keterangan,
                'user' => Auth::user()->no_pegawai
            ]);

            //stok adjusment header
            $id_stokselisih_header = DB::table('rt.stok_adjusmen_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => $tanggal_posting,
                'keterangan' => $keterangan,
                'user' => Auth::user()->no_pegawai
            ]);

            //stok selisih header
            $id_stokselisih_header = DB::table('rt.stok_selisih_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => $tanggal_posting,
                'keterangan' => $keterangan,
                'user' => Auth::user()->no_pegawai
            ]);

            foreach ($data_opname as $key => $value) {
                $id_barang = $value['id'];
                $stok_fisik = $value['stok_fisik'];

                //submit stok awal bulan baru
                DB::table('rt.stok_awal_detail')->insert([
                    'id_header' => $id_stokawal_header,
                    'id_barang' => $id_barang,
                    'qty'       => $stok_fisik,
                    'tgl_transaksi' => $tanggal_posting,
                    'user' => Auth::user()->no_pegawai
                ]);
            }

            DB::commit();
            $hasil = array(
                'sukses' => 'Y',
                'pesan' => "Berhasil Posting",
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

    // get kode selisih
    public function getKodeStokSelisih($gudang)
    {
        date_default_timezone_set('Asia/Jakarta');

        $where = ["id_gudang" => $gudang];
        $ceksa = DB::table('rt.stok_selisih_header')->where($where)->count();

        if ($ceksa == 0) {
            $id_header = DB::table('rt.stok_selisih_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => date("Y-m-d"),
                'user' => Auth::user()->no_pegawai,
            ]);
        } else {
            $id_header = DB::table('rt.stok_selisih_header')->where($where)->max('id');
        }

        return $id_header;
    }

    public function cekKodeBarangPerGudang($id_gudang, $id_barang)
    {
        $where = [
            "id" => $id_barang,
            "id_gudang" => $id_gudang,
        ];

        $data = MasterBarang::where($where)->count();
        return $data;
    }

    // cek stok awal header bulan ini
    public function cekHeaderStokAwalBln($id_gudang)
    {
        $bulan_trx = date("Y-m");
        $results = DB::select("SELECT * FROM rt.stok_awal_header
                                WHERE id_gudang='$id_gudang'
                                AND tgl LIKE '$bulan_trx-%'");

        $hasil = count($results);
        return $hasil;
    }

    //getNamaJenisStok
    public function getNamaJenisStok($idjenis)
    {
        $jenis = MasterJenisStok::where('id', $idjenis)->first()->nama_jenis;

        return strtoupper($jenis);
    }

    //getNamaJenisBarang
    public function getNamaJenisBarang($idjenis)
    {
        $jenis = MasterJenisBarang::where('id', $idjenis)->first()->nama_jenis;

        return strtoupper($jenis);
    }

    // get kode stok awal
    public function getKodeStokAwal($gudang)
    {
        $where = ["id_gudang" => $gudang];
        $id_header = DB::table('rt.stok_awal_header')->where($where)->max('id');

        return $id_header;
    }

    // get kode stok out
    public function getKodeStokOut($gudang)
    {
        $where = ["id_gudang" => $gudang];
        $id_header = DB::table('rt.stok_out_header')->where($where)->max('id');

        return $id_header;
    }

    // proses stok out
    public function prosesStokOut($id_header_out, $id_barang, $id_distribusi, $qty, $tgl_transaksi, $user)
    {
        $data = DB::table('rt.stok_out_detail')->insert([
            "id_header" => $id_header_out,
            "id_barang" => $id_barang,
            "id_distribusi" => $id_distribusi,
            "qty" => $qty,
            "tgl_transaksi" => $tgl_transaksi,
            "user" => $user
        ]);

        return $data;
    }

    // submit data barang
    public function submitDataBarang($id, $harga, $stok_awal, $data)
    {
        $id_gudang          = $data['id_gudang'];
        $id_jenis_barang    = $data['id_jenis_barang'];
        $id_jenis_stok      = $data['id_jenis_stok'];
        $id_satuan          = $data['id_satuan'];
        $nama_barang        = $data['nama_barang'];
        $stok_minimal       = $data['stok_minimal'];

        DB::beginTransaction();

        try {
            // cek kode barang per-gudang
            $cek = $this->cekKodeBarangPerGudang($id_gudang, $id);

            if (!empty($id)) {
                // update data barang
                if (empty($cek)) {

                    // update barang
                    MasterBarang::where(["id" => $id])->update([
                        "id_satuan"     => $id_satuan,
                        "id_jenis_barang"      => $id_jenis_barang,
                        "id_jenis_stok"      => $id_jenis_stok,
                        "nama_barang"   => $nama_barang,
                        "stok_minimal"  => $stok_minimal,
                        "price"         => $harga,
                        "user"          => Auth::user()->no_pegawai
                    ]);

                    // insert price
                    Price::create([
                        "id_gudang" => $id_gudang,
                        "id_barang" => $id,
                        "price"     => $harga,
                        "date"      => date("Y-m-d"),
                        "user"      => Auth::user()->no_pegawai
                    ]);
                } else {
                    // update barang
                    MasterBarang::where(["id" => $id])->update([
                        "id_satuan"     => $id_satuan,
                        "id_jenis_barang"      => $id_jenis_barang,
                        "id_jenis_stok"      => $id_jenis_stok,
                        "nama_barang"   => $nama_barang,
                        "stok_minimal"  => $stok_minimal,
                        "price"         => $harga,
                        "user"          => Auth::user()->no_pegawai
                    ]);

                    // insert price
                    Price::create([
                        "id_gudang" => $id_gudang,
                        "id_barang" => $id,
                        "price"     => $harga,
                        "date"      => date("Y-m-d"),
                        "user"      => Auth::user()->no_pegawai
                    ]);
                }

                DB::commit();
                $hasil = array(
                    'sukses' => 'Y',
                    'pesan' => "Berhasil Update Data Barang",
                );
            } else {
                // insert data barang
                if (empty($cek)) {
                    // insert barang

                    // buat kode barang
                    // PIAPI-ATK-0001
                    $idbarang = MasterBarang::max('id');
                    $urut = $idbarang + 1;

                    if ($id_jenis_barang == "1") {
                        $jenis = $this->getNamaJenisStok($id_jenis_stok);
                    } else {
                        $jenis = $this->getNamaJenisBarang($id_jenis_barang);
                    }

                    $kode_barang = "PIAPI-" . $jenis . "-" . sprintf("%04s", $urut);

                    $id_barang = DB::table('rt.master_barang')->insertGetId([
                        "id_gudang"     => $id_gudang,
                        "id_jenis_barang"      => $id_jenis_barang,
                        "id_jenis_stok"      => $id_jenis_stok,
                        "id_satuan"     => $id_satuan,
                        "kode_barang"   => $kode_barang,
                        "nama_barang"   => $nama_barang,
                        "stok_minimal"  => $stok_minimal,
                        "price"         => $harga,
                        "user"          => Auth::user()->no_pegawai
                    ]);

                    // cek barang adalah barang stok
                    if ($id_jenis_barang == '1') {
                        // insert price
                        Price::create([
                            "id_gudang"     => $id_gudang,
                            "id_barang"     => $id_barang,
                            "price"         => $harga,
                            "date"          => date("Y-m-d"),
                            "user"          => Auth::user()->no_pegawai
                        ]);

                        // insert stok awal
                        // cek stok awal header sudah ada atau blm
                        $cek_header_stok = $this->cekHeaderStokAwalBln($id_gudang);

                        if (empty($cek_header_stok)) {

                            // jika master blm ada => inser master + detail
                            $id_header = DB::table('rt.stok_awal_header')->insertGetId([
                                'id_gudang' => $id_gudang,
                                'tgl'       => date("Y-m-d"),
                                'user'      => Auth::user()->no_pegawai
                            ]);

                            DB::table('rt.stok_awal_detail')->insert([
                                'id_header'     => $id_header,
                                'id_barang'     => $id_barang,
                                'qty'           => $stok_awal,
                                'tgl_transaksi' => date("Y-m-d"),
                                'user'          => Auth::user()->no_pegawai
                            ]);
                        } else {

                            $id_header = $this->getKodeStokAwal($id_gudang);
                            DB::table('rt.stok_awal_detail')->insert([
                                'id_header'     => $id_header,
                                'id_barang'     => $id_barang,
                                'qty'           => $stok_awal,
                                'tgl_transaksi' => date("Y-m-d"),
                                'user'          => Auth::user()->no_pegawai
                            ]);
                        }
                    }

                    DB::commit();
                    $hasil = array(
                        'sukses' => 'Y',
                        'pesan'  => "Berhasil input data barang",
                    );
                } else {
                    DB::commit();
                    $hasil = array(
                        'sukses' => 'T',
                        'pesan'     => "Gagal input, Kode barang sudah terpakai!",
                    );
                }
            }

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

    // submit opname stok
    public function submitDataOpname($request)
    {
        $id_barang = $request->id_barang;
        $id_gudang = $request->id_gudang;
        $bulan_trx = $request->bulan_trx;
        $bulan = $request->bulan;
        $barang = $request->barang;
        $stok_sistem = $request->stok_sistem;
        $stok_fisik = $request->stok_fisik;
        $keterangan_opname = $request->keterangan;

        $selisih = $stok_fisik - $stok_sistem;

        $id_header_selisih = $this->getKodeStokSelisih($id_gudang);
        $whereIs = ['id_header' => $id_header_selisih, 'id_barang' => $id_barang];

        $cekdataselisih = DB::table('rt.stok_selisih_detail')->where($whereIs)->count();

        if ($cekdataselisih == 0) {
            DB::table('rt.stok_selisih_detail')->insert([
                'id_header' => $id_header_selisih,
                'id_barang' => $id_barang,
                'qty' => $selisih,
                'keterangan' => $keterangan_opname,
                'user' => Auth::user()->no_pegawai
            ]);
        } else {
            DB::table('rt.stok_selisih_detail')->where($whereIs)->update([
                'id_header' => $id_header_selisih,
                'id_barang' => $id_barang,
                'qty' => $selisih,
                'keterangan' => $keterangan_opname,
                'user' => Auth::user()->no_pegawai
            ]);
        }
    }

    // get kode selisih
    public function getKodeStokAdjusment($gudang)
    {
        date_default_timezone_set('Asia/Jakarta');

        $where = ["id_gudang" => $gudang];
        $ceksa = DB::table('rt.stok_adjusmen_header')->where($where)->count();

        if ($ceksa == 0) {
            $id_header = DB::table('rt.stok_adjusmen_header')->insertGetId([
                'id_gudang' => $gudang,
                'tgl' => date("Y-m-d"),
                'user' => Auth::user()->no_pegawai,
            ]);
        } else {
            $id_header = DB::table('rt.stok_adjusmen_header')->where($where)->max('id');
        }

        return $id_header;
    }

    // adjusment 
    public function prosesStokAdjusment($request)
    {
        $id_barang = $request->id_barang;
        $id_gudang = $request->id_gudang;
        $barang = $request->barang;
        $tgl =  date('Y-m-d', strtotime($request->tgl));
        $qty = $request->qty;
        $keterangan = $request->keterangan;
        $jenis_adjusmen = $request->jenis_adjusmen;

        $id_header = $this->getKodeStokAdjusment($id_gudang);

        DB::table('rt.stok_adjusmen_detail')->insert([
            'id_header' => $id_header,
            'id_barang' => $id_barang,
            'qty' => $qty,
            'jenis_adjusmen' => $jenis_adjusmen,
            'tgl_transaksi' => $tgl,
            'keterangan' => $keterangan,
            'user' => Auth::user()->no_pegawai
        ]);

        return;
    }

    // get id stok in header
    public function getKodeStokIn($id_gudang)
    {
        $where = ["id_gudang" => $id_gudang];
        $ceksa = DB::table('rt.stok_in_header')->where($where)->count();

        if ($ceksa == 0) {
            $id_header = DB::table('rt.stok_in_header')->insertGetId(array(
                'id_gudang' => $id_gudang,
                'tgl' => date("Y-m-d"),
                'user' => Auth::user()->no_pegawai,
            ));
        } else {
            $id_header = DB::table('rt.stok_in_header')->where($where)->max('id');
        }

        return $id_header;
    }

    // get kode po
    public function getKodePo($id_gudang)
    {
        $results = MasterPo::where(["id_gudang" => $id_gudang])->count();

        if (empty($results)) {
            $data = 1;
        } else {
            $data = $results + 1;
        }

        return $data;
    }

    // get kode po
    public function getKodePoMain()
    {
        $results = MasterPo::count();

        if (empty($results)) {
            $data = 1;
        } else {
            $data = $results + 1;
        }

        return $data;
    }

    // get kode lpb
    public function getKodeLpbMain()
    {
        $results = MasterLpb::count();

        if (empty($results)) {
            $data = 1;
        } else {
            $data = $results + 1;
        }

        return $data;
    }

    // load data po old
    public function getDataPoById($id)
    {
        $results =  DB::select("SELECT a.*,b.nama AS nama_gudang, c.nama AS nama_suplier 
                                FROM rt.po_header a
                                LEFT JOIN rt.master_gudang b ON a.id_gudang=b.id
                                LEFT JOIN rt.master_suplier c ON a.id_suplier=c.id
                                WHERE a.id='$id'");

        return $results;
    }

    // load data po
    public function getDataPoMainById($id)
    {
        $results =  DB::select("SELECT a.*,b.nama AS nama_suplier,c.nama_status,d.nama_pembayaran
                                FROM rt.master_po a
                                LEFT JOIN rt.master_suplier b ON a.id_suplier=b.id
                                LEFT JOIN rt.master_status_belanja c ON a.id_status=c.id
                                LEFT JOIN rt.master_pembayaran d ON a.id_pembayaran=d.id
                                WHERE a.id='$id'");

        return $results;
    }

    // get pegawai by id
    public function getPegawaiById($no_pegawai)
    {
        $results = \DB::select("SELECT d.no_pegawai AS id,
                                    CONCAT(d.nama_pegawai, ' [',IFNULL( nama_jenjang,''), ' - ', IFNULL(nama_struktur,'') , ']') AS text
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
                              AND a.no_pegawai='$no_pegawai'");

        return $results;
    }

    // get divisi by id
    public function getDivisiById($id_divisi)
    {
        $results = DB::select("SELECT id,nama_bidang AS text FROM simpia.master_divisi
                                WHERE parent_id<>''
                                AND id='$id_divisi'");

        return $results;
    }

    // jumlah detail distribusi
    public function getJumlahDetailDistribusiAtk($id_master)
    {
        $data = DistribusiDetailAtk::where(["id_header_distribusi" => $id_master])->count();
        return $data;
    }

    // get Master Distribusi By Id
    public function getMasterDistribusiById($id)
    {
        $data = DistribusiAtk::where(["id" => $id])->first();
        return $data;
    }

    public function getMasterDistribusiByIdAll($id)
    {
        $results =  DB::select("SELECT a.*,
                                    b.nama AS nama_gudang
                                FROM rt.master_distribusi_atk a
                                LEFT JOIN rt.master_gudang b ON a.id_gudang=b.id
                                WHERE a.id_gudang='1'
                                AND a.id='$id'");

        for ($i = 0; $i < count($results); $i++) {

            $id         = $results[$i]->id;
            $id_pegawai = $results[$i]->no_pegawai;
            $id_divisi  = $results[$i]->id_divisi_keu;
            $tgl        = $results[$i]->tgl;
            $conv_tgl = date("m/d/Y", strtotime($tgl));
            $results[$i]->tgl = $conv_tgl;

            $status = $results[$i]->status;
            $results[$i]->status = $status;

            $data_pegawai = $this->getPegawaiById($id_pegawai);
            $results[$i]->pegawai = $data_pegawai[0]->text;

            $data_divisi = $this->getDivisiById($id_divisi);
            $results[$i]->divisi = $data_divisi[0]->text;

            $data_jumlah = $this->getJumlahDetailDistribusiAtk($id);
            $results[$i]->jumlah = $data_jumlah;
        }

        return $results;
    }

    public function getMasterDaftarBelanjaByIdAll($id)
    {
        $results =  DB::select("SELECT a.*,a.user AS user_input, b.nama_bidang,c.nama_pegawai
                                FROM rt.master_daftar_belanja a
                                LEFT JOIN rt.master_bidang b ON a.id_bidang=b.id
                                LEFT JOIN simpia.Data_Induk_Pegawai c ON a.id_peminta=c.no_pegawai
                                WHERE a.id='$id'");

        for ($i = 0; $i < count($results); $i++) {
            $tgl1            = $results[$i]->tgl_surat;
            $tgl2            = $results[$i]->tgl_terima_surat;

            $conv_tgl_surat                     = date("d/m/Y", strtotime($tgl1));
            $conv_tgl_terima_surat              = date("d/m/Y", strtotime($tgl2));
            $results[$i]->tgl_surat             = $conv_tgl_surat;
            $results[$i]->conv_tgl_terima_surat = $conv_tgl_terima_surat;

            $id_pegawai = $results[$i]->id_peminta;
            $data_pegawai = $this->getPegawaiById($id_pegawai);
            $results[$i]->pegawai = $data_pegawai[0]->text;
        }

        return $results;
    }

    public function reportOpname($bulan_trx, $gudang)
    {
        $bulan_trx = $this->pecahBulan($bulan_trx, "Y-m");
        // $bulan_trx = $this->pecahBulan($bulan_trx, "2023-12");

        $Fg = "";
        if (!empty($gudang)) {
            $Fg = "WHERE a.id_gudang='$gudang'";
        }

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
                                    ),0) AS stok_awal,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_in_header sa
                                        LEFT JOIN stok_in_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN stok_out_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='in'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='out'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS selisih,
                                    IFNULL((
                                        SELECT sb.keterangan 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ),'-') AS keterangan_selisih
            FROM master_barang a
            LEFT JOIN master_gudang b ON a.id_gudang=b.id
            LEFT JOIN master_satuan c ON a.id_satuan=c.id
            LEFT JOIN stok_awal_detail d ON a.id=d.id_barang
            LEFT JOIN stok_awal_header e ON d.id_header=e.id
            {$Fg}
        ");

        foreach ($data as $key => $value) {
            $id = $value->id;
            $bulan_transaksi = $value->bulan_trx;
            $nama_barang = $value->nama_barang;
            $kode_barang = $value->kode_barang;
            $nama_satuan = $value->nama_satuan;
            $nama_gudang = $value->nama_gudang;
            $stok_minimal = $value->stok_minimal;
            $tgl = $value->tgl;
            $stok_awal = $value->stok_awal;
            $stok_in = $value->stok_in;
            $stok_out = $value->stok_out;
            $adjusmen_in = $value->adjusmen_in;
            $adjusmen_out = $value->adjusmen_out;
            $selisih = $value->selisih;
            $keterangan_selisih = $value->keterangan_selisih;

            $stok_sistem = $stok_awal + $stok_in - $stok_out + $adjusmen_in - $adjusmen_out;
            $stok_fisik = $stok_sistem + $selisih;

            $stok_in_fin = $stok_in + $adjusmen_in;
            $stok_out_fin = $stok_out + $adjusmen_out;

            $aksi_opname = "<button data-id='$id' data-idgudang='$gudang' data-bulan='$bulan_transaksi' data-barang='$nama_barang' data-stsistem='$stok_sistem' data-stfisik='$stok_fisik' data-ket='$keterangan_selisih' type='button' class='edit_opname_stok btn btn-sm btn-warning btn-block'><i class='fa fa-pencil'></i></button>";

            $aksi_adjusment = "<div class='btn-group'>
                    <button type='button' class='btn btn-success btn-flat'>Aksi</button>
                    <button type='button' class='btn btn-success btn-flat dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                      <span class='sr-only'>Toggle Dropdown</span>
                    </button>
                    <div class='dropdown-menu' role='menu'>
                      <a data-id='$id' data-idgudang='$gudang' data-barang='$nama_barang' class='detail-report dropdown-item' href='javascript:void(0)'>Detail</a>
                    </div>
                  </div>";

            $return[] = array(
                'id' => $id,
                'aksi_opname' => $aksi_opname,
                'aksi_adjusment' => $aksi_adjusment,
                'bulan_transaksi' => $bulan_transaksi,
                'nama_barang' => $nama_barang,
                'kode_barang' => $kode_barang,
                'nama_satuan' => $nama_satuan,
                'nama_gudang' => $nama_gudang,
                'tgl' => $tgl,
                'stok_minimal' => $stok_minimal,
                'stok_awal' => $stok_awal,
                'stok_in' => $stok_in_fin,
                'stok_out' => $stok_out_fin,
                'stok_sistem' => $stok_sistem,
                'stok_fisik' => $stok_fisik,
                'selisih' => $selisih,
                'keterangan_selisih' => $keterangan_selisih
            );
        }

        return $return;
    }

    public function getHargaTerakhir($id_barang)
    {
        $data = DB::select("SELECT * 
                            FROM rt.price_barang
                            WHERE id_barang='$id_barang'
                            ORDER BY id DESC
                            LIMIT 1");

        // cek
        if (!empty($data)) {
            $harga = $data[0]->price;
        } else {
            $harga = '0';
        }

        return $harga;
    }

    public function getDetailBarangBelanja($idmaster, $idbarang)
    {
        $data = DB::select("SELECT a.*,f.id AS id_detail_daftar_belanja,e.no_pb,e.nama_proyek,b.nama_barang,c.nama_jenis,d.nama_satuan 
                            FROM rt.detail_daftar_belanja a
                            LEFT JOIN master_barang b ON a.id_barang=b.id
                            LEFT JOIN rt.master_jenis_barang c ON b.id_jenis_barang=c.id
                            LEFT JOIN rt.master_satuan d ON b.id_satuan=d.id
                            LEFT JOIN rt.master_daftar_belanja e ON a.id_master_daftar_belanja=e.id
                            LEFT JOIN rt.detail_daftar_belanja f ON e.id=f.id_master_daftar_belanja AND f.id_barang='$idbarang'
                            WHERE a.id_status='1'
                            AND a.id_master_daftar_belanja='$idmaster'
                            AND a.id_barang='$idbarang'
                            ORDER BY b.nama_barang ASC");

        for ($i = 0; $i < count($data); $i++) {
            $id_barang                  = $data[$i]->id_barang;
            $data[$i]->harga_terakhir   = $this->getHargaTerakhir($id_barang);
        }

        $hasil['id_detail_daftar_belanja'] = $data[0]->id_detail_daftar_belanja;
        $hasil['qty'] = $data[0]->qty;
        $hasil['harga'] = $data[0]->harga_terakhir;

        return $hasil;
    }

    public function pecahTgl($date)
    {
        $pecah      = explode('/', $date);
        $tgl        = $pecah[0];
        $bulan      = $pecah[1];
        $thn        = $pecah[2];

        $hasil      = $thn . '-' . $bulan . '-' . $tgl;
        return $hasil;
    }

    public function dataBarangDatang($request)
    {
        $id = $request->id;

        $Fid = "";
        if (!empty($id)) {
            $Fid = " AND a.id='$id'";
        }

        $results = DB::select("SELECT a.id,a.no_po,a.tgl,a.id AS id_master_po,
                                b.id AS id_detail_po, b.qty_po,b.harga_satuan,b.id_status,b.id_detail_daftar_belanja,
                                c.nama_status,
                                d.id_barang,
                                e.nama_barang,e.id_gudang,e.id_jenis_barang,
                                f.nama AS nama_gudang,
                                g.nama_satuan,
                                h.nama_color,
                                i.nama_jenis,
                                (
                                    SELECT IFNULL(SUM(bi.qty),0) 
                                    FROM rt.barang_datang_in bi
                                    LEFT JOIN rt.detail_po dp ON bi.id_master_po=dp.id_master_po AND bi.id_detail_po=dp.id
                                    WHERE dp.id=b.id
                                ) AS barang_datang_in,
                                (
                                    SELECT IFNULL(SUM(bo.qty),0) 
                                    FROM rt.barang_datang_out bo
                                    LEFT JOIN rt.detail_po dp ON bo.id_master_po=dp.id_master_po AND bo.id_detail_po=dp.id
                                    WHERE dp.id=b.id
                                ) AS barang_datang_out
                            FROM rt.master_po a
                            LEFT JOIN rt.detail_po b ON b.id_master_po=a.id
                            LEFT JOIN rt.master_status_belanja c ON b.id_status=c.id
                            LEFT JOIN rt.detail_daftar_belanja d ON b.id_detail_daftar_belanja=d.id
                            LEFT JOIN rt.master_barang e ON d.id_barang=e.id
                            LEFT JOIN rt.master_gudang f ON e.id_gudang=f.id
                            LEFT JOIN rt.master_satuan g ON e.id_satuan=g.id
                            LEFT JOIN rt.master_color h ON c.id_color=h.id
                            LEFT JOIN rt.master_jenis_barang i ON e.id_jenis_barang=i.id
                            WHERE b.id_detail_daftar_belanja <> ''
                            $Fid
                            AND a.id_status <> '1' 
                            AND a.id_status <> '3' 
                            AND a.id_status <> '5'
                            ORDER BY b.id ASC");

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->tglconv = date("d/m/Y", strtotime($results[$i]->tgl));

            $id         = $results[$i]->id;
            $barang_in  = $results[$i]->barang_datang_in;
            $qty_po     = $results[$i]->qty_po;

            $id_barang  = $results[$i]->id_barang;
            $id_gudang  = $results[$i]->id_gudang;
            $gudang     = $results[$i]->nama_gudang;
            $id_jenis_barang   = $results[$i]->id_jenis_barang;
            $jenis      = $results[$i]->nama_jenis;
            $satuan     = $results[$i]->nama_satuan;
            $barang     = $results[$i]->nama_barang;

            $results[$i]->nama_barang   = $barang . '<br> [ <b>' . $satuan . ' - ' . $gudang . '</b> ]';

            $qty_kurang = $qty_po - $barang_in;
            $results[$i]->qty_kurang = $qty_kurang;
            if ($qty_kurang < 0) {
                $results[$i]->qty_kurang = "+ " . abs($qty_kurang);
            }

            $nama_status    = $results[$i]->nama_status;
            $color          = $results[$i]->nama_color;

            $id_master          = $results[$i]->id_master_po;
            $id_detail          = $results[$i]->id_detail_po;
            $id_detail_belanja  = $results[$i]->id_detail_daftar_belanja;
            $nama_barang        = $barang . ' [ ' . $satuan . ' - ' . $gudang . ' ]';
            $harga_satuan       = $results[$i]->harga_satuan;

            $results[$i]->aksi = "<div class='btn-group'>
                    <button type='button' class='btn btn-$color btn-flat'>$nama_status</button>
                    <button type='button' class='btn btn-$color btn-flat dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                      <span class='sr-only'>Toggle Dropdown</span>
                    </button>
                    <div class='dropdown-menu' role='menu'>
                      <a data-idmaster='$id_master' data-iddetail='$id_detail' data-namabarang='$nama_barang' data-qtypo='$qty_po' data-hargapo='$harga_satuan' class='barang-datang dropdown-item' href='javascript:void(0)'>Barang Datang</a>
                      <a data-idbarang='$id_barang' data-jenis='$jenis' data-idjenis='$id_jenis_barang' data-gudang='$gudang' data-idgudang='$id_gudang' data-idmaster='$id_master' data-iddetail='$id_detail' data-namabarang='$nama_barang' data-qtypo='$qty_po' data-hargapo='$harga_satuan' class='detail-barang-datang dropdown-item' href='javascript:void(0)'>Detail Barang Datang</a>
                      <a data-idmaster='$id_master' data-iddetail='$id_detail' data-idbelanja='$id_detail_belanja' data-namabarang='$nama_barang' class='ubah-status-barang-datang dropdown-item' href='javascript:void(0)'>Ubah Status</a>
                    </div>
                  </div>";
        }

        return $results;
    }

    public function getMasterPoById($id)
    {
        $results = DB::select("SELECT a.*,
                                      b.nama_perusahaan AS nama_suplier 
                                FROM rt.master_po a
                                LEFT JOIN rt.master_suplier b ON b.id=a.id_suplier
                                WHERE a.id='$id'");

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->tgl      = date("d/m/Y", strtotime($results[$i]->tgl));
        }

        return $results;
    }

    public function getDetailPOById($id)
    {
        $results = DB::select("SELECT a.*,
                                c.nama_perusahaan AS nama_suplier,
                                d.nama_produk,
                                e.nama AS nama_bidang,
                                f.uraian_kebutuhan,
                                g.no_surat,
                                h.kode_barang, h.nama_barang,
                                i.nama_satuan
                            FROM detail_po a
                            LEFT JOIN master_po b ON a.id_master_po=b.id
                            LEFT JOIN master_suplier c ON b.id_suplier=c.id
                            LEFT JOIN master_produk_suplier d ON c.id_produk=d.id
                            LEFT JOIN simpia.Bidang_Isct e ON b.bidang=e.id
                            LEFT JOIN detail_fpb f ON a.id_detail_fpb=f.id
                            LEFT JOIN master_fpb g ON f.id_master_fpb=g.id
                            LEFT JOIN master_barang h ON a.id_barang=h.id
                            LEFT JOIN master_satuan i ON h.id_satuan=i.id
                            WHERE a.id_master_po='$id'");

        return $results;

    }

    public function getDataPoByIdNew($id)
    {
        $results = DB::select("SELECT a.*,
                                b.nama_status AS status_po,
                                d.id AS id_pb,
                                d.no_pb,d.nama_proyek,
                                c.id_barang,
                                e.nama_barang,
                                f.nama_jenis,
                                g.nama_satuan,
                                h.nama_color
                                FROM rt.detail_po a
                                LEFT JOIN rt.master_status_belanja b ON a.id_status=b.id
                                LEFT JOIN rt.detail_daftar_belanja c ON a.id_detail_daftar_belanja=c.id
                                LEFT JOIN rt.master_daftar_belanja d ON c.id_master_daftar_belanja=d.id
                                LEFT JOIN rt.master_barang e ON c.id_barang=e.id
                                LEFT JOIN rt.master_jenis_barang f ON e.id_jenis_barang=f.id
                                LEFT JOIN rt.master_satuan g ON e.id_satuan=g.id
                                LEFT JOIN rt.master_color h ON b.id_color=h.id
                                WHERE a.id_master_po='$id'");

        for ($i = 0; $i < count($results); $i++) {
            // $results[$i]->tgl      = date("d/m/Y", strtotime($results[$i]->tgl));
        }

        return $results;
    }

    public function getBarangById($id)
    {
        $result = DB::select("SELECT a.id,a.nama_barang,a.price,b.nama_jenis,c.nama_satuan
                                FROM master_barang a
                                LEFT JOIN master_jenis_barang b ON b.id=a.id_jenis_barang
                                LEFT JOIN master_satuan c ON c.id=a.id_satuan
                                WHERE a.id='$id'");

        return $result;
    }

    public function getMasterBelanja($idmaster)
    {
        $results = DB::select("SELECT a.*,b.nama_pegawai,c.nama_bidang 
                            FROM rt.master_daftar_belanja a
                            LEFT JOIN simpia.Data_Induk_Pegawai b ON b.no_pegawai=a.id_peminta
                            LEFT JOIN rt.master_bidang c ON c.id=a.id_bidang
                            WHERE a.id='$idmaster'");
        return $results;
    }

    public function getBarangDatangTerakhir($id_master, $id_detail)
    {
        $results = DB::select("SELECT * 
                                FROM barang_datang_in
                                WHERE id_master_po='$id_master'
                                AND id_detail_po='$id_detail'
                                ORDER BY id DESC
                                LIMIT 1");

        return $results;
    }

    public function dataReportBelanja($filter)
    {
        $tgl_awal   = $this->pecahTgl($filter['tgl_awal']);
        $tgl_akhir  = $this->pecahTgl($filter['tgl_akhir']);

        $results = DB::select("SELECT a.id,a.no_po,a.tgl,a.id AS id_master_po,
                                b.tgl as tgl_po,
                                b.id AS id_detail_po, b.qty_po,b.harga_satuan,b.id_status,b.id_detail_daftar_belanja,
                                c.nama_status,
                                d.id_barang,d.id_master_daftar_belanja,d.qty AS qty_po,d.id_barang,
                                e.nama_barang,
                                f.nama AS nama_gudang,
                                g.nama_satuan,
                                h.nama_color,
                                i.nama AS nama_suplier,
                                (
                                    SELECT IFNULL(SUM(bi.qty),0) 
                                    FROM rt.barang_datang_in bi
                                    LEFT JOIN rt.detail_po dp ON bi.id_master_po=dp.id_master_po AND bi.id_detail_po=dp.id
                                    WHERE dp.id=b.id
                                ) AS barang_datang_in,
                                (
                                    SELECT IFNULL(SUM(bo.qty),0) 
                                    FROM rt.barang_datang_out bo
                                    LEFT JOIN rt.detail_po dp ON bo.id_master_po=dp.id_master_po AND bo.id_detail_po=dp.id
                                    WHERE dp.id=b.id
                                ) AS barang_datang_out
                            FROM rt.master_po a
                            LEFT JOIN rt.detail_po b ON b.id_master_po=a.id
                            LEFT JOIN rt.master_status_belanja c ON b.id_status=c.id
                            LEFT JOIN rt.detail_daftar_belanja d ON b.id_detail_daftar_belanja=d.id
                            LEFT JOIN rt.master_barang e ON d.id_barang=e.id
                            LEFT JOIN rt.master_gudang f ON e.id_gudang=f.id
                            LEFT JOIN rt.master_satuan g ON e.id_satuan=g.id
                            LEFT JOIN rt.master_color h ON c.id_color=h.id
                            LEFT JOIN rt.master_suplier i ON a.id_suplier=i.id
                            WHERE b.id_detail_daftar_belanja <> ''
                            AND b.tgl BETWEEN '$tgl_awal' AND '$tgl_akhir'
                            ORDER BY a.tgl ASC");

        for ($i = 0; $i < count($results); $i++) {
            $results[$i]->tglconv = date("y-m", strtotime($results[$i]->tgl));

            $barang_in  = $results[$i]->barang_datang_in;
            $qty_po     = $results[$i]->qty_po;

            $gudang     = $results[$i]->nama_gudang;
            $satuan     = $results[$i]->nama_satuan;
            $barang     = $results[$i]->nama_barang;

            $results[$i]->nama_barang = $barang . '[ ' . $satuan . ' - ' . $gudang . ' ]';

            $qty_kurang = $qty_po - $barang_in;
            $results[$i]->qty_kurang = $qty_kurang;
            if ($qty_kurang < 0) {
                $results[$i]->qty_kurang = "+ " . abs($qty_kurang);
            }

            $id_master_daftar_belanja   = $results[$i]->id_master_daftar_belanja;
            $master_belanja             = $this->getMasterBelanja($id_master_daftar_belanja);
            $results[$i]->nama_proyek   = $master_belanja[0]->nama_proyek;
            $results[$i]->no_pb         = $master_belanja[0]->no_pb;
            $results[$i]->peminta       = $master_belanja[0]->nama_pegawai;
            $results[$i]->bidang        = $master_belanja[0]->nama_bidang;
            $results[$i]->tgl_pb        = date("d/m/Y", strtotime($master_belanja[0]->tgl_terima_surat));

            $results[$i]->tgl_po        = date("d/m/Y", strtotime($results[$i]->tgl_po));
            $id_master                  = $results[$i]->id_master_po;
            $id_detail                  = $results[$i]->id_detail_po;

            $barang_datang              = $this->getBarangDatangTerakhir($id_master, $id_detail);

            if (!empty($barang_datang)) {
                $results[$i]->qty_datang    = $barang_datang[0]->qty;
                $results[$i]->harga_datang  = $barang_datang[0]->harga_datang;
                $results[$i]->tgl_datang    = date("d/m/Y", strtotime($barang_datang[0]->created_at));
                $results[$i]->user_terima   = $barang_datang[0]->user;
            } else {
                $results[$i]->qty_datang    = 0;
                $results[$i]->harga_datang  = 0;
                $results[$i]->tgl_datang    = 0;
                $results[$i]->user_terima   = 0;
            }

            $nama_status    = $results[$i]->nama_status;
            $color          = $results[$i]->nama_color;


            $id_detail_belanja  = $results[$i]->id_detail_daftar_belanja;
            $nama_barang        = $barang . ' [ ' . $satuan . ' - ' . $gudang . ' ]';
            $harga_satuan       = $results[$i]->harga_satuan;
        }

        return $results;
    }

    public function getIdGudangByName($gudang)
    {
        $data = MasterGudang::where('nama', $gudang);

        $hasil = '';
        if (!empty($data->count())) {
            $hasil = $data->first()->id;
        }

        return $hasil;
    }

    public function getIdJenisBarangByName($jenis_barang)
    {
        $data = MasterJenisBarang::where('nama_jenis', $jenis_barang);

        $hasil = '';
        if (!empty($data->count())) {
            $hasil = $data->first()->id;
        }

        return $hasil;
    }

    public function getIdJenisStokByName($jenis_stok)
    {
        $data = MasterJenisStok::where('nama_jenis', $jenis_stok);

        $hasil = '';
        if (!empty($data->count())) {
            $hasil = $data->first()->id;
        }

        return $hasil;
    }

    public function getIdSatuanByName($satuan)
    {
        $data = MasterSatuan::where('nama_satuan', $satuan);

        $hasil = '';
        if (!empty($data->count())) {
            $hasil = $data->first()->id;
        }

        return $hasil;
    }

    public function cekAdaBarangByNama($nama_barang)
    {
        $data = MasterBarang::where('nama_barang', $nama_barang);

        $hasil = '';
        if (!empty($data->count())) {
            $hasil = $data->first()->id;
        }

        return $hasil;
    }

    public function getStokPerItem($bulan_trx, $gudang, $idbarang)
    {
        $bulan_trx = $this->pecahBulan($bulan_trx, "Y-m");

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
                                    ),0) AS stok_awal,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_in_header sa
                                        LEFT JOIN stok_in_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_out_header sa
                                        LEFT JOIN stok_out_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS stok_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='in'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_in,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_adjusmen_header sa
                                        LEFT JOIN stok_adjusmen_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sb.jenis_adjusmen='out'
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS adjusmen_out,
                                    (
                                        SELECT IFNULL(SUM(sb.qty),0) 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ) AS selisih,
                                    IFNULL((
                                        SELECT sb.keterangan 
                                        FROM stok_selisih_header sa
                                        LEFT JOIN stok_selisih_detail sb ON sa.id=sb.id_header
                                        WHERE sb.id_barang=a.id
                                        AND sa.tgl LIKE '$bulan_trx-%'
                                    ),'-') AS keterangan_selisih
            FROM master_barang a
            LEFT JOIN master_gudang b ON a.id_gudang=b.id
            LEFT JOIN master_satuan c ON a.id_satuan=c.id
            LEFT JOIN stok_awal_detail d ON a.id=d.id_barang
            LEFT JOIN stok_awal_header e ON d.id_header=e.id
            WHERE a.id_gudang='$gudang'
            AND e.tgl LIKE '$bulan_trx-%'
            AND a.id='$idbarang'

        ");

        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]->id;
            $bulan_transaksi = $data[$i]->bulan_trx;
            $nama_barang = $data[$i]->nama_barang;
            $kode_barang = $data[$i]->kode_barang;
            $nama_satuan = $data[$i]->nama_satuan;
            $nama_gudang = $data[$i]->nama_gudang;
            $stok_minimal = $data[$i]->stok_minimal;
            $tgl = $data[$i]->tgl;
            $stok_awal = $data[$i]->stok_awal;
            $stok_in = $data[$i]->stok_in;
            $stok_out = $data[$i]->stok_out;
            $adjusmen_in = $data[$i]->adjusmen_in;
            $adjusmen_out = $data[$i]->adjusmen_out;
            $selisih = $data[$i]->selisih;
            $keterangan_selisih = $data[$i]->keterangan_selisih;

            $stok_sistem = $stok_awal + $stok_in - $stok_out + $adjusmen_in - $adjusmen_out;
            $data[$i]->stok_sistem = $stok_sistem;
            $stok_fisik = $stok_sistem + $selisih;

            $stok_in_fin = $stok_in + $adjusmen_in;
            $stok_out_fin = $stok_out + $adjusmen_out;
        }

        return $data;
    }
}
