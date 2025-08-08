<canvas id="chart_1"></canvas>


<script src="{{ asset('chart/chart.js') }}"></script>

<script>
    var filter_tahun = "{{ $data['filter_tahun'] }}";
    var filter_ada_tidak_program_kerja = "{{ $data['filter_ada_tidak_program_kerja'] }}";
    var filter_jenis_kegiatan = "{{ $data['filter_jenis_kegiatan'] }}";
    var filter_status_pencapaian = "{{ $data['filter_status_pencapaian'] }}";
    var filter_bidang = "{{ $data['filter_bidang'] }}";

    chartSatu(filter_tahun, filter_ada_tidak_program_kerja, filter_jenis_kegiatan, filter_status_pencapaian,
        filter_bidang);


    /*
    function chartSatu(filter_tahun, filter_ada_tidak_program_kerja, filter_jenis_kegiatan, filter_status_pencapaian,
        filter_bidang) {

        var ctx = document.getElementById('chart_1').getContext('2d');

        $.post('{{ URL::to('chart-jml-aktivitas') }}', {
            filter_tahun,
            filter_ada_tidak_program_kerja,
            filter_jenis_kegiatan,
            filter_status_pencapaian,
            filter_bidang,
            _token: '{{ csrf_token() }}'

        }, function(e) {

            var chart = new Chart(ctx, {
                type: 'bar',

                data: {

                    labels: e.labels,

                    datasets: [

                        {
                            label: 'done',
                            data: e.done,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Biru Muda
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'process',
                            data: e.process,
                            backgroundColor: 'rgba(255, 206, 86, 0.2)', // Kuning
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'cancel',
                            data: e.cancel,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)', // Merah Muda
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'failed',
                            data: e.failed,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)', // Ungu
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'scheduled',
                            data: e.scheduled,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Tosca
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'in review',
                            data: e.in_review,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)', // Oranye
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'revision',
                            data: e.revision,
                            backgroundColor: 'rgba(201, 203, 207, 0.2)', // Abu-abu
                            borderColor: 'rgba(201, 203, 207, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'hold',
                            data: e.hold,
                            backgroundColor: 'rgba(0, 128, 0, 0.2)', // Hijau Tua
                            borderColor: 'rgba(0, 128, 0, 1)',
                            borderWidth: 1
                        }

                    ]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

        });
    }
    */

    function chartSatu(filter_tahun, filter_ada_tidak_program_kerja, filter_jenis_kegiatan, filter_status_pencapaian,
        filter_bidang) {

        var ctx = document.getElementById('chart_1').getContext('2d');

        $.post('{{ URL::to('chart-jml-aktivitas') }}', {
            filter_tahun,
            filter_ada_tidak_program_kerja,
            filter_jenis_kegiatan,
            filter_status_pencapaian,
            filter_bidang,
            _token: '{{ csrf_token() }}'
        }, function(e) {

            // Hancurkan chart lama jika ada (hindari duplikasi)
            if (window.chartSatuInstance) {
                window.chartSatuInstance.destroy();
            }

            window.chartSatuInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: e.labels,
                    datasets: [{
                            label: 'done',
                            data: e.done,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'process',
                            data: e.process,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'cancel',
                            data: e.cancel,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'failed',
                            data: e.failed,
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'scheduled',
                            data: e.scheduled,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'in review',
                            data: e.in_review,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'revision',
                            data: e.revision,
                            backgroundColor: 'rgba(201, 203, 207, 0.2)',
                            borderColor: 'rgba(201, 203, 207, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'hold',
                            data: e.hold,
                            backgroundColor: 'rgba(100, 100, 100, 0.2)',
                            borderColor: 'rgba(100, 100, 100, 1)',
                            borderWidth: 1
                        },
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        });
    }
</script>
