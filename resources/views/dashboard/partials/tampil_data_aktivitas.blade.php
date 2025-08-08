<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0" style="cursor: default;">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 10px">#</th>
                <th>Nama</th>
                <th>Aktivitas</th>
                <th style="width: 80px">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $i => $pegawai)
                <tr>
                    <td class="text-center">{{ $i + 1 }}.</td>
                    <td>
                        <div class="text-muted small">{{ $pegawai->created_at }}</div>
                        <div class="fw-semibold">{{ $pegawai->nama_pegawai }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $pegawai->nama_program_kerja }}</div>
                        <div class="text-muted">{{ $pegawai->uraian_kegiatan }}</div>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark d-block text-center">
                            {{ $pegawai->nama_status_pencapaian }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        Tidak ada data absen
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
