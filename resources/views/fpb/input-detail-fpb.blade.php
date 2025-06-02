 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
     <div class="row pt-1">

         @if ($data->status == '')
             <div class="col-3">
                 <div class="card">
                     <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                     <form id="add-detail-fpb" enctype="multipart/form-data">

                         <div class="card-body">
                             @csrf

                             <input type="hidden" name="id_master_fpb" id="id_master_fpb" class="form-control"
                                 value="{{ $data->id }}">

                             <input type="hidden" name="no_surat" value="{{ $data->no_surat }}" id="no_surat"
                                 class="form-control">

                             <input type="hidden" name="id" id="id" class="form-control">

                             {{-- in_jenis_perangkat --}}
                             <div class="form-group mb-1">
                                 <label for="in_jenis_perangkat" style="font-size:12px;">Jenis Perangkat<b
                                         style="color: crimson"> *</b></label>

                                 <select required name="in_jenis_perangkat" id="in_jenis_perangkat"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                 </select>

                                 <p style="font-size:12px; color:darkgoldenrod; margin:1px 0px 4px 0px;"
                                     class="font-gray-dark">
                                     Jika Jenis Perangkat belum ada hubungi <b style='color:red'><a target="_blank"
                                             href='https://wa.me/6287812718400'>Staf Pembelian IT</a></b>
                             </div>

                             {{-- in_spesifikasi --}}
                             <div class="form-group mb-1">
                                 <label required for="in_spesifikasi" style="font-size:12px;">Spesifikasi<b
                                         style="color: crimson"> *</b></label>
                                 <textarea name="in_spesifikasi" id="in_spesifikasi" class="form-control" placeholder="Deskripsi"></textarea>
                             </div>

                             {{-- in_yt_anggaran --}}
                             <div class="form-group mb-1">
                                 <label required for="in_yt_anggaran" style="font-size:12px;">Anggaran<b
                                         style="color: crimson"> *</b></label>

                                 <select required name="in_yt_anggaran" id="in_yt_anggaran"
                                     class="form-control form-control-sm" style="width: 100%;">
                                     <option value="">-Anggaran-</option>
                                     <option value="Ya">Ya</option>
                                     <option value="Tidak">Tidak</option>
                                 </select>
                             </div>

                             {{-- Jika anggaran Ya, ambil data dari api rab --}}
                             <div class="form-group mb-1">
                                 <label for="in_nama_anggaran" style="font-size:12px;">Nama Anggaran <b
                                         style="color: crimson">
                                         *</b></label>

                                 <select required name="in_nama_anggaran" id="in_nama_anggaran"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- nama_biaya --}}
                             <input type="hidden" name="nama_biaya" id="nama_biaya" class="form-control form-control-sm"
                                 placeholder="Nama Biaya">

                             {{-- in_anggaran --}}
                             <div class="form-group mb-1">
                                 <label for="in_anggaran" style="font-size:12px;">Nominal peritem<b style="color: crimson">
                                         *</b></label>

                                 <input type="text" name="in_anggaran" id="in_anggaran"
                                     class="form-control form-control-sm" placeholder="Nominal">
                             </div>

                             {{-- in_jumlah --}}
                             <div class="form-group mb-1">
                                 <label required for="in_jumlah" style="font-size:12px;">Jumlah<b style="color: crimson">
                                         *</b></label>

                                 <input type="text" name="in_jumlah" id="in_jumlah" class="form-control form-control-sm"
                                     placeholder="Jumlah">
                             </div>

                             {{-- in_penguna --}}
                             <div class="form-group mb-1">
                                 <label for="in_penguna" style="font-size:12px;">Penguna <b style="color: crimson">
                                         *</b></label>

                                 <select required name="in_penguna" id="in_penguna"
                                     class="form-control form-control-sm select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- in_jabatan_penguna --}}
                             <div class="form-group mb-1">
                                 <label required for="in_jabatan_penguna" style="font-size:12px;">Jabatan<b
                                         style="color: crimson">*</b></label>

                                 <input type="text" name="in_jabatan_penguna" readonly id="in_jabatan_penguna"
                                     class="form-control form-control-sm" placeholder="Jabatan">
                             </div>

                             {{-- in_uraian_kebutuhan --}}
                             <div class="form-group mb-1">
                                 <label required for="in_uraian_kebutuhan" style="font-size:12px;">Uraian Kebutuhan<b
                                         style="color: crimson"> *</b></label>

                                 <textarea name="in_uraian_kebutuhan" id="in_uraian_kebutuhan" class="form-control" placeholder="Deskripsi"></textarea>
                             </div>

                             {{-- in_jenis_pengajuan --}}
                             <div class="form-group mb-1">
                                 <label required for="in_jenis_pengajuan" style="font-size:12px;">Jenis Pengajuan<b
                                         style="color: crimson"> *</b></label>

                                 <select required name="in_jenis_pengajuan" id="in_jenis_pengajuan"
                                     class="form-control form-control-sm" style="width: 100%;">
                                     <option value="">-Jenis Pengajuan-</option>
                                     <option value="Baru">Baru</option>
                                     <option value="Upgrade">Upgrade</option>
                                 </select>
                             </div>
                         </div>

                         <div class="card-footer">
                             <button id="btn_reset" class="btn btn-warning btn-sm"><i class="fas fa-refresh"></i>
                                 Reset</button>
                             <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i>
                                 Save</button>
                         </div>

                     </form>
                 </div>
             </div>
         @endif

         @php
             $col = 'col-9';
             if ($data->status != '') {
                 $col = 'col-12';
             }
         @endphp

         <div class="{{ $col }}">

             {{-- tabel --}}
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">
                         <i class="fas fa-file mr-1"></i>
                         <b>No Surat</b> : {{ $data->no_surat }}
                     </h3>

                     <div class="card-tools">
                         <ul class="nav nav-pills ml-auto">
                             <li class="nav-item bg-danger">
                                 <a class="nav-link" href="/master-fpb">Close</a>
                             </li>
                         </ul>
                     </div>
                 </div>

                 <div class="card-body card-body">

                     <table id="table_fpb" class="table table-bordered table-striped table-sm">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Jenis Perangkat</th>
                                 <th>Spesifikasi</th>
                                 <th>Jml</th>
                                 <th>Anggaran</th>
                                 <th>Nama Anggaran</th>
                                 <th>Harga Peritem</th>
                                 <th>Nominal Anggaran</th>
                                 <th>Uraian Kebutuhan</th>
                                 <th>Jenis Pengajuan</th>
                                 <th># </th>
                             </tr>
                         </thead>

                         <tfoot>
                             <tr>
                                 <th colspan="3" style="text-align:right; font-weight: bold;">Jumlah</th>
                                 <th id="jml"></th>
                                 <th></th>
                                 <th></th>
                                 <th></th>
                                 <th id="ang"></th>
                                 <th colspan="3"></th>
                             </tr>
                         </tfoot>
                     </table>
                 </div>

             </div>

             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-body card-body">
                     <div class="row">
                         @if ($data->status == '')
                             <div class="col-4">
                                 <button id="btn_ajukan" type="button" class="btn btn-primary"><i
                                         class="fa fa-upload"></i> Ajukan, Untuk memproses FPB
                                 </button>
                             </div>
                         @else
                             <div class="col-2">
                                 <a href="/tampil-view-cetak-fpb?data={{ $data->no_surat }}" class="btn btn-success"
                                     role="button">
                                     <i class="fa fa-print"></i>
                                     Cetak
                                 </a>
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
 @endsection

 @section('script')
     <script src="/datatable/dataTables.responsive.min.js"></script>
     <script src="/datatable/dataTables.fixedColumns.min.js"></script>

     <script>
         $(function() {
             var idmaster = {{ $data->id }};

             resetForm();

             loadTabeDetailFpb(idmaster);

             loadJenisPerangkat();

             loadPegawai();


             function loadJenisPerangkat() {
                 $('#in_jenis_perangkat').empty()

                 $.post('{{ URL::to('get-jenis-perangkat') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#in_jenis_perangkat").select2({
                         data: e,
                         //  theme: 'bootstrap4',
                         placeholder: '-Jenis Perangkat-',
                         allowClear: true,
                         tags: true,
                         selectOnBlur: true
                     });

                     $("#in_jenis_perangkat").val('').trigger("change");

                 });
             }

             function loadPegawai() {
                 $('#in_penguna').empty()

                 $.post('{{ URL::to('get-nama-pegawai') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#in_penguna").select2({
                         data: e,
                         //  theme: 'bootstrap4',
                         placeholder: '-Pengguna-',
                         allowClear: true,
                         tags: true,
                         selectOnBlur: true
                     });

                     $("#in_penguna").val('').trigger("change");

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
                                 className: "text-center",
                             },
                             {
                                 data: 'nama_perangkat',
                                 className: "text-left",
                             },
                             {
                                 data: 'spesifikasi',
                                 className: "text-left",
                             },
                             {
                                 data: 'jumlah',
                                 className: "text-right",
                             },
                             {
                                 data: 'yt_anggaran',
                                 className: "text-center",
                             },
                             {
                                 data: 'nama_anggaran',
                                 className: "text-left",
                             },
                             {
                                 data: 'harga_peritem',
                                 className: "text-right",
                             },
                             {
                                 data: 'anggaran',
                                 className: "text-right",
                             },
                             {
                                 data: 'uraian_kebutuhan',
                                 className: "text-left",
                             },
                             {
                                 data: 'jenis_pengajuan',
                                 className: "text-left",
                             },
                             {
                                 data: 'aksi',
                                 className: "text-left",
                             },

                         ],

                         columnDefs: [{
                                 targets: 6,
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

                             jml = api.column(3).data().reduce(function(a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0);

                             ang = api.column(7).data().reduce(function(a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0);

                             var numFormat = $.fn.dataTable.render.number('.', '.', ).display;
                             $("#jml").html(numFormat(jml));
                             $("#ang").html(numFormat(ang));
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

             $('#in_yt_anggaran').on('change', function() {
                 var data = this.value;
                 if (data == 'Tidak') {
                     $('#in_nama_anggaran').val('').trigger("change");
                     $('#in_nama_anggaran').prop('disabled', true);

                     $('#nama_biaya').val('');

                     $('#in_anggaran').val('');
                     $('#in_anggaran').attr('readonly', 'readonly');

                     $('#in_jumlah').val('');
                     $('#in_jumlah').removeAttr('readonly', 'readonly')

                 } else {
                     $('#in_nama_anggaran').removeAttr('disabled');
                     $('#in_anggaran').removeAttr('readonly');

                     $('#in_anggaran').val('');
                     $('#in_anggaran').attr('readonly', 'readonly');

                     $('#in_jumlah').val('');
                     $('#in_jumlah').attr('readonly', 'readonly')

                     loadDataAnggaran();

                 }
             });

             $('#in_nama_anggaran').on('change', function() {
                 var id = $("#in_nama_anggaran").val();

                 if (id != null) {
                     $('.loader').show();

                     // get-anggaran-rab-detail
                     $.post('{{ URL::to('get-anggaran-rab-detail') }}', {
                         id,
                         _token: '{{ csrf_token() }}'
                     }, function(e) {
                         $('#in_anggaran').val(e.harga_satuan);
                         $('#in_jumlah').val(e.qty);
                         $('#nama_biaya').val(e.nama_biaya);
                     }).done(function(data) {
                         $('.loader').hide();
                     });
                 }
             });

             function loadDataAnggaran() {
                 $('#in_nama_anggaran').empty()

                 $('.loader').show();

                 $.post('{{ URL::to('get-anggaran-rab') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#in_nama_anggaran").select2({
                         data: e,
                         //  theme: 'bootstrap4',
                         placeholder: '-Anggaran Bulanan-',
                         allowClear: true,
                         tags: true,
                         selectOnBlur: true
                     });

                     $("#in_nama_anggaran").val('').trigger("change");
                 }).done(function(e) {
                     $('.loader').hide();
                 });

             }

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

             var rupiah = document.getElementById("in_anggaran");
             rupiah.addEventListener("keyup", function(e) {
                 // tambahkan 'Rp.' pada saat form di ketik
                 // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                 rupiah.value = formatRupiah(this.value, "");
             });

             // on change penguna
             $('#in_penguna').on('select2:select', function(e) {
                 var kode_pegawai = $("#in_penguna").val();
                 // alert(kode_pegawai);

                 $('.loader').show();

                 $.post('{{ URL::to('get-jabatan') }}', {
                     kode_pegawai,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     // console.log(e);
                     $("#in_jabatan_penguna").val(e.jabatan);
                 }).done(function(e) {
                     $('.loader').hide();
                 });
             });

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

             // edit_detail
             $(document).on("click", ".edit_pengajuan_detail", function(e) {
                 var id = $(this).data('id');

                 $.post('{{ URL::to('get-data-detail-fpb-by-id') }}', {
                     id,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#id").val(e.id);

                     $("#in_jenis_perangkat").val(e.jenis_perangkat).trigger("change");
                     $("#in_jenis_perangkat").val(e.jenis_perangkat).trigger("change");
                     $("#in_spesifikasi").val(e.spesifikasi);
                     $("#in_jumlah").val(e.jumlah);
                     $("#in_yt_anggaran").val(e.yt_anggaran);

                     $("#in_uraian_kebutuhan").val(e.uraian_kebutuhan);
                     $("#in_jenis_pengajuan").val(e.jenis_pengajuan);
                     $("#in_penguna").val(e.nama_penguna).trigger("change");
                     $("#in_jabatan_penguna").val(e.jabatan_penguna);


                     $("#in_anggaran").val(e.harga_peritem).trigger('keypress', {
                         keyCode: 32
                     });

                     if (e.yt_anggaran == "Ya") {
                         loadDataAnggaran();

                         setTimeout(function() {
                             $("#in_nama_anggaran").val(e.id_anggaran).trigger("change");
                             $("#nama_biaya").val(e.nama_anggaran);

                             $('#in_nama_anggaran').removeAttr('disabled');

                             $('#in_anggaran').attr('readonly', 'readonly');
                             $('#in_jumlah').attr('readonly', 'readonly')
                         }, 1500);
                     }else{
                             $('#in_nama_anggaran').prop('disabled', true);
                             $('#in_anggaran').attr('readonly', 'readonly');
                             $('#in_jumlah').removeAttr('readonly', 'readonly')
                     }

                 });
             });

             //  btn_reset
             $(document).on("click", "#btn_reset", function(e) {
                 e.stopPropagation();
                 e.stopImmediatePropagation();
                 e.preventDefault();
                 resetForm();
             });

             // hapus_detail
             $(document).on("click", ".hapus_pengajuan_detail", function(e) {
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

                                     resetForm();
                                 } else {
                                     $('.loader').hide();
                                     tampilPesan('error', pesan);
                                     resetForm();
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

             // btn_ajukan
             $(document).on("click", "#btn_ajukan", function(e) {
                 e.stopPropagation();
                 e.stopImmediatePropagation();
                 e.preventDefault();

                 var no_surat = $("#no_surat").val();

                 var table = $('#table_fpb').DataTable().rows().data();

                 if (table.length == 0) {
                     tampilPesan('error', 'Barang pengajuan tidak boleh kosong!')
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
                                     no_surat,
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

             function resetForm() {
                 $('#id').val('');
                 $('#nama_biaya').val('');
                 $("#in_nama_anggaran").val('').trigger("change");
                 $("#in_jenis_perangkat").val('').trigger("change");
                 $("#in_spesifikasi").val('');
                 $("#in_jumlah").val('');
                 $("#in_yt_anggaran").val('');
                 $("#in_anggaran").val('');
                 $("#in_uraian_kebutuhan").val('');
                 $("#in_jenis_pengajuan").val('');
                 $("#in_penguna").val('').trigger("change");
                 $("#in_jabatan_penguna").val('');
             }

         });
     </script>
 @endsection
