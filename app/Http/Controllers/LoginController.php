<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return Redirect::to('data-dashboard');
        }

        return view('login.index');
    }

    public function checklogin($credentials)
    {
        $user = $credentials['user'];
        $password = $credentials['password'];
        $recaptcha = $credentials['g-recaptcha-response'];

        $lanjut = true;
        $sukses = "Y";
        $pesan  = "Berhasil";
        $user_final  = "";

        $checks = \DB::select("SELECT p.no_pegawai AS id_pegawai,
                                p.NIP,
                                nama_pegawai,
                                ur.user,
                                ur.status_ajukan_user,
                                ur.password2,
                                k.*,
                                ul.id_level
                                FROM simpia.Data_Induk_Pegawai p
                                LEFT JOIN (
                                    SELECT nama_jabatan, 
                                    nama_jenjang, 
                                    nama_unit, p.* 
                                    FROM simpia.Penempatan_Kerja_Pegawai p
                                    LEFT JOIN simpia.Jabatan j ON p.kode_jabatan=j.kode_jabatan
                                    LEFT JOIN simpia.Jenjang j2 ON p.kode_jenjang=j2.kode_jenjang
                                    LEFT JOIN simpia.Unit u ON p.kode_unit=u.kode_unit
                                    WHERE resign IS NULL OR resign=''
                                ) AS k ON p.no_pegawai=k.no_pegawai
                                LEFT JOIN simpia.users ur ON ur.no_pegawai=p.no_pegawai
                                LEFT JOIN wifi.user_level ul ON p.NIP=ul.nip
                                WHERE tanggal_penempatan LIKE (SELECT MAX(tanggal_penempatan) FROM simpia.Penempatan_Kerja_Pegawai WHERE no_pegawai=p.no_pegawai)
                                AND p.NIP='$user'
                                AND ul.id_level <> ''
                                GROUP BY id_pegawai");


        //cek ada tidak user
        foreach ($checks as $ck) {
            $status_ajukan_user = $ck->status_ajukan_user;
            $db_user = $ck->user;
            $db_pass = $ck->password2;
            $level = $ck->id_level;
            $kode_unit = $ck->kode_unit;
            $kode_jenjang = $ck->kode_jenjang;
            $nama_pegawai = $ck->nama_pegawai;
            $nama_jenjang = $ck->nama_jenjang;
            $nama_unit = $ck->nama_unit;
            $no_pegawai = $ck->no_pegawai;
            $nip = $ck->NIP;
        }

        if ($lanjut) {
            if (empty($db_user)) {
                $lanjut = false;
                $sukses = "T";
                $pesan  = "User $user Tidak di Temukan / tidak memiliki akses!";
            }
        }

        if ($lanjut) {
            if ($status_ajukan_user == "TIDAK") {
                $lanjut = false;
                $sukses = "T";
                $pesan  = "Status Pengajuan User belum disetujui..";
            }
        }

        if ($lanjut) {
            if ($status_ajukan_user == "") {
                $lanjut = false;
                $sukses = "T";
                $pesan  = "Belum mengajukan user, Mohon Ajukan User..";
            }
        }

        if ($lanjut) {
            if ($level == "") {
                $lanjut = false;
                $sukses = "T";
                $pesan  = "User ESA tidak diberi aksek untuk aplikasi ini..";
            }
        }

        if ($lanjut) {
            if (!password_verify($password, $db_pass)) {
                $lanjut = false;
                $sukses = "T";
                $pesan  = "Password Tidak Sesuai";
            }
        }

        $user_final = empty($db_user) ? '' : $db_user;
        $level = empty($level) ? '' : $level;
        $kode_unit = empty($kode_unit) ? '' : $kode_unit;
        $kode_jenjang = empty($kode_jenjang) ? '' : $kode_jenjang;
        $nama_pegawai = empty($nama_pegawai) ? '' : $nama_pegawai;
        $nama_jenjang = empty($nama_jenjang) ? '' : $nama_jenjang;
        $nama_unit = empty($nama_unit) ? '' : $nama_unit;
        $no_pegawai = empty($no_pegawai) ? '' : $no_pegawai;
        $nip = empty($nip) ? '' : $nip;

        return array(
            "sukses" => $sukses,
            "pesan" => $pesan,
            "user_final" => $user_final,
            "level" => $level,
            "kode_unit" => $kode_unit,
            "kode_jenjang" => $kode_jenjang,
            "nama_pegawai" => $nama_pegawai,
            "nama_jenjang" => $nama_jenjang,
            "nama_unit" => $nama_unit,
            "no_pegawai" => $no_pegawai,
            "nip" => $nip
        );
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->validate([
            'user' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        $checklogin = $this->checklogin($credentials);

        $sukses = $checklogin['sukses'];
        $pesan = $checklogin['pesan'];
        $user_final = $checklogin['user_final'];
        $level = $checklogin['level'];
        $kode_unit = $checklogin['kode_unit'];
        $kode_jenjang = $checklogin['kode_jenjang'];
        $nama_pegawai = $checklogin['nama_pegawai'];
        $nama_jenjang = $checklogin['nama_jenjang'];
        $nama_unit = $checklogin['nama_unit'];
        $no_pegawai = $checklogin['no_pegawai'];
        $nip = $checklogin['nip'];

        if ($sukses == "Y") {
            $cek = ['user' => $user_final, 'password' => $request->password];

            if (Auth::attempt($cek)) {
                $request->session()->regenerate();
                $nama_level = Level::where('id', $level)->first()->name;

                session([
                    'level' => $level,
                    'nama_level' => $nama_level,
                    'kode_unit' => $kode_unit,
                    'kode_jenjang' => $kode_jenjang,
                    'nama_jenjang' => $nama_jenjang,
                    'nama_unit' => $nama_unit,
                    'nama_pegawai' => $nama_pegawai,
                    'no_pegawai' => $no_pegawai,
                    'nip' => $nip,
                ]);

                $jam = now();
                UserLevel::where('nip', $request->user)->update(['last_login' => $jam]);
                return redirect()->intended('data-dashboard');
            }
        }

        return back()->with('loginError', $pesan);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


