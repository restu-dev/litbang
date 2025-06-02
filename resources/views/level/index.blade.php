 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-8">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel {{ $title }}</h3>
                 </div>
                 <!-- /.card-header -->
                 <div class="card-body">
                     <table id="tabel_level" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Nama</th>
                                 <th>Aksi</th>
                             </tr>
                         </thead>
                     </table>
                 </div>
                 <!-- /.card-body -->
             </div>
         </div>

         <div class="col-lg-4">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h5 class="m-0">Action</h5>
                 </div>
                 <div class="card-body">

                     <div class="card-body pl-0 pt-0">
                         <input type="hidden" name="id_level" class="form-control" id="id_level">

                         {{-- nama_level --}}
                         <div class="form-group">
                             <label for="nama_level">Nama Level</label>

                             <input type="text" name="nama_level" class="form-control" id="nama_level"
                                 placeholder="Kode Unit">
                         </div>
                     </div>

                     <div class="card-footer">
                         <button id="reset_form" class="btn btn-warning"><i class="fas fa-refresh"></i> Reset</button>
                         <button id="save_form" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                     </div>


                 </div>
             </div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>
         $(function() {

              loadTabeLevel();

             // load tabel unit
             function loadTabeLevel() {
                 $('.loader').show();

                 $('#tabel_level').DataTable().destroy();

                 $.post('{{ URL::to('admin/load-tabel-level') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_level").DataTable({
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
                                 data: 'name',
                                 className: "text-left",
                             },
                             {
                                 data: null,
                                 "render": function(data, type, row) {
                                     return '<div class="btn-group">' +
                                         '<button data-id="' + row.id + '" data-name="' + row.name + 
                                         '" data-toggle="tooltip" data-placement="top" title="Edit" type="button" class="btn btn-info btn-sm edit_level"><i class="fas fa-edit"></i></button>' +
                                         '<button data-id="' + row.id +
                                         '" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm hapus_level"><i class="fa fa-trash"></i></button>' +
                                         '</div>'
                                 },
                             },
                         ]
                     }).buttons().container().appendTo('#tabel_level_wrapper .col-md-6:eq(0)');

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
                     resetForm();
                 });
             }

             $(document).on("click", ".edit_level", function(e) {
                 var id_level = $(this).data("id");
                 var nama_level = $(this).data("name");

                 $("#id_level").val(id_level);
                 $("#nama_level").val(nama_level);
             });

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 var id_level = $("#id_level").val();
                 var nama_level = $("#nama_level").val();

                 if (nama_level == "") {
                     tampilPesan('warning', ' Nama Level tidak boleh kosong!');
                 } else {
                     $.ajax({
                         url: "/admin/store-level",
                         cache: false,
                         type: 'post',
                         data: {
                             id_level,
                             nama_level,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             console.log(result);
                             loadTabeLevel();
                             $('.loader').hide();
                             tampilPesan(result.status, result.message);
                             resetForm();
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                         }
                     });
                 }

             });

             //  hapus_level
             $(document).on("click", ".hapus_level", function(e) {
                 var id_level = $(this).data('id');

                 Swal.fire({
                     title: 'Are you sure?',
                     text: "You won't be able to revert this!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Yes, delete it!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();

                         $.ajax({
                             url: '/admin/destroy-level',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id_level,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTabeLevel();
                                 $('.loader').hide();
                                 tampilPesan(result.status, result.message);
                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         })
                     }
                 })
             });

             // reset_form
             $(document).on("click", "#reset_form", function() {
                 resetForm();
             });

             function resetForm() {
                 $("#id_level").val('');
                 $("#nama_level").val('');
             }
         });
     </script>
 @endsection
