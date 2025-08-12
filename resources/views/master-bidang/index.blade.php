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
                                 <th>Warna</th>
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
                         <input type="hidden" name="id_master" class="form-control" id="id_master">

                         {{-- nama_master --}}
                         <div class="form-group">
                             <label for="nama_master">Nama Master <code>*</code></label>

                             <input type="text" name="nama_master" class="form-control" id="nama_master"
                                 placeholder="Nama Master">
                         </div>

                         {{-- warna --}}
                         <div class="form-group">
                             <label for="warna">Pilih Warna: <code>*</code></label>

                             <select id="warna" name="warna" class="form-control" style="padding:5px;">
                                 <option value="">-- Pilih Warna --</option>
                                 <option value="#FF0000" style="background-color:#FF0000; color:white;">Merah</option>
                                 <option value="#0000FF" style="background-color:#0000FF; color:white;">Biru</option>
                                 <option value="#00FF00" style="background-color:#00FF00;">Hijau</option>
                                 <option value="#FFFF00" style="background-color:#FFFF00;">Kuning</option>
                                 <option value="#000000" style="background-color:#000000; color:white;">Hitam</option>
                                 <option value="#FFFFFF" style="background-color:#FFFFFF;">Putih</option>
                                 <option value="#FFA500" style="background-color:#FFA500;">Oranye</option>
                                 <option value="#800080" style="background-color:#800080; color:white;">Ungu</option>
                                 <option value="#FFC0CB" style="background-color:#FFC0CB;">Pink</option>
                                 <option value="#A52A2A" style="background-color:#A52A2A; color:white;">Coklat</option>
                                 <option value="#808080" style="background-color:#808080; color:white;">Abu-Abu</option>
                                 <option value="#008080" style="background-color:#008080; color:white;">Teal</option>
                                 <option value="#FFD700" style="background-color:#FFD700;">Emas</option>
                                 <option value="#C0C0C0" style="background-color:#C0C0C0;">Perak</option>
                                 <option value="#00FFFF" style="background-color:#00FFFF;">Cyan</option>
                                 <option value="#4B0082" style="background-color:#4B0082; color:white;">Indigo</option>
                                 <option value="#FF6347" style="background-color:#FF6347;">Tomato</option>
                                 <option value="#2E8B57" style="background-color:#2E8B57; color:white;">Sea Green</option>
                                 <option value="#DC143C" style="background-color:#DC143C; color:white;">Crimson</option>
                                 <option value="#F5DEB3" style="background-color:#F5DEB3;">Wheat</option>
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
 @endsection

 @section('script')
     <script>
         $(function() {

             loadTabeMaster();

             // load tabel unit
             function loadTabeMaster() {
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-master-bidang') }}', {
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
                                 data: 'nama',
                                 className: "text-left",
                             },
                              {
                                 data: 'tampil_warna',
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
                 var warna = $(this).data("warna");

                 $("#id_master").val(id);
                 $("#nama_master").val(nama);
                 $("#warna").val(warna);
             });

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 var id_master = $("#id_master").val();
                 var nama_master = $("#nama_master").val();
                 var warna = $("#warna").val();

                 if (nama_master == "") {
                     tampilPesan('warning', ' Nama Master tidak boleh kosong!');
                 } else if (warna == "") {
                     tampilPesan('warning', ' Warna tidak boleh kosong!');
                 } else {
                     $.ajax({
                         url: "/store-master-bidang",
                         cache: false,
                         type: 'post',
                         data: {
                             id_master,
                             nama_master,
                             warna,
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

             });

             //  hapus_master
             $(document).on("click", ".hapus_master", function(e) {
                 var id_master = $(this).data('id');

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
                             url: '/destroy-master-bidang',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id_master,
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
                 $("#id_master").val('');
                 $("#nama_master").val('');
             }

         });
     </script>
 @endsection
