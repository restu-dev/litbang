 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
     {{-- filter --}}
     <div class="row">
         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-body card-body">
                     <div class="row justify-content-start">

                         {{-- filter pegawai --}}
                         <div class="col-3">
                             <select id="filter_pegawai" class="form-control select2" style="width: 100%;">
                             </select>
                         </div>

                        {{-- filter bulan --}}
                        <div class="col-3">
                            <select id="filter_bulan" class="form-control select2" style="width: 100%;">
                                <option selected="selected" value="">-Bulan-</option>
                                <?php
                                    $today = date('d M Y');
                                    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    
                                    $month = date('n');
                                    for ($j = 1; $j <= 12; $j++) {
                                        if ($month == $j) {
                                            echo '<option selected="selected" value='.$j .'>' .$bulan[$j] .'</option>';
                                        } else {
                                            echo '<option value='.$j .'>'.$bulan[$j].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        {{-- filter tahun --}}
                        <div class="col-3">
                            <select id="filter_tahun" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="">-Tahun-</option>
                                <?php
                                    $tahun = date('Y');
                                    $start = $tahun - 4;
                                    $i = 1;
                                    $year = date('Y');
                                
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($year == $start) {
                                            echo '<option selected="selected" value='.$start.'>'.$start .'</option>';
                                        } else {
                                            echo '<option value='.$start.'>'.$start.'</option>';
                                        }
                                    
                                        $start++;
                                    }
                                ?>
                            </select>
                        </div>

                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="row">
         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel {{ $title }}</h3>
                 </div>

                 <div class="card-body">
                     <table id="tabel_master" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th rowspan=2>Tanggal</th>
                                <th rowspan=2>Hari</th>
                                <th colspan=2>Absen Harian</th>
                                <th colspan=2>Jam Kerja</th>
                                <th colspan=2>Lebih/Kurang</th>
                            </tr>

                            <tr>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Terlambat</th>
                                <th>Pulang Awal</th>
                            </tr>
                        </thead>
                     </table>
                 </div>

             </div>
         </div>
     </div>
 @endsection

 @section('script')
     <script src="/datatable/dataTables.responsive.min.js"></script>
     <script src="/datatable/dataTables.fixedColumns.min.js"></script>

     <script>

         $(function() {

             loadFilterDataPegawaiBySo();

             function loadFilterDataPegawaiBySo() {
                 $('.loader').show()
                 $('#filter_pegawai').empty()

                 $.post('{{ URL::to('select-data-pegawai-by-so') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#filter_pegawai").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()
                     $("#filter_pegawai").val('').trigger("change");
                 });

                 $("#filter_pegawai").append("<option value='' selected>-Pegawai-</option>");

             }

             // on change filter_pegawai
             $('#filter_pegawai').on("change", function(e) {

                 var filter_pegawai = $("#filter_pegawai").val();
                 var filter_bulan = $("#filter_bulan").val();
                 var filter_tahun = $("#filter_tahun").val();
               
                 loadTabelData(filter_pegawai,filter_bulan, filter_tahun);
             });

             // on change filter_bulan
             $('#filter_bulan').on("change", function(e) {

                 var filter_pegawai = $("#filter_pegawai").val();
                 var filter_bulan = $("#filter_bulan").val();
                 var filter_tahun = $("#filter_tahun").val();
               
                 loadTabelData(filter_pegawai,filter_bulan, filter_tahun);
             });

             // on change filter_tahun
             $('#filter_tahun').on("change", function(e) {

                 var filter_pegawai = $("#filter_pegawai").val();
                 var filter_bulan = $("#filter_bulan").val();
                 var filter_tahun = $("#filter_tahun").val();

                 loadTabelData(filter_pegawai,filter_bulan, filter_tahun);
             });


             loadTabelData('', '', '');

             // load tabel
             function loadTabelData(filter_pegawai,filter_bulan, filter_tahun){
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 var nip = filter_pegawai;
                var month = filter_bulan;
                var year = filter_tahun;

                var max_day = 0;
                if (month <= 7) {
                    if (month == 2 && year % 4 == 0)
                        max_day = 29;
                    else if (month == 2 && year % 4 != 0)
                        max_day = 28;
                    else if (month % 2 == 0)
                        max_day = 30;
                    else
                        max_day = 31;

                } else {
                    if (month % 2 == 0)
                        max_day = 31;
                    else
                        max_day = 30;
                }

                if (nip == "") {
                    nip = "";
                }

                 $.post('{{ URL::to('load-tabel-report-kehadiaran') }}', {
                    nip,
                    month,
                    year,
                    max_day,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_master").DataTable({
                         "bDestroy": true,
                         "buttons": ["excel", "pdf", "print"],
                         "ordering": false,
                         "autoWidth": false,
                         "searching": true,
                         "scrollY": "375px",
                         "scrollX": true,
                         "scrollCollapse": true,
                         "paging": false,
                         "fixedColumns": true,
                         "fixedHeader": {
                             header: true,
                             footer: true
                         },
                         "fixedColumns": {
                             left: 2,
                         },
                         "data": e,
                         "columns": [
                             {
                                 data: 'tanggal',
                                 className: "text-left",
                             },
                             {
                                 data: 'hari',
                                 className: "text-left",
                             },
                             {
                                 data: 'masuk',
                                 className: "text-left",
                             },
                             {
                                 data: 'pulang',
                                 className: "text-left",
                             },
                             {
                                 data: 'jam_masuk',
                                 className: "text-left",
                             },
                             {
                                 data: 'jam_pulang',
                                 className: "text-left",
                             },
                             {
                                 data: 'terlambat',
                                 className: "text-left",
                             },
                             {
                                 data: 'pulang_awal',
                                 className: "text-left",
                             }
                         ]
                     }).buttons().container().appendTo('#tabel_master_wrapper .col-md-6:eq(0)');


                 }).done(function(data) {
                     $('.loader').hide();
                 });
             }

         });
     </script>
 @endsection
