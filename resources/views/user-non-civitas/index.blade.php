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
                     <table id="tabel_master" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Nama</th>
                                 <th>Level</th>
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
                         <input type="hidden" name="id" class="form-control" id="id">

                         {{-- nama_user --}}
                         <div class="form-group">
                             <label for="nama_user">Nama User <code>*</code></label>

                             <input type="text" name="nama_user" class="form-control" id="nama_user"
                                 placeholder="Input Nama">
                         </div>

                         {{-- pass_user --}}
                         <div class="form-group">
                             <label for="pass_user">Password User <code>*</code></label>

                             <input type="password" name="pass_user" class="form-control" id="pass_user">
                         </div>

                         {{-- id_level --}}
                         <div class="form-group">
                             <label for="id_level">Level <code>*</code></label>

                             <select id="id_level" name="id_level" class="form-control select2" style="width: 100%;">
                             </select>
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

     {{-- modal bawahan --}}
     <div class="modal fade" id="modal-lg-bawahan">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">

                 <div class="modal-header">
                     <h4 class="modal-title-bawahan">Large Modal</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>

                 <div class="p-2" id="tampil_bawahan"></div>

                 <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 </div>

             </div>
         </div>
     </div>
 @endsection

 @section('script')
     <script>
         $(function() {

             loadSelectLevel();
             loadTabeMaster();

             function loadSelectLevel() {
                 $('.loader').show()
                 
                 $('#id_level').empty()

                 $.post('{{ URL::to('select-level') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#id_level").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()
                     $("#id_level").val('').trigger("change");
                 });
             }

             // load tabel
             function loadTabeMaster() {
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-user-non-civitas') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_master").DataTable({
                         "buttons": ["excel", "pdf", "print"],
                         "autoWidth": false,
                         "searching": true,
                         "paging": false,
                         "fixedColumns": true,
                         "scrollX": true,
                         "data": e,
                         "columns": [{
                                 data: 'id',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 className: "text-center",
                             },
                             {
                                 data: 'nip',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_level',
                                 className: "text-left",
                             },
                             {
                                 data: 'aksi',
                                 className: "text-left",
                             },

                         ]
                     }).buttons().container().appendTo('#tabel_master_wrapper .col-md-6:eq(0)');

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

             $(document).on("click", ".edit_master", function(e) {
                 var id = $(this).data("id");
                 var nama = $(this).data("nama");
                 var level = $(this).data("level");

                 $("#id").val(id);
                 $("#nama_user").val(nama);
                 $("#id_level").val(level).trigger("change");

                 //  $('#pass_user').prop('disabled', true);
                 $('#nama_user').prop('disabled', true);

             });

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 var id = $("#id").val();
                 var nama_user = $("#nama_user").val();
                 var pass_user = $("#pass_user").val();
                 var level = $("#id_level").val();

                 if (nama_user == "") {
                     tampilPesan('warning', ' Nama tidak boleh kosong!');
                 } else if (level == "") {
                     tampilPesan('warning', ' Level tidak boleh kosong!');
                 } else {
                     if (id != "") {
                         $.ajax({
                             url: "/store-user-non-civitas",
                             cache: false,
                             type: 'post',
                             data: {
                                 id,
                                 nama_user,
                                 pass_user,
                                 level,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTabeMaster();
                                 $('.loader').hide();
                                 tampilPesan(result.status, result.message);
                                 resetForm();
                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         });
                     } else {
                         if (pass_user == "") {
                             tampilPesan('warning', ' Password tidak boleh kosong!');

                         } else {
                             $.ajax({
                                 url: "/store-user-non-civitas",
                                 cache: false,
                                 type: 'post',
                                 data: {
                                     id,
                                     nama_user,
                                     pass_user,
                                     level,
                                     _token: '{{ csrf_token() }}'
                                 },
                                 success: function(result) {
                                     console.log(result);
                                     loadTabeMaster();
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
                     }


                 }

             });

             //  hapus_master
             $(document).on("click", ".hapus_master", function(e) {
                 var id = $(this).data('id');

                 Swal.fire({
                     title: 'Hapus data?',
                     text: "Data akan dihapus!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, Hapus!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();

                         $.ajax({
                             url: '/destroy-user-non-civitas',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 var sukses = result.sukses;
                                 var pesan = result.pesan;

                                 if (sukses == "Y") {
                                     loadTabeMaster();
                                     $('.loader').hide();
                                     tampilPesan('success', pesan);
                                     resetForm();

                                 } else {
                                     $('.loader').hide();
                                     tampilPesan('error', pesan);
                                 }
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
                 $("#id").val('');
                 $("#pass_user").val('');
                 $('#pass_user').prop('disabled', false);

                 $("#nama_user").val('');
                 $('#nama_user').prop('disabled', false);
                 $("#id_level").val('').trigger("change");
             }

             //  mapping_bawahan
             $(document).on("click", ".mapping_bawahan", function(e) {
                 var id = $(this).data('id');

                 $.ajax({
                     url: '/tampil-mapping-bawahan',
                     cache: false,
                     type: 'post',
                     data: {
                         id,
                         _token: '{{ csrf_token() }}'
                     },
                     success: function(result) {

                         $("#modal-lg-bawahan").modal("show");
                         $(".modal-title-bawahan").html('Mapping Bawahan');

                         console.log(result);

                         $('#tampil_bawahan').html(result);

                     },
                     fail: function(xhr, textStatus, errorThrown) {
                         $('.loader').hide();
                         tampilPesan('error', 'request failed');
                     }
                 })


             });

         });
     </script>
 @endsection
