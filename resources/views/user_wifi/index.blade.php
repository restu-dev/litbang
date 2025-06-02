 @extends('layouts.main')

 @section('content')
     @include('user_wifi.partials.modal_add')
     @include('user_wifi.partials.modal_edit')
     @include('user_wifi.partials.modal_surat')

     <div class="row">

         <div class="col-lg-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title">Filter</h3>
                 </div>

                 <div class="card-body">
                     <div class="row">
                         <div class="col">
                             <div class="form-group">
                                 <label>Status</label>
                                 <select id="select_status" class="form-control">
                                     <option value="">All</option>
                                     <option value="AKTIF">AKTIF</option>
                                     <option value="NON AKTIF">NON AKTIF</option>
                                 </select>
                             </div>
                         </div>

                         <div class="col">
                             <div class="form-group">
                                 <label>Bidang</label>
                                 <select id="select_bidang" class="form-control">
                                     <option value="">All</option>
                                     @foreach ($bidang as $d)
                                         <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>

                         <div class="col">
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
                     <table id="tabel_user_wifi" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Status</th>
                                 <th>Bidang</th>
                                 <th>User</th>
                                 <th>Pass</th>
                                 <th>Nama</th>
                                 <th>HP</th>
                                 <th>Unit</th>
                                 <th>Jabatan</th>
                                 <th>Keperluan</th>
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

             loadTableUserWifi('', '', '');

             $('#select_status').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserWifi(status, bidang, unit);
             });

             $('#select_bidang').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserWifi(status, bidang, unit);
             });

             $('#select_unit').on('change', function() {
                 var status = $("#select_status").val();
                 var bidang = $("#select_bidang").val();
                 var unit = $("#select_unit").val();

                 loadTableUserWifi(status, bidang, unit);
             });

             // load tabel user wifi
             function loadTableUserWifi(status, bidang, unit) {
                 $('.loader').show();

                 $('#tabel_user_wifi').DataTable().destroy();

                 $.post('{{ URL::to('load-table-user-wifi') }}', {
                     status,
                     bidang,
                     unit,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_user_wifi").DataTable({
                         "responsive": true,
                         "lengthChange": false,
                         "autoWidth": false,
                         "buttons": [{
                             text: 'Add User',
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
                                 data: 'nama_bidang',
                                 className: "text-left",
                             },
                             {
                                 data: 'user',
                                 className: "text-left",
                             },
                             {
                                 data: 'pass',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama',
                                 className: "text-left",
                             },
                             {
                                 data: 'hp',
                                 className: "text-left",
                             },
                             {
                                 data: 'unit',
                                 className: "text-left",
                             },
                             {
                                 data: 'jabatan',
                                 className: "text-left",
                             },
                             {
                                 data: 'keperluan',
                                 className: "text-left",
                             }

                         ]
                     }).buttons().container().appendTo('#tabel_user_wifi_wrapper .col-md-6:eq(0)');

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
                 loadNamaPegawai();
                 //  loadNamaBidang();
             }

             //  load select 2 pegawai
             function loadNamaPegawai() {
                 $('.loader').show()
                 $('#nama_pegawai').empty()

                 $.post('{{ URL::to('select-nama-pegawai') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#nama_pegawai").select2({
                         data: e,
                         theme: 'bootstrap4',
                         dropdownParent: $('#modal-default')
                     })
                     $('.loader').hide()
                 });
             }

             //  load select 2 bidang
             function loadNamaBidang() {
                 $('.loader').show()
                 $('#nama_bidang').empty()

                 $.post('{{ URL::to('select-bidang') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#nama_bidang").select2({
                         data: e,
                         theme: 'bootstrap4',
                         dropdownParent: $('#modal-default')
                     })
                     $('.loader').hide()
                 });
             }

             $('#nama_pegawai').on("change", function(e) {
                 var no_pegawai = $("#nama_pegawai").val();

                 $.ajax({
                     url: "load-kode-pegawai",
                     cache: false,
                     type: 'post',
                     data: {
                         no_pegawai,
                         _token: '{{ csrf_token() }}'
                     },
                     success: function(result) {
                         $("#nip").val(result[0].NIP);
                         $("#nama").val(result[0].nama_pegawai);
                         $("#user_wifi").val(result[0].NIP);
                         $("#no_hp").val(result[0].hp);
                     },
                     fail: function(xhr, textStatus, errorThrown) {
                         $('.loader').hide();
                         tampilPesan('error', 'request failed');
                     }
                 });
             });

             //  tampil edit
            $(document).on("click", ".edit_user_wifi", function(e) {
                 var id = $(this).data('id');
                 var nama = $(this).data('nama');
                 var user = $(this).data('user');
                 var bidang = $(this).data('bidang');
                 var password = $(this).data('password');
                 var keperluan = $(this).data('keperluan');

                 // modal-default-edit
                 $("#modal-default-edit").modal("show");
                 $(".modal-title").html("Edit User Wifi");

                 $("#edit_id").val(id);
                 $("#edit_nama").val(nama);
                 $("#edit_user").val(user);
                 $("#edit_bidang").val(bidang);
                 $("#edit_password").val(password);
                 $("#edit_keperluan").val(keperluan);
            });

            // save edit
             $(document).on("click", "#save_edit_form", function(e) {
                 var id = $("#edit_id").val();
                 var nama = $("#edit_nama").val();
                 var user = $("#edit_user").val();
                 var bidang = $("#edit_bidang").val();
                 var password = $("#edit_password").val();
                 var keperluan = $("#edit_keperluan").val();

                 if (id == "") {
                     tampilPesan('warning', ' ID tidak boleh kosong!');
                 } else if (nama == "") {
                     tampilPesan('warning', ' Nama tidak boleh kosong!');
                 } else if (user == "") {
                     tampilPesan('warning', ' User tidak boleh kosong!');
                 } else if (bidang == "") {
                     tampilPesan('warning', ' bidang tidak boleh kosong!');
                 } else if (password == "") {
                     tampilPesan('warning', ' Password tidak boleh kosong!');
                 } else if (keperluan == "") {
                     tampilPesan('warning', ' Keperluan tidak boleh kosong!');
                 } else {
                     $('.loader').show();
                     $.ajax({
                         url: "form-save-user-wifi",
                         cache: false,
                         type: 'post',
                         data: {
                             id,
                             bidang,
                             user,
                             password,
                             keperluan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var status = result.status;
                             var message = result.message;

                             if (sukses == 'Y') {
                                 clearFormEdit();
                                 loadTableUserWifi('', '', '');

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
                 var nama_pegawai = $("#nama_pegawai").val();
                 var nip = $("#nip").val();
                 var nama = $("#nama").val();
                 var user = $("#user_wifi").val();
                 var bidang = $("#nama_bidang").val();
                 var password = $("#password").val();
                 var keperluan = $("#keperluan").val();

                 if (nama_pegawai == "") {
                     tampilPesan('warning', ' Keterangan tidak boleh kosong!');
                 } else if (nip == "") {
                     tampilPesan('warning', ' NIP tidak boleh kosong!');
                 } else if (nama == "") {
                     tampilPesan('warning', ' Nama tidak boleh kosong!');
                 } else if (user == "") {
                     tampilPesan('warning', ' User tidak boleh kosong!');
                 } else if (bidang == "") {
                     tampilPesan('warning', ' bidang tidak boleh kosong!');
                 } else if (password == "") {
                     tampilPesan('warning', ' Password tidak boleh kosong!');
                 } else if (keperluan == "") {
                     tampilPesan('warning', ' Keperluan tidak boleh kosong!');
                 } else {
                     $('.loader').show();
                     $.ajax({
                         url: "form-save-user-wifi",
                         cache: false,
                         type: 'post',
                         data: {
                             nip,
                             nama,
                             bidang,
                             user,
                             password,
                             keperluan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var status = result.status;
                             var message = result.message;

                             if (sukses == 'Y') {
                                 clearFormInput();
                                 loadTableUserWifi('', '', '');

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
                 $("#nama_pegawai").val("");
                 $("#nip").val("");
                 $("#nama").val("");
                 $("#no_hp").val("");
                 $("#user_wifi").val("");
                 $("#nama_bidang").val("");
                 $("#password").val("");
                 $("#keperluan").val("");
             }

             $('#modal-default-edit').on('hidden.bs.modal', function() {
                 clearFormEdit()
             });

             function clearFormEdit() {
                 $("#edit_id").val("");
                 $("#edit_nama").val("");
                 $("#edit_user").val("");
                 $("#edit_bidang").val("");
                 $("#edit_password").val("");
                 $("#edit_keperluan").val("");
             }

             $(document).on("click", ".hapus_user_wifi", function(e) {
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
                             url: 'destroy-user-wifi',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {

                                 if (result.sukses == "Y") {
                                     loadTableUserWifi('', '', '');
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

             $(document).on("click", ".non_aktif_user_wifi", function(e) {
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
                             url: 'status-user-wifi',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 status: 'nonaktif',
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserWifi('', '', '');
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

             $(document).on("click", ".aktif_user_wifi", function(e) {
                 var id = $(this).data("id");

                 Swal.fire({
                     title: 'Aktif?',
                     text: "User akan diaktifkan!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Yes, aktif!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: 'status-user-wifi',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 status: 'aktif',
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserWifi('', '', '');
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

             $(document).on("click", ".kirim_surat", function(e) {
                 var id = $(this).data('id');

                 Swal.fire({
                     title: 'Kirim Surat?',
                     text: "Data surat pengajuan akan dikirim!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, kirim!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: '/kirim-surat-pengajuan-user-wifi',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserWifi('', '', '');
                                 $('.loader').hide();

                                 tampilPesan('success', 'Berhasil kirim data..');

                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         })
                     }
                 })

             });

             $(document).on("click", ".kirim_user", function(e) {
                 var id = $(this).data('id');

                 Swal.fire({
                     title: 'Kirim User?',
                     text: "Data user dan password wifi akan dikirim",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, kirim!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();
                         $.ajax({
                             url: '/kirim-user-pengajuan-user-wifi',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 console.log(result);
                                 loadTableUserWifi('', '', '');
                                 $('.loader').hide();
                                 tampilPesan('success', 'Berhasil kirim data..');
                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         })
                     }
                 })

             });


         });
     </script>
 @endsection
