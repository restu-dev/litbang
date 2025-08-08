{{-- <div class="row m-0"> --}}

    @php
        // Daftar warna background gradient untuk tiap kartu
        $warna = [
            'linear-gradient(135deg, #508ae1 0%, #02029b 100%)',
            'linear-gradient(135deg, #28a745 0%, #0c662d 100%)',
            'linear-gradient(135deg, #ffc107 0%, #d39e00 100%)',
            'linear-gradient(135deg, #e83e8c 0%, #bd2164 100%)',
            'linear-gradient(135deg, #17a2b8 0%, #0f6674 100%)',
            'linear-gradient(135deg, #6f42c1 0%, #4c2885 100%)',
            'linear-gradient(135deg, #fd7e14 0%, #b15d0c 100%)',
            'linear-gradient(135deg, #dc3545 0%, #a71d2a 100%)',
        ];
    @endphp

    <div class="row">
        @forelse ($dataPegawai as $i => $pegawai)
            @php
                // Ambil warna berdasarkan index, lalu ulang jika habis
                $background = $warna[$i % count($warna)];
            @endphp
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                <div class="card shadow border-0 h-100 text-white" style="background: {{ $background }};">
                    <div class="card-body text-center p-3">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark fs-6 px-2 py-1">
                                {{ $i + 1 }}. {{ $pegawai['nama_pegawai'] }}
                            </span>
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-clock-history me-1"></i>
                            <span class="fw-bold">Jam Kerja:</span>
                            <span>{{ $pegawai['data_absesin'] ?? '-' }}</span>
                        </div>
                        <div>
                            <i class="bi bi-calendar-date me-1"></i>
                            <span class="fw-bold">Tanggal:</span>
                            <span>
                                {{ $pegawai['tgl'] ? \Carbon\Carbon::parse($pegawai['tgl'])->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">Tidak ada data absen</p>
            </div>
        @endforelse
    </div>




{{-- </div> --}}
