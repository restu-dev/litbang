<div class="card">

    <div class="card-header">
        <h3 class="card-title">Jumlah Akses</h3>
    </div>

    <div class="card-body">
        <canvas id="chart_1"></canvas>
    </div>
</div>

<script src="{{ asset('chart/chart.js') }}"></script>

<script>
    $(function() {
        chartSatu();
    });

    function chartSatu() {
        var ctx = document.getElementById('chart_1').getContext('2d');

        $.post('{{ URL::to('load-chart-satu') }}', {
            _token: '{{ csrf_token() }}'
        }, function(e) {
            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: e.labels,
                    datasets: [{
                        label: 'Data',
                        data: e.jml,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Chart Doughnut'
                    }
                }
            })
        });


    }
</script>
