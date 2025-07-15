 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
     @if (session('id_bidang') == '')
         <div class="alert alert-danger alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <h5><i class="icon fas fa-ban"></i> Peringatan !</h5>
             Bidang user belum disetting Admin, hubungi admin terlebih dahulu untuk setting bidang!
         </div>
     @else
         {{-- filter --}}
         <div class="row">
             <div class="col-lg-12">
                 <div class="card">
                     <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                     <div class="card-body card-body">
                         <div class="row justify-content-start">
                             {{-- filter_jenis_kegiatan --}}
                             <div class="col-3">
                                 <select id="filter_jenis_kegiatan" class="form-control select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- filter_program_kerja --}}
                             <div class="col-3">
                                 <select id="filter_program_kerja" class="form-control select2" style="width: 100%;">
                                 </select>
                             </div>

                             {{-- filter approvement --}}
                             <div class="col-3">
                                 <select id="filter_status_pencapaian" class="form-control select2" style="width: 100%;">
                                 </select>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="row">
             <div class="col-lg-12">
                 <div class="card">
                     <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                     <div class="card-header">

                         <div class="btn-group">
                             <button type="button" title="Add" data-btn="add" class="add_edit_data btn btn-success"><i
                                     class="fa fa-add"></i> Add Data</button>
                         </div>
                     </div>

                     <div class="card-body">
                         <table id="tabel_master" class="table table-bordered table-striped table-sm">
                             <thead>
                                 <tr>
                                     <th>No</th>
                                     <th>Aksi</th>
                                     <th>Bidang</th>
                                     <th>Uraian Kegiatan</th>
                                     <th>Jenis Kegiatan</th>
                                     <th>Output Dokumen</th>
                                     <th>Dokumen</th>
                                     <th>Foto</th>
                                     <th>Program Kerja</th>
                                     <th>Tgl Mulai</th>
                                     <th>Tgl Selesai</th>
                                     <th>Status Pencapaian</th>
                                     <th>Keterangan</th>
                                 </tr>
                             </thead>
                         </table>
                     </div>

                 </div>
             </div>
         </div>
     @endif



     {{-- modal add edit --}}
     <div class="modal fade" id="modal_add_edit">
         <div class="modal-dialog">
             <div class="modal-content">

                 <div class="modal-header">
                     <h4 class="modal-title">Default Modal</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>

                 <div class="modal-body">
                     <form id="jurnalForm" enctype="multipart/form-data">
                         @csrf

                         <input type="hidden" name="id" id="id">

                         {{-- uraian_kegiatan --}}
                         <div class="form-group">
                             <label for="uraian_kegiatan">Uraian Kegiatan</label>
                             <input type="text" name="uraian_kegiatan" class="form-control" id="uraian_kegiatan"
                                 placeholder="Input Uraian Kegiatan">
                         </div>

                         {{-- id_jenis_kegiatan --}}
                         <div class="form-group">
                             <label for="id_jenis_kegiatan">Jenis Kegiatan</label>

                             <select id="id_jenis_kegiatan" name="id_jenis_kegiatan" class="form-control select2"
                                 style="width: 100%;">
                             </select>
                         </div>

                         {{-- output_dokumen --}}
                         <div class="form-group">
                             <label for="output_dokumen">Output Dokumen</label>
                             <input type="text" name="output_dokumen" class="form-control" id="output_dokumen"
                                 placeholder="Input Output Dokumen">
                         </div>

                         {{-- id_program_kerja_tahunan --}}
                         <div class="form-group">
                             <label for="id_program_kerja_tahunan">Program Kerja</label>

                             <select id="id_program_kerja_tahunan" name="id_program_kerja_tahunan"
                                 class="form-control select2" style="width: 100%;">
                             </select>
                         </div>

                         {{-- tanggal_mulai --}}
                         <div class="form-group">
                             <label for="tanggal_mulai">Tanggal Mulai</label>
                             <input type="date" name="tanggal_mulai" class="form-control" id="tanggal_mulai"
                                 placeholder="Input Tanggal Mulai">
                         </div>

                         {{-- tanggal_selesai --}}
                         <div class="form-group">
                             <label for="tanggal_selesai">Tanggal Selesai</label>
                             <input type="date" name="tanggal_selesai" class="form-control" id="tanggal_selesai"
                                 placeholder="Input Tanggal Selesai">
                         </div>

                         {{-- id_status_pencapaian --}}
                         <div class="form-group">
                             <label for="id_status_pencapaian">Status Capaian</label>

                             <select id="id_status_pencapaian" name="id_status_pencapaian" class="form-control select2"
                                 style="width: 100%;">
                             </select>
                         </div>

                         {{-- file_dokumen --}}
                         <div class="form-group">
                             <label for="file_dokumen">File Dokumen (PDF)</label>
                             <input type="file" name="file_dokumen" accept=".pdf" class="form-control"
                                 id="file_dokumen" placeholder="Input File Dokumen (PDF)">
                         </div>

                         {{-- file_foto --}}
                         <div class="form-group">
                             <label for="file_foto">File Foto (Gambar)</label>
                             <input type="file" name="file_foto" accept="image/*" class="form-control"
                                 id="file_foto" placeholder="Input Foto (Gambar)">
                         </div>

                         {{-- Keterangan --}}
                         <div class="form-group">
                             <label for="keterangan">Keterangan</label>
                             <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Input Keterangan"></textarea>
                         </div>

                 </div>

                 <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="submit" id="save_form" class="btn btn-primary">Simpan</button>
                 </div>

                 </form>
             </div>
         </div>
     @endsection

     @section('script')
         <script src="/datatable/dataTables.responsive.min.js"></script>
         <script src="/datatable/dataTables.fixedColumns.min.js"></script>

         <script>
             $(function() {
                 loadFilterJenisKegiatan();
                 loadFilterProgramKerja();
                 loadFilterStatusPencapaian();

                 function loadFilterJenisKegiatan() {
                     $('.loader').show()
                     $('#filter_jenis_kegiatan').empty()

                     $.post('{{ URL::to('select-jenis-kegiatan') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#filter_jenis_kegiatan").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#filter_jenis_kegiatan").val('').trigger("change");
                     });

                     $("#filter_jenis_kegiatan").append("<option value='' selected>-Jenis Kegiatan-</option>");

                 }

                 function loadFilterProgramKerja() {
                     $('.loader').show()
                     $('#filter_program_kerja').empty()

                     $.post('{{ URL::to('select-program-kerja-by-user') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#filter_program_kerja").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#filter_program_kerja").val('').trigger("change");
                     });

                     $("#filter_program_kerja").append("<option value='' selected>-Program Kerja-</option>");

                 }

                 function loadFilterStatusPencapaian() {
                     $('.loader').show()
                     $('#filter_status_pencapaian').empty()

                     $.post('{{ URL::to('select-status-capaian') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#filter_status_pencapaian").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#filter_status_pencapaian").val('').trigger("change");
                     });

                     $("#filter_status_pencapaian").append("<option value='' selected>-Status Capaian-</option>");

                 }

                 // on change filter_jenis_kegiatan
                 $('#filter_jenis_kegiatan').on("change", function(e) {

                     var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                     var filter_program_kerja = $("#filter_program_kerja").val();
                     var filter_status_pencapaian = $("#filter_status_pencapaian").val();

                     loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian);
                 });

                 // on change filter_program_kerja
                 $('#filter_program_kerja').on("change", function(e) {

                     var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                     var filter_program_kerja = $("#filter_program_kerja").val();
                     var filter_status_pencapaian = $("#filter_status_pencapaian").val();

                     loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian);
                 });

                
                 // on change filter_status_pencapaian
                 $('#filter_status_pencapaian').on("change", function(e) {

                     var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                     var filter_program_kerja = $("#filter_program_kerja").val();
                     var filter_status_pencapaian = $("#filter_status_pencapaian").val();

                     loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian);
                 });

                 loadSelectJenisKegiatan();
                 loadSelectStatusCapaian();
                 loadSelectProgramKerja();
                 loadTabelData('', '' , '');

                 function loadSelectJenisKegiatan() {
                     $('.loader').show()
                     $('#id_jenis_kegiatan').empty()

                     $.post('{{ URL::to('select-jenis-kegiatan') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#id_jenis_kegiatan").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#id_jenis_kegiatan").val('').trigger("change");
                     });
                 }

                 function loadSelectStatusCapaian() {
                     $('.loader').show()
                     $('#id_status_pencapaian').empty()

                     $.post('{{ URL::to('select-status-capaian') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#id_status_pencapaian").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#id_status_pencapaian").val('').trigger("change");
                     });
                 }

                 function loadSelectProgramKerja() {
                     $('.loader').show()
                     $('#id_program_kerja_tahunan').empty()

                     $.post('{{ URL::to('select-program-kerja-by-user') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#id_program_kerja_tahunan").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#id_program_kerja_tahunan").val('').trigger("change");
                     });
                 }

                 // load tabel unit
                 function loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian) {
                     $('.loader').show();

                     $('#tabel_master').DataTable().destroy();

                     $.post('{{ URL::to('load-tabel-jurnal-harian') }}', {
                         filter_jenis_kegiatan,
                         filter_program_kerja,
                         filter_status_pencapaian,
                         _token: '{{ csrf_token() }}'
                     }, function(e) {
                         var tabel = $("#tabel_master").DataTable({
                             "bDestroy": true,
                             "buttons": ["excel", "pdf", "print"],
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
                                 left: 3,
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
                                     data: 'aksi',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'nama_bidang',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'uraian_kegiatan',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'nama_jenis_kegiatan',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'output_dokumen',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'file_dokumen',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'file_foto',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'nama_program_kerja',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'tanggal_mulai',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'tanggal_selesai',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'nama_status_pencapaian',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'keterangan',
                                     className: "text-left",
                                 }
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

                 // add modal_add_edit
                 $(document).on("click", ".add_edit_data", function(e) {
                     e.stopPropagation();
                     e.stopImmediatePropagation();
                     e.preventDefault();

                     var btn = $(this).data("btn");

                     if (btn == "add") {
                         $(".modal-title").html("Add data");
                         resetForm();
                     }

                     if (btn == "edit") {
                         resetForm();

                         $(".modal-title").html("Edit data");

                         var id = $(this).data("id");

                         // get data edit by id
                         $.post('{{ URL::to('get-data-edit-jurnal-harian-by-id') }}', {
                             id,
                             _token: '{{ csrf_token() }}'
                         }, function(e) {
                             $("#id").val(e.id);

                             $("#id_program_kerja_tahunan").val(e.id_program_kerja_tahunan).trigger(
                                 "change");
                             $("#id_status_pencapaian").val(e.id_status_pencapaian).trigger("change");
                             $("#id_jenis_kegiatan").val(e.id_status_pencapaian).trigger("change");
                             $("#uraian_kegiatan").val(e.uraian_kegiatan);
                             $("#output_dokumen").val(e.output_dokumen);
                             $("#tanggal_mulai").val(e.tanggal_mulai);
                             $("#tanggal_selesai").val(e.tanggal_mulai);
                             $("#keterangan").val(e.keterangan);
                         });
                     }

                     $("#modal_add_edit").modal("show");
                 });

                 $('#jurnalForm').submit(function(e) {
                     e.preventDefault();
                     let formData = new FormData(this);

                     var uraian_kegiatan = $("#uraian_kegiatan").val();
                     var id_jenis_kegiatan = $("#id_jenis_kegiatan").val();
                     var output_dokumen = $("#output_dokumen").val();
                     var id_program_kerja_tahunan = $("#id_program_kerja_tahunan").val();
                     var tanggal_mulai = $("#tanggal_mulai").val();
                     var tanggal_selesai = $("#tanggal_selesai").val();
                     var id_status_pencapaian = $("#id_status_pencapaian").val();
                     var keterangan = $("#keterangan").val();

                     if (uraian_kegiatan == "") {
                         tampilPesan('warning', ' Uraian Kegiatan tidak boleh kosong!');
                     } else if (id_jenis_kegiatan == null) {
                         tampilPesan('warning', ' Jenis Kegiatan tidak boleh kosong!');
                     } else if (output_dokumen == "") {
                         tampilPesan('warning', ' Output Dokumen tidak boleh kosong!');
                     } else if (id_program_kerja_tahunan == null) {
                         tampilPesan('warning', ' Program Kerja Tahunan tidak boleh kosong!');
                     } else if (tanggal_mulai == "") {
                         tampilPesan('warning', ' Tanggal Mulai tidak boleh kosong!');
                     } else if (tanggal_selesai == "") {
                         tampilPesan('warning', ' Tanggal Selesai tidak boleh kosong!');
                     } else if (id_status_pencapaian == null) {
                         tampilPesan('warning', ' Status Pencapaian tidak boleh kosong!');
                     } else {

                         $.ajax({
                             url: 'simpan-jurnal-harian',
                             type: 'POST',
                             data: formData,
                             processData: false,
                             contentType: false,
                             success: function(result) {

                                 var sukses = result.sukses;
                                 var pesan = result.pesan;

                                 if (sukses == "Y") {
                                    var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                                    var filter_program_kerja = $("#filter_program_kerja").val();
                                    var filter_status_pencapaian = $("#filter_status_pencapaian").val();

                                    loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian);

                                     $('.loader').hide();
                                     tampilPesan('success', result.pesan);

                                     resetForm();

                                     $("#modal_add_edit").modal("hide");

                                 } else {
                                     $('.loader').hide();
                                     tampilPesan('error', result.pesan);
                                 }

                             },
                             error: function(xhr) {
                                 alert('Gagal menyimpan data:\n' + xhr.responseText);
                             }
                         });

                     }

                 });

                 //  hapus_data
                 $(document).on("click", ".hapus_data", function(e) {
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
                                 url: '/destroy-jurnal-harian',
                                 cache: false,
                                 type: 'post',
                                 data: {
                                     id: id,
                                     _token: '{{ csrf_token() }}'
                                 },
                                 success: function(result) {
                                     var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                                    var filter_program_kerja = $("#filter_program_kerja").val();
                                    var filter_status_pencapaian = $("#filter_status_pencapaian").val();

                                    loadTabelData(filter_jenis_kegiatan, filter_program_kerja, filter_status_pencapaian);

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

                 function resetForm() {
                     $('#jurnalForm')[0].reset();
                     $("#id_jenis_kegiatan").val('').trigger("change");
                     $("#id_program_kerja_tahunan").val('').trigger("change");
                     $("#id_status_pencapaian").val('').trigger("change");
                 }

             });
         </script>
     @endsection
