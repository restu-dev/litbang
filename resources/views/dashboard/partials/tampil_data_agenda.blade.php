<div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
    <table class="table table-bordered table-hover align-middle mb-0" style="cursor: default;">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Bidang Pembuat</th>
                <th>Tgl Awal</th>
                <th>Tgl Akhir</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $i => $agenda)
                <tr>
                    <td class="text-center">{{ $i + 1 }}.</td>
                    <td>
                        <div class="text-muted small">{{ $agenda->nama_bidang }}</div>
                        <div class="fw-semibold">{{ $agenda->jenis }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $agenda->tgl_awal }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $agenda->tgl_akhir }}</div>
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
