<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <script type="text/javascript">
        function display_c() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct()', refresh)
        }

        function display_ct() {
            var x = new Date()
            var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
            x1 = x1 + " - " + x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds();
            document.getElementById('ct').innerHTML = x1;
            display_c();
        }
    </script>
    <title>Qr Scan</title>
</head>

<body onload=display_ct();>
    <br>
    <div style="padding: 0px !important" class="container border border-secondary">
        {{-- navbar --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="/img/logo-nuris.png" alt="" width="50" height="50">
                    Pesantren Islam Al-Irsyad
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    </ul>

                    <form class="d-flex">
                        <h5 id='ct' class="fw-bold"></h5>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>Hello, world!</h1>
        </div>

        <br>

        <div class="container">
            <div class="row">
                <div class="col-9">
                    <table id="data-calon-santri" class="table table-striped table-bordered dt-responsive nowrap"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kode</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>

                <div class="col-3">
                    <div class="card" style="width: 16rem;">
                        <div class="card-header">
                            <h5>Jumlah</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h5>Pendaftar <span class="badge bg-danger">10</span></h5>
                            </li>

                        </ul>
                    </div>
                    <br>

                    <div class="card" style="width: 16rem;">
                        <img src="/img/user-2.png" class="card-img-top" alt="...">

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Nama : Restu Winaldi</li>
                            <li class="list-group-item">No Pendaftaran : 12</li>
                            <li class="list-group-item">Status : Registrasi awal</li>
                        </ul>
                    </div>


                </div>

            </div>
        </div>

        <br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        loadTabelDataCalonSantri();

        function loadTabelDataCalonSantri() {
            pesan('Data ditambah..');
            $('#data-calon-santri').DataTable().destroy();
            $.post('{{ URL::to('get-data-calon-santri') }}', {
                _token: '{{ csrf_token() }}'
            }, function(e) {
                var t = $('#data-calon-santri').DataTable({
                    "dom": 'lBfrtip',
                    "buttons": ['copy', 'excel', 'pdf', 'print'],
                    "ordering": true,
                    "order": [
                        [0, 'asc'],
                        [1, 'asc'],
                        [2, 'asc']
                    ],
                    "bDestroy": true,
                    "paging": false,
                    "lengthChange": false,
                    "searching": true,
                    "select": false,
                    "info": false,
                    "autoWidth": false,
                    "responsive": true,
                    "rowId": 'id',
                    "targets": 0,
                    "data": e,
                    "columns": [{
                            data: 'id',
                            className: "text-center",
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'nama',
                            className: "text-left",
                        },
                        {
                            data: 'kode',
                            className: "text-right",
                        },

                    ],
                    "columnDefs": [{
                        width: 1,
                        targets: 0
                    }],
                });

                t.on('order.dt search.dt', function() {
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                // $("div.data_obat").html("<button id='btn_add_data_obat' type='button' class='btn_data_obat btn btn-success btn-sm'><i class='fa fa-plus-circle'></i> Tambah</button>"+
                //                         "<button id='btn_impor_data_obat' type='button' class='btn_data_obat btn btn-warning btn-sm'><i class='fa fa-file-excel-o'></i> Impor</button>");
            }).done(function(data) {

            });

        }

        function pesan(pesan) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: pesan,
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>

    <script src="/js/app.js"></script>

</body>

</html>
