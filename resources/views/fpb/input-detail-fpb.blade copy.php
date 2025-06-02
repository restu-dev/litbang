     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">

     <style>

     </style>

     <div class="row pt-1">
         @if ($data->status == 'Draft')
             <div class="col-3">
                 <div class="card">
                     <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                     <form id="add-detail-fpb" enctype="multipart/form-data">
                         <div class="card-body">
                             @csrf

                             <input type="hidden" name="id_master_fpb" id="id_master_fpb" class="form-control"
                                 value="{{ $data->id }}">

                             <input type="hidden" name="id" id="id" class="form-control">

                             {{-- nama barang --}}
                             <div class="form-group mb-1">
                                 <label for="id_barang" style="font-size:12px;">Nama Barang <b
                                         style="color: crimson">*</b></label>

                                 <select required name="id_barang" id="id_barang"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- jumlah --}}
                             <div class="form-group mb-1">
                                 <label for="jumlah" style="font-size:12px;">Jumlah <b
                                         style="color: crimson">*</b></label>

                                 <input required type="number" name="jumlah" id="jumlah"
                                     class="form-control form-control-sm" placeholder="Jumlah">
                             </div>

                             {{-- anggaran --}}
                             <div class="form-group mb-1">
                                 <label for="yt_anggaran" style="font-size:12px;">Anggaran <b
                                         style="color: crimson">*</b></label>

                                 <select required name="yt_anggaran" id="yt_anggaran"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                     <option value="Ya">Ya</option>
                                     <option value="Tidak">Tidak</option>
                                 </select>
                             </div>

                             {{-- nominal --}}
                             <div class="form-group mb-1">
                                 <label required for="harga_satuan" style="font-size:12px;">Nominal Peritem <b
                                         style="color: crimson">*</b></label>

                                 <input type="text" name="harga_satuan" id="harga_satuan"
                                     class="form-control form-control-sm" placeholder="Nominal">
                             </div>

                             {{-- pengguna --}}
                             <div class="form-group mb-1">
                                 <label for="pengguna" style="font-size:12px;">Pengguna <b
                                         style="color: crimson">*</b></label>

                                 <select required name="pengguna" id="pengguna"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- deskripsi --}}
                             <div class="form-group mb-1">
                                 <label for="deskripsi" style="font-size:12px;">Deskripsi Barang</label>

                                 <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi"></textarea>
                             </div>

                             {{-- uraian_kebutuhan --}}
                             <div class="form-group mb-1">
                                 <label for="uraian_kebutuhan" style="font-size:12px;">Uraian Kebutuhan</label>

                                 <textarea name="uraian_kebutuhan" id="uraian_kebutuhan" class="form-control" placeholder="Uraian Kebutuhan"></textarea>
                             </div>

                             {{-- img --}}
                             <div class="form-group mb-1">

                                 <label for="image" style="font-size:12px;">Upload Foto | Tipe file gambar : .png,
                                     .jpg</label>

                                 <div class="row">

                                     <div class="col-2">
                                         <img style="width: 100%" src="/img/photo.png" class="img-preview">
                                     </div>

                                     <div class="col">
                                         <div class="form-group">
                                             <input name="image" onchange="previewFoto()" class="form-control"
                                                 type="file" accept="image/png, image/jpg" id="image">
                                         </div>
                                     </div>
                                 </div>
                             </div>

                         </div>

                         <div class="card-footer">
                             <button id="reset_form" class="btn btn-warning btn-sm"><i
                                     class="fas fa-refresh"></i>Reset</button>
                             <button type="submit" class="btn btn-success btn-sm"><i
                                     class="fas fa-save"></i>Save</button>
                         </div>
                     </form>
                 </div>
             </div>
         @endif


         @php
             $col = 'col-9';
             if ($data->status != 'Draft') {
                 $col = 'col-12';
             }
         @endphp
         <div class="{{ $col }}">

             {{-- tabel --}}
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h5> <b>No Surat</b> : {{ $data->no_surat }}</h5>
                 </div>

                 <div class="card-body card-body">

                     <table id="table_fpb" class="table table-bordered table-striped table-sm">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Aksi</th>
                                 <th>Barang</th>
                                 <th>Jml</th>
                                 <th>Harga</th>
                                 <th>Tot Harga</th>
                                 <th>Anggaran</th>
                                 <th>Pengguna</th>
                                 <th>Deskripsi</th>
                                 <th>Uraian Kebutuhan</th>
                             </tr>
                         </thead>

                         <tfoot>
                             <tr>
                                 <th colspan="3" style="text-align:right; font-weight: bold;">Jumlah</th>
                                 <th id="foter_jml"></th>
                                 <th id="foter_harga_satuan"></th>
                                 <th id="foter_total"></th>
                                 <th colspan="4"></th>
                             </tr>
                         </tfoot>
                     </table>
                 </div>

             </div>

             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-body card-body">
                     <div class="row">
                         @if ($data->status == 'Draft')
                             <div class="col-3">
                                 <input placeholder="Tgl Pemakaian" id="tgl_pemakaian" class="textbox-n form-control"
                                     type="text" onfocus="(this.type='date')" onblur="(this.type='text')" />
                             </div>

                             <div class="col-2">
                                 <button id="ajukan_fpb" type="button" class="btn btn-primary"><i
                                         class="fa fa-upload"></i>
                                     Ajukan</button>
                             </div>
                         @else
                             <div class="col-2">
                                 <button id="cetak_fpb" type="button" class="btn btn-success btn-block"><i
                                         class="fa fa-print"></i>
                                     Cetak</button>
                             </div>
                         @endif
                     </div>

                 </div>
             </div>
         </div>
     </div>

     <div class="modal fade" id="modal_img">
         <div class="modal-dialog modal-sm">
             <div class="modal-content">

                 <div class="modal-body" id="view_img">

                 </div>

                 <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 </div>
             </div>
         </div>
     </div>

     <script src="/datatable/dataTables.responsive.min.js"></script>
     <script src="/datatable/dataTables.fixedColumns.min.js"></script>

     <script>

         function previewFoto() {
             const image = document.querySelector('#image');
             const imgPreview = document.querySelector('.img-preview');

             imgPreview.style.display = 'block';

             const oFReader = new FileReader();
             oFReader.readAsDataURL(image.files[0]);

             oFReader.onload = function(oFREvent) {
                 imgPreview.src = oFREvent.target.result;
             }
         }

         $(function() {
             var idmaster = {{ $data->id }};

             loadTabeDetailFpb(idmaster);

             loadSelectBarang();

             loadSelectAnggaran();

             loadSelectPengguna();

             function formatRupiah(angka, prefix) {
                 var number_string = angka.replace(/[^,\d]/g, '').toString(),
                     split = number_string.split(','),
                     sisa = split[0].length % 3,
                     rupiah = split[0].substr(0, sisa),
                     ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                 // tambahkan titik jika yang di input sudah menjadi angka ribuan
                 if (ribuan) {
                     separator = sisa ? '.' : '';
                     rupiah += separator + ribuan.join('.');
                 }

                 rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                 return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
             }

             var rupiah = document.getElementById("harga_satuan");
             if (rupiah != null) {
                 rupiah.addEventListener("keyup", function(e) {
                     // tambahkan 'Rp.' pada saat form di ketik
                     // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                     rupiah.value = formatRupiah(this.value, "");
                 });
             }


             function loadSelectBarang() {

                 $('#id_barang').empty()

                 $.post('{{ URL::to('select-master-barang') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#id_barang").select2({
                         data: e,
                         theme: 'bootstrap4',
                         placeholder: '-Barang-',
                         allowClear: true,
                     })

                     $('.loader').hide()

                     $("#id_barang").val('').trigger("change");

                 });
             }


             function loadSelectAnggaran() {

                 $("#yt_anggaran").select2({
                     theme: 'bootstrap4',
                     placeholder: '-Anggaran-',
                     allowClear: true,
                 })

                 $("#yt_anggaran").val('').trigger("change");
             }

             function loadSelectPengguna() {

                 $('#pengguna').empty()

                 $.post('{{ URL::to('select-pegawai') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#pengguna").select2({
                         data: e,
                         theme: 'bootstrap4',
                         placeholder: '-Pengguna-',
                         allowClear: true,
                     })

                     $('.loader').hide()

                     $("#pengguna").val('').trigger("change");

                 });
             }

             function loadTabeDetailFpb(idmaster) {
                 $('.loader').show();

                 $('#table_fpb').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-detail-fpb') }}', {
                     idmaster,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#table_fpb").DataTable({
                         "bDestroy": true,
                         "buttons": ["copy", "excel"],
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
                         "columns": [{
                                 data: 'id',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 width: 5,
                                 className: "text-center",
                             },
                             {
                                 data: 'aksi',
                                 width: 5,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'nama_barang',
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'jumlah',
                                 width: 5,
                                 className: "align-middle text-right"
                             },
                             {
                                 data: 'harga_satuan',
                                 width: 20,
                                 className: "align-middle text-right",
                             },
                             {
                                 data: 'tot_harga',
                                 width: 20,
                                 className: "align-middle text-right"
                             },
                             {
                                 data: 'yt_anggaran',
                                 width: 8,
                                 className: "align-middle text-center"
                             },
                             {
                                 data: 'nama_pegawai',
                                 width: 15,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'deskripsi',
                                 width: 20,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'uraian_kebutuhan',
                                 width: 20,
                                 className: "align-middle text-left"
                             },

                         ],

                         columnDefs: [{
                                 targets: 4,
                                 render: $.fn.dataTable.render.number('.', '.', )
                             },
                             {
                                 targets: 5,
                                 render: $.fn.dataTable.render.number('.', '.', )
                             },
                         ],

                         footerCallback: function(row, data, start, end, display) {
                             var api = this.api();

                             var intVal = function(i) {
                                 return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                                     typeof i === 'number' ? i : 0;
                             };

                             foter_jml = api.column(3).data().reduce(function(a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0);

                             foter_harga_satuan = api.column(4).data().reduce(function(a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0);

                             foter_total = api.column(5).data().reduce(function(a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0);

                             var numFormat = $.fn.dataTable.render.number('.', '.', ).display;
                             $("#foter_jml").html(numFormat(foter_jml));
                             $("#foter_harga_satuan").html(numFormat(foter_harga_satuan));
                             $("#foter_total").html(numFormat(foter_total));
                         },
                     }).buttons().container().appendTo('#table_fpb_wrapper .col-md-6:eq(0)');

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

             // ajax form submit
             $("form#add-detail-fpb").submit(function(event) {
                 event.preventDefault();

                 $('.loader').show();

                 var formData = new FormData(this);

                 $.ajax({
                     type: "POST",
                     url: "simpan-detail-fpb",
                     data: formData,
                     processData: false,
                     contentType: false,
                     success: function(e) {
                         var sukses = e.sukses;
                         var pesan = e.pesan;

                         if (sukses == "Y") {
                             resetForm();
                             var idmaster = {{ $data->id }};
                             loadTabeDetailFpb(idmaster);
                             $('.loader').hide();
                             tampilPesan('success', pesan);
                         } else {
                             $('.loader').hide();
                             tampilPesan('error', pesan);
                         }

                     },
                     error: function(err) {
                         $('.loader').hide();
                         tampilPesan('error', 'error!');
                     }
                 })

             });

             // view_img
             $(document).on("click", ".view_img", function(e) {
                 $("#modal_img").modal("show");
                 var id = $(this).data('id');

                 $.post('{{ URL::to('view-img-fpb') }}', {
                     id,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#view_img").html(e);
                 });
             });

             // edit_detail
             $(document).on("click", ".edit_detail", function(e) {
                 var id = $(this).data('id');

                 $.post('{{ URL::to('get-data-detail-fpb-by-id') }}', {
                     id,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#id").val(e.id);
                     $("#id_barang").val(e.id_barang).trigger("change");
                     $("#jumlah").val(e.jumlah);
                     $("#yt_anggaran").val(e.yt_anggaran).trigger("change");;
                     $("#harga_satuan").val(e.harga_satuan).trigger('keypress', {
                         keyCode: 32
                     });
                     $("#pengguna").val(e.pengguna).trigger("change");
                     $("#deskripsi").val(e.deskripsi);
                     // $("#image").html('');
                 });
             });

             // reset_form
             $(document).on("click", "#reset_form", function() {
                 resetForm();
             });

             // hapus_detail
             $(document).on("click", ".hapus_detail", function(e) {
                 var id = $(this).data('id');

                 Swal.fire({
                     title: 'Apa kamu yakin?',
                     text: "Anda tidak akan dapat mengembalikan ini!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, hapus!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();

                         $.ajax({
                             url: 'hapus-detail-fpb',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(e) {
                                 var sukses = e.sukses;
                                 var pesan = e.pesan;

                                 if (sukses == "Y") {
                                     var idmaster = {{ $data->id }};
                                     loadTabeDetailFpb(idmaster);
                                     $('.loader').hide();
                                     tampilPesan('success', pesan);
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

             // ajukan_fpb
             $(document).on("click", "#ajukan_fpb", function(e) {
                 var idmaster = {{ $data->id }};
                 var tgl_pemakaian = $("#tgl_pemakaian").val();

                 if (tgl_pemakaian == "") {
                     tampilPesan('error', 'Tgl Pemakaian harus diisi!');
                 } else {
                     Swal.fire({
                         title: 'Apa kamu yakin?',
                         text: "FPB Akan diajukan, tidak bisa melakukan penambahan data lagi pada nomor surat ini!",
                         icon: 'warning',
                         showCancelButton: true,
                         confirmButtonColor: '#3085d6',
                         cancelButtonColor: '#d33',
                         confirmButtonText: 'Ya, Ajukan!'
                     }).then((result) => {
                         if (result.value) {
                             $('.loader').show();

                             $.ajax({
                                 url: 'ajukan-fpb',
                                 cache: false,
                                 type: 'post',
                                 data: {
                                     idmaster,
                                     tgl_pemakaian,
                                     _token: '{{ csrf_token() }}'
                                 },
                                 success: function(e) {
                                     var sukses = e.sukses;
                                     var pesan = e.pesan;

                                     if (sukses == "Y") {
                                         $('.loader').hide();

                                         //  tampil surat yang dicetak
                                         location.href = '/tampil-view-cetak-fpb?data=' +
                                             '{{ $data->no_surat }}';

                                         //  location.reload();
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
                 }
             });

             // cetak_fpb

             function resetForm() {
                 $("#id").val('');
                 $("#id_barang").val('').trigger("change");
                 $("#jumlah").val('');
                 $("#yt_anggaran").val('').trigger("change");;
                 $("#harga_satuan").val('');
                 $("#pengguna").val('').trigger("change");
                 $("#deskripsi").val('');
                 $("#uraian_kebutuhan").val('');
                 $("#image").val('');
             }

         });
     </script>
