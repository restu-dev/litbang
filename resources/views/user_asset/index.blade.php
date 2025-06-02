 @extends('layouts.main')

 @section('content')
     @include('user_asset.partials.modal_add')
     @include('user_asset.partials.modal_edit')

     <div class="row">

         <div class="col-lg-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title">Filter</h3>
                 </div>

                 <div class="card-body">
                    
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
                     <table id="tabel_user_asset" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Status</th>
                                 <th>Kode</th>
                                 <th>Asset</th>
                                 <th>Pemakai</th>
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
         function tampilPassword(id) {

             var x = document.getElementById("data_pass_" + id);
             if (x.type === "password") {
                 x.type = "text";
             } else {
                 x.type = "password";
             }
         }

         $(function() {

             loadTableUserAsset('', '', '');

             $('#select_status').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserAsset(status, bidang, unit);
             });

             $('#select_bidang').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserAsset(status, bidang, unit);
             });

             $('#select_unit').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserAsset(status, bidang, unit);
             });

             // load tabel user wifi
             function loadTableUserAsset(status, bidang, unit) {
                 $('.loader').show();

                 $('#tabel_user_asset').DataTable().destroy();

                 $.post('{{ URL::to('load-table-user-asset') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_user_asset").DataTable({
                         "responsive": true,
                         "lengthChange": false,
                         "autoWidth": false,
                         "buttons": [{
                             text: 'Add Asset',
                             action: function(e, dt, node, config) {
                                 addUser();
                             }
                         }, "copy", "excel"],
                         "data": e,
                         "columns": [{
                                 data: 'id',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 className: "text-center",
                             },
                             {
                                 data: 'status',
                                 className: "text-left",
                             },
                             {
                                 data: 'id_asset',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_asset',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_pegawai',
                                 className: "text-left",
                             },
                             {
                                 data: 'keterangan',
                                 className: "text-left",
                             }
                         ]
                     }).buttons().container().appendTo('#tabel_user_asset_wrapper .col-md-6:eq(0)');

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


             function addUser() {
                 $("#modal-default").modal("show");
                 $(".modal-title").html("Add User Wifi");

                 loadAsset();
             }

             //  load select 2 pegawai
             function loadAsset() {
                 $('.loader').show()
                 $('#nama_asset').empty()

                 $.post('{{ URL::to('select-nama-asset') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#nama_asset").select2({
                         data: e,
                         theme: 'bootstrap4',
                         dropdownParent: $('#modal-default')
                     });

                     $('#nama_asset').val('').trigger('change');

                     $('.loader').hide()
                 });
             }

             //  tampil edit
            $(document).on("click", ".edit_user_asset", function(e) {
                 var id = $(this).data('id');
                 var idasset = $(this).data('idasset');
                 var keterangan = $(this).data('keterangan');
             
                 // modal-default-edit
                 $("#modal-default-edit").modal("show");
                 $(".modal-title").html("Edit User Wifi");

                 $("#edit_id").val(id);
                 $("#edit_nama").val(idasset);
                 $("#edit_keterangan").val(keterangan);
            });

            // save edit
             $(document).on("click", "#save_edit_form", function(e) {
                 var id = $("#edit_id").val();
                 var nama = $("#edit_nama").val();
                 var keterangan = $("#edit_keterangan").val();
                
                 if (id == "") {
                     tampilPesan('warning', ' ID tidak boleh kosong!');
                 } else if (nama == "") {
                     tampilPesan('warning', ' Nama tidak boleh kosong!');
                 } else if (keterangan == "") {
                     tampilPesan('warning', ' Keteragan tidak boleh kosong!');
                 } else {
                     $('.loader').show();
                     $.ajax({
                         url: "form-save-user-asset",
                         cache: false,
                         type: 'post',
                         data: {
                             id,
                             keterangan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var status = result.status;
                             var message = result.message;

                             if (sukses == 'Y') {
                                 clearFormEdit();
                                 loadTableUserAsset('', '', '');

                                 tampilPesan(status, message);
                             } else {
                                 tampilPesan(status, message);
                             }

                             $("#modal-default-edit").modal("hide");
                             $('.loader').hide();
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                         }
                     });
                 }

             });

            //  save add
             $(document).on("click", "#save_form", function(e) {
                 var nama_asset = $("#nama_asset").val();
                 var keterangan = $("#keterangan").val();
               
                 if (nama_asset == "") {
                     tampilPesan('warning', ' Asset tidak boleh kosong!');
                 } else if (keterangan == "") {
                     tampilPesan('warning', ' Keterangan tidak boleh kosong!');
                 } else {
                     $('.loader').show();
                     $.ajax({
                         url: "form-save-user-asset",
                         cache: false,
                         type: 'post',
                         data: {
                             nama_asset,
                             keterangan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var status = result.status;
                             var message = result.message;

                             if (sukses == 'Y') {
                                 clearFormInput();
                                 loadTableUserAsset('', '', '');

                                 tampilPesan(status, message);
                             } else {
                                 tampilPesan(status, message);
                             }

                             $("#modal-default").modal("hide");
                             $('.loader').hide();
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                         }
                     });
                 }

             });

             $('#modal-default').on('hidden.bs.modal', function() {
                 clearFormInput()
             });

             function clearFormInput() {
                 $("#nama_asset").val("");
                 $("#keterangan").val("");
             }

             $('#modal-default-edit').on('hidden.bs.modal', function() {
                 clearFormEdit()
             });

             function clearFormEdit() {
                 $("#edit_id").val("");
                 $("#edit_nama").val("");
                 $("#edit_keterangan").val("");
             }

             $(document).on("click", ".hapus_user_asset", function(e) {
                 var id = $(this).data("id");

                 Swal.fire({
                     title: 'Hapus User?',
                     text: "Data user tidak bisa dikembalikan!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, Hapus!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: 'destroy-user-asset',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {

                                 if (result.sukses == "Y") {
                                     loadTableUserAsset('', '', '');
                                     tampilPesan(result.status, result.message);
                                 } else {
                                     tampilPesan(result.status, result.message);
                                 }

                                 $('.loader').hide();

                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         })
                     }
                 })
             });

             $(document).on("click", ".non_aktif_user_asset", function(e) {
                 var id = $(this).data("id");

                 Swal.fire({
                     title: 'Non Aktif?',
                     text: "Akses akan dinonaktifkan!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, non aktif!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: 'status-user-asset',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 status: 'nonaktif',
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserAsset('', '', '');
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

             $(document).on("click", ".aktif_user_asset", function(e) {
                 var id = $(this).data("id");

                 Swal.fire({
                     title: 'Aktif?',
                     text: "User akan diaktifkan!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, aktif!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: 'status-user-asset',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 status: 'aktif',
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserAsset('', '', '');
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

             $(document).on("click", ".cetak_surat", function(e) {
                 var id = $(this).data('id');
                 var nama = $(this).data('nama');

                 $("#modal-lg-surat").modal("show");
                 $(".modal-title-surat").html('Surat Pengajuan User : ' + nama);
                 $('#tampil_surat').attr('src', 'https://jaringan.pesantrenalirsyad.org/tampil-surat/' + id);
             });

         });
     </script>
 @endsection
