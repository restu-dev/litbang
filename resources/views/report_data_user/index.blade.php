 @extends('layouts.main')

 @section('content')

     <div class="row">

         <div class="col-lg-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title">Filter</h3>
                 </div>

                 <div class="card-body">
                     <div class="row">
                         <div class="col-2">
                             <div class="form-group">
                                 <label>Status</label>
                                 <select id="select_status" class="form-control">
                                     <option value="">All</option>
                                     <option value="AKTIF">AKTIF</option>
                                     <option value="NON AKTIF">NON AKTIF</option>
                                 </select>
                             </div>
                         </div>

                         <div class="col-2">
                             <div class="form-group">
                                 <label>Unit</label>
                                 <select id="select_unit" class="form-control">
                                     <option value="">All</option>
                                     <option value="001">PUTRA</option>
                                     <option value="002">PUTRI</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel {{ $title }}</h3>
                 </div>

                 <div class="card-body">
                     <table id="tabel_data_report" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Kode</th>
                                 <th>Status</th>
                                 <th>Nama</th>
                                 <th>Unit</th>
                                 <th>Keterangan</th>
                             </tr>
                         </thead>
                     </table>
                 </div>
             </div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>

         $(function() {

             loadTableUserWifi('', '');

             $('#select_status').on('change', function() {
                 var status = $("#select_status").val();
                 var unit = $("#select_unit").val();

                 loadTableUserWifi(status, unit);
             });

             $('#select_unit').on('change', function() {
                 var status = $("#select_status").val();
                 var unit = $("#select_unit").val();

                 loadTableUserWifi(status, unit);
             });

             // load tabel user wifi
             function loadTableUserWifi(status, unit) {
                 $('.loader').show();

                 $('#tabel_data_report').DataTable().destroy();

                 $.post('{{ URL::to('load-table-report-data-user') }}', {
                     status,
                     unit,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_data_report").DataTable({
                         "responsive": true,
                         "lengthChange": false,
                         "autoWidth": false,
                         "buttons": [ "copy", "excel"],
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
                                 data: 'status',
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
                        
                         ]
                     }).buttons().container().appendTo('#tabel_data_report_wrapper .col-md-6:eq(0)');

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


         });
     </script>
 @endsection
