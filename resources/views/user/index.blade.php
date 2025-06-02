 @extends('layouts.main')

 @section('content')
     @include('user.partials.modal_edit')
     @include('user.partials.modal_bidang')

     <div class="row">
         <div class="col-lg-12">
             <div class="card">

                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                 
                 <div class="card-header">
                     <h3 class="card-title">Level</h3>
                 </div>

                 <div class="card-body table-responsive p-3">

                     {{-- level --}}
                     <div class="row">

                         <div class="col">
                             <button type="button" data-id=""
                                 class="data_level btn btn-block btn-warning btn-sm">All</button>
                         </div>
                         
                         @foreach ($data_level as $l)
                             <div class="col">
                                 <button type="button" data-id="{{ $l->id }}"
                                     class="data_level btn btn-block btn-primary btn-sm">{{ $l->name }} :
                                     {{ $l->jumlah }}</button>
                             </div>
                         @endforeach
                     </div>

                 </div>
             </div>
         </div>

         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel User</h3>
                 </div>

                 <!-- /.card-header -->
                 <div class="card-body table-responsive p-3" style="height: 380px;">
                     <table id="tabel_user" class="table table-bordered table-striped display compact table-sm">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Aksi</th>
                                 <th>Level</th>
                                 <th>Unit</th>
                                 <th>NIP</th>
                                 <th>Nama</th>
                                 <th>Struktur</th>
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
         $(function() {

             loadTabelUser('');

             // load tabel unit
             function loadTabelUser(level) {
                 $('.loader').show();

                 $('#tabel_user').DataTable().destroy();

                 $.post('{{ URL::to('admin/load-tabel-user') }}', {
                     level,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_user").DataTable({
                         "responsive": true,
                         "lengthChange": false,
                         "autoWidth": false,
                         "bPaginate": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print"],
                         "data": e,
                         "columns": [{
                                 data: 'id_pegawai',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 className: "text-center",
                             },
                             {
                                 data: 'aksi',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_level',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_unit',
                                 className: "text-left",
                             },
                             {
                                 data: 'NIP',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_pegawai',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_struktur',
                                 className: "text-left",
                             },

                         ],
                         columnDefs: [{
                             width: 200,
                             targets: 1
                         }],
                     }).buttons().container().appendTo('#tabel_user_wrapper .col-md-6:eq(0)')


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

             $(document).on("click", ".data_level", function(e) {
                 var idlevel = $(this).data("id");
                 loadTabelUser(idlevel);
             });

             $(document).on("click", ".edit_akses", function(e) {
                 var nip = $(this).data("nip");
                 var level = $(this).data("level");

                 $("#id_pegawai_edit_level").val(nip);
                 $("#nama_level").val(level);

                 $('#modal-default').modal('show');
                 $('.modal-title').html('Edit Level');
             });

              //  save_edit_level
             $(document).on("click", "#save_edit_level", function(e) {
                 var idpegawai = $("#id_pegawai_edit_level").val();
                 var idlevel = $("#level_pilih").val();

                 if (idpegawai == "") {
                     tampilPesan('warning', ' Pegawai boleh kosong!');
                 } else {
                     $.ajax({
                         url: "/admin/simpan-level-user",
                         cache: false,
                         type: 'post',
                         data: {
                             idpegawai,
                             idlevel,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             console.log(result);
                             loadTabelUser('');
                             $('.loader').hide();
                             resetFormEditLevel();
                             tampilPesan(result.status, result.message);
                             $('#modal-default').modal('hide');
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                             $('#modal-default').modal('hide');
                         }
                     });
                 }
             });

               $(document).on("click", ".edit_bidang_isct", function(e) {
                 var nip = $(this).data("nip");
                 var bidang = $(this).data("bidang");

                 $("#id_pegawai_bidang").val(nip);
                 $("#nama_bidang").val(bidang);

                 $('#modal-bidang').modal('show');
                 $('.modal-title').html('Bidang');
             });

              //  save_edit_level
             $(document).on("click", "#save_edit_bidang", function(e) {
                 var idpegawai = $("#id_pegawai_bidang").val();
                 var idbidang = $("#bidang_pilih").val();

                 if (idpegawai == "") {
                     tampilPesan('warning', ' Pegawai boleh kosong!');
                 } else {
                     $.ajax({
                         url: "/admin/simpan-bidang-user",
                         cache: false,
                         type: 'post',
                         data: {
                             idpegawai,
                             idbidang,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             console.log(result);
                             loadTabelUser('');
                             $('.loader').hide();
                             resetFormEditBidang();
                             tampilPesan(result.status, result.message);
                             $('#modal-bidang').modal('hide');
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                             $('#modal-bidang').modal('hide');
                         }
                     });
                 }
             });
            

             //  hapus_akses
             $(document).on("click", ".hapus_akses", function(e) {
                 var nip = $(this).data('nip');

                 Swal.fire({
                     title: 'Yakin hapus Akses?',
                     text: "Akses aplikasi akan dihapus!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, Hapus!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();

                         $.ajax({
                             url: '/admin/hapus-akses',
                             cache: false,
                             type: 'post',
                             data: {
                                 nip: nip,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 loadTabelUser('');
                                 $('.loader').hide();
                                 tampilPesan(result.status, result.message);
                                 $('#modal-default').modal('hide');
                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                                 $('#modal-default').modal('hide');
                             }
                         })
                     }
                 })
             });

             $('#modal-default').on('hide.bs.modal', function() {
                 resetFormEditLevel();
             });

             function resetFormEditLevel() {
                 $("#id_pegawai_edit_level").val("");
                 $("#nama_level").val("");
                 $("#level_pilih").val("");
             }

             function resetFormEditBidang() {
                 $("#id_pegawai_bidang").val("");
                 $("#nama_bidang").val("");
                 $("#bidang_pilih").val('').trigger("change");
             }


         });
     </script>
 @endsection
