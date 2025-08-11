<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
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
        $awal = $tahunAjar ? $tahunAjar->awal : null;

        $title = 'Agenda ' . $nama_tahun_ajaran . ' - ' . session('nama_bidang');;
        $active = 'agenda';

        // Ambil 1 tahun ajaran sebelumnya secara otomatis
        $tahunLalu = "";
        if ($awal) {
            $tahunLalu = DB::table('master_tahun_pelajaran')
                ->where('awal', '<', $awal)
                ->orderByDesc('awal')
                ->first();
        }

        $id_tahun_ajaran_lalu = $tahunLalu ? $tahunLalu->id : null;
        $nama_tahun_ajaran_lalu = $tahunLalu ? $tahunLalu->nama : null;

        $adaProgramKerja = "";
        if ($id_tahun_ajaran) {
            $adaProgramKerja = DB::table('program_kerja_tahunan')
                ->where('id_tahun_pelajaran', $id_tahun_ajaran)
                ->where('penanggung_jawab', $no_pegawai)
                ->exists();
        }

        return view('agenda.index', [
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

    public function events()
    {
        $agendas = Agenda::all();

        $events = [];

        foreach ($agendas as $agenda) {
            $events[] = [
                'id'    => $agenda->id,
                'title' => $agenda->keterangan,
                'start' => $agenda->tgl_awal,
                'end'   => $agenda->tgl_akhir ? date('Y-m-d', strtotime($agenda->tgl_akhir . ' +1 day')) : null,
                'color' => $agenda->jenis === 'semua_bidang' ? '#007bff' : '#28a745'
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
            'jenis' => 'required|in:semua_bidang,per_bidang'
        ]);

        Agenda::create($request->all());

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
            'jenis' => 'required|in:semua_bidang,per_bidang'
        ]);

        $agenda = Agenda::findOrFail($id);

        $agenda->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
