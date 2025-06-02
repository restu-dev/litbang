<table id="tabel_jumlah_report" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Asset</th>
            <th>Detail</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>Aktif</td>
            <td>{{ $data['aktif_user'] }}</td>
            <td>{{ $data['aktif_asset'] }}</td>
            <td>
                <button id="tampil_aktif" type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
            </td>
        </tr>
        <tr>
            <td>Daftar</td>
            <td>{{ $data['daftar_user'] }} / Bulan</td>
            <td>{{ $data['daftar_asset'] }} / Bulan</td>
            <td>
                <button id="tampil_daftar" type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
            </td>
        </tr>
        <tr>
            <td>Non Aktif</td>
            <td>{{ $data['non_aktif_user'] }} / Bulan</td>
            <td>{{ $data['non_aktif_asset'] }} / Bulan</td>
            <td>
                <button id="tampil_non_aktif" type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
            </td>
        </tr>

    </tbody>
</table>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table id="tabel_detail_user" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Aktif</th>
                            <th>Non Aktif</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <table id="tabel_detail_asset" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Unit</th>
                            <th>Keterangan</th>
                            <th>Aktif</th>
                            <th>Non Aktif</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>
    </div>
</div>


<script>
    var tahun = {{ $tahun }};
    var bulan = {{ $bulan }};

    $("#tabel_jumlah_report").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel"],
    }).buttons().container().appendTo('#tabel_jumlah_report_wrapper .col-md-6:eq(0)');

    // tampil_aktif
    $("#tampil_aktif").on("click", function(e) {

        $("#modal-detail").modal('show');
        $(".modal-title").html('Aktif');

        loadDataUser('AKTIF');
        loadDataAsset('AKTIF');
    });

    // tampil_daftar
    $("#tampil_daftar").on("click", function(e) {
        $("#modal-detail").modal('show');
        $(".modal-title").html('Daftar');

        loadDataUser('DAFTAR');
        loadDataAsset('DAFTAR');
    });

    // tampil_non_aktif
    $("#tampil_non_aktif").on("click", function(e) {
        $("#modal-detail").modal('show');
        $(".modal-title").html('Non Aktif');

        loadDataUser('NON AKTIF');
        loadDataAsset('NON AKTIF');
    });

    // load tabel user wifi
    function loadDataUser(status) {
        $('.loader').show();

        $('#tabel_detail_user').DataTable().destroy();

        $.post('{{ URL::to('load-table-detail-data-user') }}', {
            tahun,
            bulan,
            status,
            _token: '{{ csrf_token() }}'
        }, function(e) {
            var tabel = $("#tabel_detail_user").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel"],
                "data": e,
                "columns": [{
                        data: 'kode',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        className: "text-center",
                    },
                    {
                        data: 'kode',
                        className: "text-left",
                    },
                    {
                        data: 'nama',
                        className: "text-left",
                    },
                    {
                        data: 'unit',
                        className: "text-left",
                    },
                    {
                        data: 'keterangan',
                        className: "text-left",
                    },
                    {
                        data: 'aktive_date',
                        className: "text-left",
                    },
                    {
                        data: 'non_aktive_date',
                        className: "text-left",
                    },

                ]
            }).buttons().container().appendTo('#tabel_detail_user_wrapper .col-md-6:eq(0)');

            tabel.on('order.dt search.dt', function() {
                tabel.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            });

        }).done(function(data) {
            $('.loader').hide();
        });
    }

    // load tabel user asset
    function loadDataAsset(status) {
        $('.loader').show();

        $('#tabel_detail_asset').DataTable().destroy();

        $.post('{{ URL::to('load-table-detail-data-asset') }}', {
            tahun,
            bulan,
            status,
            _token: '{{ csrf_token() }}'
        }, function(e) {
            var tabel = $("#tabel_detail_asset").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel"],
                "data": e,
                "columns": [{
                        data: 'kode',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        className: "text-center",
                    },
                    {
                        data: 'kode',
                        className: "text-left",
                    },
                    {
                        data: 'nama',
                        className: "text-left",
                    },
                    {
                        data: 'unit',
                        className: "text-left",
                    },
                    {
                        data: 'keterangan',
                        className: "text-left",
                    },
                    {
                        data: 'aktive_date',
                        className: "text-left",
                    },
                    {
                        data: 'non_aktive_date',
                        className: "text-left",
                    },

                ]
            }).buttons().container().appendTo('#tabel_detail_asset_wrapper .col-md-6:eq(0)');

            tabel.on('order.dt search.dt', function() {
                tabel.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            });

        }).done(function(data) {
            $('.loader').hide();
        });
    }
</script>
