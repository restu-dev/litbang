<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AgendaController extends Controller
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

        $title = 'Agenda ' . $nama_tahun_ajaran . ' - ' . session('nama_bidang');;
        $active = 'agenda';

        // cek akses agende berdasarkan mapping mapping_user_agenda
        $nip = session('nip');
        $id_user_level = UserLevel::where('nip', $nip)->first()->id;

        $mappingAgenda = DB::table('mapping_user_agenda')
            ->where('id_user_level', $id_user_level)
            ->first();

        return view('agenda.index', [
            'title' => $title,
            'active' => $active,
            'tahunAjar' => $tahunAjar,
            'id_tahun_ajaran' => $id_tahun_ajaran,
            'nama_tahun_ajaran' => $nama_tahun_ajaran,
            'mappingAgenda' => $mappingAgenda
        ]);
    }

    public function events()
    {
        // $agendas = Agenda::all();

        $agendas = DB::select("SELECT a.*, b.warna, b.nama AS nama_bidang
                                FROM agenda a
                                LEFT JOIN master_bidang b ON a.id_bidang=b.id");

        $events = [];

        foreach ($agendas as $agenda) {
            $jenis = $agenda->jenis;

            if($jenis=="semua"){
                $warna = "#1A2A80";
                $title = "(Semua Bidang) - ". $agenda->keterangan;
            }else{
                $warna = $agenda->warna;
                $title = $agenda->nama_bidang.' - '.$agenda->keterangan;
            }

            $events[] = [
                'id'    => $agenda->id,
                'title' => $title,
                'start' => $agenda->tgl_awal,
                'end'   => $agenda->tgl_akhir ? date('Y-m-d', strtotime($agenda->tgl_akhir . ' +1 day')) : null,
                'color' => $warna
            ];
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'nullable|date',
            'keterangan' => 'required|string',
            'jenis' => 'required'
        ]);

        $hariIni = Carbon::now()->toDateString();

        $tahunAjar = DB::table('master_tahun_pelajaran')
            ->whereDate('awal', '<=', $hariIni)
            ->whereDate('akhir', '>=', $hariIni)
            ->first();

        // cek ada tidak jenis semua ditgl itu
        $cekAgenda = Agenda::where('tgl_awal', $request->tgl_awal)
            ->where('jenis', 'semua')
            ->first();

        if($cekAgenda){
            return response()->json(['success' => false]);
        }

        Agenda::create([
            'tgl_awal'    => $request->tgl_awal,
            'tgl_akhir'   => $request->tgl_akhir,
            'keterangan'  => $request->keterangan,
            'jenis'       => $request->jenis,
            'id_tahun_pelajaran' => $tahunAjar->id,
            'id_bidang'   => session('id_bidang'),
            'user_created' => session('no_pegawai'),
            'created_at' =>  date('Y-m-d H:i:s')
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        return Agenda::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'nullable|date',
            'keterangan' => 'required|string',
            'jenis' => 'required'
        ]);

        $agenda = Agenda::findOrFail($id);

        $agenda->update([
            'tgl_awal'    => $request->tgl_awal,
            'tgl_akhir'   => $request->tgl_akhir,
            'keterangan'  => $request->keterangan,
            'jenis'       => $request->jenis,
            'id_bidang'   => session('id_bidang'),
            'user_updated' => session('no_pegawai'),
            'updated_at' =>  date('Y-m-d H:i:s')
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function listAgenda()
    {
        $hariIni = Carbon::now()->toDateString();

        $tahunAjar = DB::table('master_tahun_pelajaran')
            ->whereDate('awal', '<=', $hariIni)
            ->whereDate('akhir', '>=', $hariIni)
            ->first();

        $id_tahun_ajaran = $tahunAjar ? $tahunAjar->id : null;

        $agendas = DB::table('agenda')
            ->join('master_bidang', 'agenda.id_bidang', '=', 'master_bidang.id')
            ->select('agenda.*', 'master_bidang.nama as nama_bidang')
            ->where('agenda.id_tahun_pelajaran', $id_tahun_ajaran)
            ->orderBy('agenda.tgl_awal', 'desc')
            ->get();

        for($i=0; $i<count($agendas);$i++){
            $tgl_awal = Carbon::parse($agendas[$i]->tgl_awal)->format('d-m-Y');
            $tgl_akhir = $agendas[$i]->tgl_akhir ? Carbon::parse($agendas[$i]->tgl_akhir)->format('d-m-Y') : '-';

            $agendas[$i]->tgl_awal = $tgl_awal;
            $agendas[$i]->tgl_akhir = $tgl_akhir;

            if($agendas[$i]->jenis == 'semua'){
                $agendas[$i]->nama_bidang = '(Semua Bidang)';
            }
        }

        return $agendas;
    }
}
