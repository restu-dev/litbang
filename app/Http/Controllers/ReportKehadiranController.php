<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerjaTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReportKehadiranController extends Controller
{
    public function index()
    {
        $title = 'Report Kehadiran';
        $active = 'report-kehadiran';

        return view('report-kehadiran.index', [
            'title' => $title,
            'active' => $active,
        ]);
    }

    public function getKet($nip, $tgl)
    {
        $data = DB::select("SELECT * 
					FROM simpia.Master_Perizinan_Detail
					WHERE no_pegawai='$nip'
					AND tanggal_perizinan='$tgl'
					AND approvel1='Approve'
					AND approvel2='Approve'");

        $keterangan = '';
        foreach ($data as $d) {
            $keterangan = $d->keterangan;
        }

        return $keterangan;
    }

    public function getKeteranganIzin($nip, $kode)
    {
        // cari tgl izin
        $data = DB::select("SELECT * 
							FROM simpia.Data_Jam_Kerja
							WHERE no_pegawai='$nip'
							AND kode_ijin_cuti='$kode'");


        // cari keterangan di Master_Perizinan_Detail
        // $ket_izin="";
        for ($i = 0; $i < count($data); $i++) {

            $tgl = $data[$i]->tanggal_mulai;

            // cari keterangan
            $data_ket = $this->getKet($nip, $tgl);

            // var_dump($data_ket);

            $ket_izin = $data_ket;
        }

        return $ket_izin;
    }

    public function getKeteranganTukarLibur($nip, $tgltkl)
    {
        // cari di Data_Jam_Kerja by no_pegaawi dan tgl
        // get kode_tukar_libur
        $data = DB::select("SELECT * 
							FROM simpia.Data_Jam_Kerja
							WHERE no_pegawai='$nip'
							and tanggal_mulai='$tgltkl'
							order by tanggal_mulai asc");

        // cari kode_tukar_libur yg sama
        $kode_tukar_libur = '';

        foreach ($data as $d) {
            $kode_tukar_libur = $d->kode_tukar_libur;
        }

        if ($kode_tukar_libur == '' || NULL) {
            $keterangan = 'Kode tukar libur tidak diketemukan';
        } else {
            $data2 = DB::select("SELECT * 
							FROM simpia.Data_Jam_Kerja
							WHERE no_pegawai='$nip'
							and kode_tukar_libur='$kode_tukar_libur'
							order by kode_tukar_libur asc");

            $tgl = [];
            foreach ($data2 as $d) {
                $tgl[] = $d->tanggal_mulai;
            }

            // ket tukar libur
            if (empty($tgl[0])) {
                $awal = '-';
            } else {
                $awal = date('d-m-Y', strtotime($tgl[0]));
            }

            if (empty($tgl[1])) {
                $akhir = '-';
            } else {
                $akhir = date('d-m-Y', strtotime($tgl[1]));
            }


            // var_dump('restuu awal : ' . $awal).'<br>';
            // var_dump('restuu akhir: '.$akhir). '<br>';

            // $keterangan ='Dari '.$awal.' tukar ke '.$akhir;
            $keterangan = 'Dari <b>' . $awal . '</b> tukar ke <b>' . $akhir . '</b>';
        }

        return $keterangan;
    }

    // loadTabelReportJurnalHarian
    public function loadTabelReportKehadiran(Request $request)
    {
        $nip = $request->nip;
        $month = $request->month;
        $year = $request->year;
        $max_day = $request->max_day;

        if ($month <= 9) {
            $monthsql = "0$month";
        } else {
            $monthsql = $month;
        }

        $menit = 0;
        $tanggal_choosen = "$year-$monthsql";
        $hari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
        $data = DB::select('SELECT simpia.getJamKerja2(\'' . $nip . '\',str_to_date(concat(\'' . $tanggal_choosen . '-\',tanggal),\'%Y-%m-%d\'),0) as tgl,
														\'\' as jam_kerja, 
														\'\' as shift1_tgl, 
														\'-\' as jam_masuk, 
														\'-\' as jam_pulang,
														da.shift as fixshift,
														\'\' as masuk, 
														\'\' as pulang, 
														\'\' as masuk1, 
														\'\' as pulang1,
														\'\' as masuk2, 
														\'\' as pulang2, 
														date_format(masuk,\'%d\') as tgl_masuk, 
														date_format(pulang,\'%d\') as tgl_pulang,
														date_format(str_to_date(concat(\'' . $tanggal_choosen . '-\',tanggal),\'%Y-%m-%d\'),\'%w\') as hari, 
														da.keterangan, 
														masuk_manual,
														pulang_manual,
														date_format(str_to_date(concat(\'' . $tanggal_choosen . '-\',tanggal),\'%Y-%m-%d\'),\'%d-%m-%Y\') as tanggal,
														\'\' as terlambat,
														\'\' as pulang_awal,
														\'\' as masuk_awal,
														\'\' as ket_tukar_libur,
														\'\' as lembur
							FROM simpia.Data_Tanggal_Absen dt 
							left outer join simpia.Data_Absensi_Pegawai da on(dt.tanggal=date_format(date,\'%d\') 
																		and date_format(date,\'%Y-%m\')=\'' . $tanggal_choosen . '\' 
																		and no_pegawai=\'' . $nip . '\')
							where usia_tanggal=' . $max_day . ' order by tanggal');

        for ($i = 0; $i < count($data); $i++) {
            // 06:53-14:37,07:00-14:30,;,,
            //var_dump($data[$i]->tgl);

            $xx1 = explode(';', $data[$i]->tgl);
            if (count($xx1) > 1) {
                $xx1[0]; // shift 1
                $xx1[1]; // shift 2
            }

            // 06:53-14:37,07:00-14:30,
            // shift 1
            $xx_jam_shift1_tgl = explode(',', $xx1[0]);
            if (count($xx_jam_shift1_tgl) > 1) {
                $data[$i]->jam_shift1_tgl            = $xx_jam_shift1_tgl[0];
                $data[$i]->jam_kerja1                   = $xx_jam_shift1_tgl[1];
                $data[$i]->keterangan_shift1_tgl     = $xx_jam_shift1_tgl[2];
            }

            $dm1 = explode('-', $data[$i]->jam_shift1_tgl);
            if (count($dm1) > 1) {
                $data[$i]->masuk1            = $dm1[0];
                $data[$i]->pulang1           = $dm1[1];
            }

            // shift 2
            $xx_jam_shift2_tgl = explode(',', $xx1[1]);
            if (count($xx_jam_shift2_tgl) > 1) {
                $data[$i]->jam_shift2_tgl            = $xx_jam_shift2_tgl[0];
                $data[$i]->jam_kerja2                   = $xx_jam_shift2_tgl[1];
                $data[$i]->keterangan_shift2_tgl     = $xx_jam_shift1_tgl[2];
            }

            $dm2 = explode('-', $data[$i]->jam_shift2_tgl);
            if (count($dm2) > 1) {
                $data[$i]->masuk2            = $dm2[0];
                $data[$i]->pulang2           = $dm2[1];
            }

            if ($data[$i]->fixshift == 1) {
                $data[$i]->jam_kerja = $data[$i]->jam_kerja1;
                $data[$i]->masuk = $data[$i]->masuk1;
                $data[$i]->pulang = $data[$i]->pulang1;
            } else {
                $data[$i]->jam_kerja = $data[$i]->jam_kerja1;
                $data[$i]->masuk = $data[$i]->masuk1;
                $data[$i]->pulang = $data[$i]->pulang1;
            }


            $data[$i]->hari = $hari[$data[$i]->hari];

            if (substr($data[$i]->jam_kerja, 5, 1) == '-') {
                //var_dump(substr($data[$i]->jam_kerja,5,1));
                if ($data[$i]->masuk == "00:00:00" && $data[$i]->masuk_manual != '') {
                    $data[$i]->masuk = $data[$i]->masuk_manual . ":00";
                }

                if ($data[$i]->pulang == "00:00:00" && $data[$i]->pulang_manual != '') {
                    $data[$i]->pulang = $data[$i]->pulang_manual . ":00";
                }

                $data[$i]->jam_masuk = substr($data[$i]->jam_kerja, 0, 5);
                if ($data[$i]->masuk_manual == '' && $data[$i]->pulang != '00:00:00' && $data[$i]->masuk != '00:00:00') {
                    if (substr($data[$i]->jam_masuk, 2, 1) == ':' && $data[$i]->masuk != '') {
                        $menit = substr($data[$i]->masuk, 0, 2) * 1 - substr($data[$i]->jam_masuk, 0, 2) * 1;
                        $menit = $menit * 60 + (substr($data[$i]->masuk, 3, 2) * 1 - substr($data[$i]->jam_masuk, 3, 2) * 1);
                        if ($menit > 0) {
                            if (floor($menit / 60) > 0) {
                                if (floor($menit / 60) < 10) {
                                    $data[$i]->terlambat = '0' . floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->terlambat = $data[$i]->terlambat . ':' . $menit;
                                } else {
                                    $data[$i]->terlambat = floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->terlambat = $data[$i]->terlambat . ':' . $menit;
                                }
                            } else if ($menit > 9)
                            $data[$i]->terlambat = '00:' . $menit;
                            else
                            $data[$i]->terlambat = '00:0' . $menit;
                        }

                        $menit = substr($data[$i]->jam_masuk, 0, 2) * 1 - substr($data[$i]->masuk, 0, 2) * 1;
                        $menit = $menit * 60 + (substr($data[$i]->jam_masuk, 3, 2) * 1 - substr($data[$i]->masuk, 3, 2) * 1);
                        if ($menit > 0) {
                            if (floor($menit / 60) > 0) {
                                if (floor($menit / 60) < 10) {
                                    $data[$i]->masuk_awal = '0' . floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->masuk_awal = $data[$i]->masuk_awal . ':' . $menit;
                                } else {
                                    $data[$i]->masuk_awal = floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->masuk_awal = $data[$i]->masuk_awal . ':' . $menit;
                                }
                            } else if ($menit > 9)
                            $data[$i]->masuk_awal = '00:' . $menit;
                            else
                            $data[$i]->masuk_awal = '00:0' . $menit;
                        }
                    }
                }

                // else $data[$i]->terlambat=$data[$i]->masuk_manual;

                $data[$i]->jam_pulang = substr($data[$i]->jam_kerja, 6, 5);
                if ($data[$i]->pulang_manual == '' && $data[$i]->pulang != '00:00:00' && $data[$i]->masuk != '00:00:00') {
                    if (substr($data[$i]->jam_pulang, 2, 1) == ':' && $data[$i]->pulang != '') {
                        $menit = substr($data[$i]->jam_pulang, 0, 2) * 1 - substr($data[$i]->pulang, 0, 2) * 1;
                        $menit = $menit * 60 + (substr($data[$i]->jam_pulang, 3, 2) * 1 - substr($data[$i]->pulang, 3, 2) * 1);
                        if ($menit > 0 && $data[$i]->tgl_masuk == $data[$i]->tgl_pulang) {
                            if (floor($menit / 60) > 0) {
                                if (floor($menit / 60) < 10) {
                                    $data[$i]->pulang_awal = '0' . floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->pulang_awal = $data[$i]->pulang_awal . ':' . $menit;
                                } else {
                                    $data[$i]->pulang_awal = floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->pulang_awal = $data[$i]->pulang_awal . ':' . $menit;
                                }
                            } else if ($menit > 9)
                            $data[$i]->pulang_awal = '00:' . $menit;
                            else
                            $data[$i]->pulang_awal = '00:0' . $menit;
                        }

                        $menit = substr($data[$i]->pulang, 0, 2) * 1 - substr($data[$i]->jam_pulang, 0, 2) * 1;
                        $menit = $menit * 60 + (substr($data[$i]->pulang, 3, 2) * 1 - substr($data[$i]->jam_pulang, 3, 2) * 1);
                        if ($menit > 0 && $data[$i]->tgl_masuk == $data[$i]->tgl_pulang) {
                            if (floor($menit / 60) > 0) {
                                if (floor($menit / 60) < 10) {
                                    $data[$i]->lembur = '0' . floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->lembur = $data[$i]->lembur . ':' . $menit;
                                } else {
                                    $data[$i]->lembur = floor($menit / 60);
                                    if ($menit - (floor($menit / 60) * 60) > 9)
                                        $menit = $menit - (floor($menit / 60) * 60);
                                    else
                                        $menit = '0' . ($menit - (floor($menit / 60) * 60));
                                    $data[$i]->lembur = $data[$i]->lembur . ':' . $menit;
                                }
                            } else if ($menit > 9)
                            $data[$i]->lembur = '00:' . $menit;
                            else
                            $data[$i]->lembur = '00:0' . $menit;
                        }
                    }
                }
                // else $data[$i]->pulang_awal=$data[$i]->pulang_manual;
            } else {
                $data[$i]->jam_masuk = $data[$i]->jam_kerja;
                $data[$i]->jam_pulang = $data[$i]->jam_kerja;
            }

            // jika tukar libur => keterangan
            // tgl awal akhir tukar libur
            if ($data[$i]->jam_masuk == 'Tukar Libur') {
                $tgltkl = date('Y-m-d', strtotime($data[$i]->tanggal));
                $ket_tkl = $this->getKeteranganTukarLibur($nip, $tgltkl);
                $data[$i]->ket_tukar_libur = $ket_tkl;
            }

            if ($data[$i]->jam_masuk == 'Sakit') {
                $tgl = date('Y-m-d', strtotime($data[$i]->tanggal));
                $ket_sakit = $this->getKeteranganIzin($nip, 'sakit');

                $data[$i]->keterangan = $ket_sakit;

                // var_dump($data[$i]->jam_masuk);
                // var_dump($ket_tkl);
            }
        }

        return $data;
    }

}
