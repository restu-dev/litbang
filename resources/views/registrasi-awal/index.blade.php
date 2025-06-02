 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel Unit</h3>
                 </div>
                 <!-- /.card-header -->
                 <div class="card-body">
                     <table id="tabel_registrasi_awal" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Tahun</th>
                                 <th>Kode Pendaftar</th>
                                 <th>Aksi</th>
                             </tr>
                         </thead>
                     </table>
                 </div>
                 <!-- /.card-body -->
             </div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>
         loadTableRegistrasiAwal();

         // load tabel unit
         function loadTableRegistrasiAwal() {
             tampilPesanScan('Load table registrasi awal...');

             $('.loader').show();

             $('#tabel_registrasi_awal').DataTable().destroy();

             $.post('{{ URL::to('admin/load-tabel-registrasi-awal') }}', {
                 _token: '{{ csrf_token() }}'
             }, function(e) {
                 var tabel = $("#tabel_registrasi_awal").DataTable({
                     "responsive": true,
                     "lengthChange": false,
                     "autoWidth": false,
                     "buttons": ["copy", "csv", "excel", "pdf", "print"],
                     "data": e,
                     "columns": [{
                             data: 'id',
                             render: function(data, type, row, meta) {
                                 return meta.row + 1;
                             },
                             className: "text-center",
                         },
                         {
                             data: 'tahun_pelajaran',
                             className: "text-left",
                         },
                         {
                             data: 'kode_pendaftar',
                             className: "text-left",
                         },
                         {
                             data: null,
                             "render": function(data, type, row) {
                                 return '<div class="btn-group">' +
                                     '<button data-id="' + row.id +
                                     '" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm hapus_unit"><i class="fa fa-trash"></i></button>' +
                                     '</div>'
                             },
                         },
                     ]
                 }).buttons().container().appendTo('#tabel_registrasi_awal_wrapper .col-md-6:eq(0)');

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
 @endsection
