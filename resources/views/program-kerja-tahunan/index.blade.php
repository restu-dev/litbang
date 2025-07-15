 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
     {{-- filter --}}
     <div class="row">
         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-body card-body">
                     <div class="row justify-content-start">
                         {{-- filter approvement --}}
                         <div class="col-3">
                             <select id="filter_status_capaian" class="form-control select2" style="width: 100%;">
                             </select>
                         </div>

                         {{-- filter approvement --}}
                         <div class="col-3">
                             <select id="filter_approvement" class="form-control select2" style="width: 100%;">
                                 <option value="">-Approvement-</option>
                                 <option value="Belum">Belum</option>
                                 <option value="Ya">Ya</option>
                                 <option value="Tidak">Tidak</option>
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
                                 <th>Program Kerja</th>
                                 <th>Penanggung Jawab</th>
                                 <th>Target Frekuensi Tahunan</th>
                                 <th>Indikator Kinerja</th>
                                 <th>Capaian Aktual</th>
                                 <th>Pro Capaian</th>
                                 <th>Status Capaian</th>
                                 <th>Keterangan</th>
                                 <th>Approvement</th>
                             </tr>
                         </thead>
                     </table>
                 </div>

             </div>
         </div>
     </div>

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
                     <input type="hidden" name="id" class="form-control" id="id">

                     {{-- Program Kerja --}}
                     <div class="form-group">
                         <label for="program_kerja">Program Kerja</label>
                         <input type="text" name="program_kerja" class="form-control" id="program_kerja"
                             placeholder="Input Program Kerja">
                     </div>

                     {{-- Target Frekuensi Tahunan --}}
                     <div class="form-group">
                         <label for="target_frekuensi_tahunan">Target Frekuensi Tahunan</label>
                         <input type="number" name="target_frekuensi_tahunan" class="form-control"
                             id="target_frekuensi_tahunan" placeholder="Input Target Frekuensi Tahunan (Angka)">
                     </div>

                     {{-- Indikator Kinerja --}}
                     <div class="form-group">
                         <label for="indikator_kinerja">Indikator Kinerja</label>
                         <textarea name="indikator_kinerja" id="indikator_kinerja" class="form-control" placeholder="Input Indikator Kinerja"></textarea>
                     </div>

                     {{-- Status Capaian --}}
                     <div class="form-group">
                         <label for="status_capaian">Status Capaian</label>

                         <select id="status_capaian" class="form-control select2" style="width: 100%;">
                         </select>
                     </div>

                     {{-- Keterangan --}}
                     <div class="form-group">
                         <label for="keterangan">Keterangan</label>
                         <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Input Keterangan"></textarea>
                     </div>

                 </div>

                 <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="button" id="save_form" class="btn btn-primary">Simpan</button>
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
             loadFilterStatusCapaian();

             function loadFilterStatusCapaian() {
                 $('.loader').show()
                 $('#filter_status_capaian').empty()

                 $.post('{{ URL::to('select-status-capaian') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#filter_status_capaian").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()
                     $("#filter_status_capaian").val('').trigger("change");
                 });

                 $("#filter_status_capaian").append("<option value='' selected>-Status Capaian-</option>");

             }

             // on change filter_status_capaian
             $('#filter_status_capaian').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();


                 loadTabelData(filter_status_capaian, filter_approvement);

             });

             // on change filter_approvement
             $('#filter_approvement').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();

                 loadTabelData(filter_status_capaian, filter_approvement);

             });


             loadSelectStatusCapaian();
             loadTabelData('', '');

             function loadSelectStatusCapaian() {
                 $('.loader').show()
                 $('#status_capaian').empty()

                 $.post('{{ URL::to('select-status-capaian') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#status_capaian").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()
                     $("#status_capaian").val('').trigger("change");
                 });
             }

             // load tabel unit
             function loadTabelData(filter_status_capaian, filter_approvement) {
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-program-kerja-tahunan') }}', {
                      filter_status_capaian, 
                      filter_approvement,
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
                                 data: 'program_kerja',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_pegawai',
                                 className: "text-left",
                             },
                             {
                                 data: 'target_frekuensi_tahunan',
                                 className: "text-left",
                             },
                             {
                                 data: 'indikator_kinerja',
                                 className: "text-left",
                             },
                             {
                                 data: 'capaian_aktual',
                                 className: "text-left",
                             },
                             {
                                 data: 'pro_capaian',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_status_pencapaian',
                                 className: "text-left",
                             },
                             {
                                 data: 'keterangan',
                                 className: "text-left",
                             },
                             {
                                 data: 'approvement',
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
                     $.post('{{ URL::to('get-data-edit-program-kerja-tahunan-by-id') }}', {
                         id,
                         _token: '{{ csrf_token() }}'
                     }, function(e) {
                         $("#id").val(id);
                         $("#status_capaian").val(e.id_status_capaian).trigger("change");
                         $("#program_kerja").val(e.program_kerja);
                         $("#target_frekuensi_tahunan").val(e.target_frekuensi_tahunan);
                         $("#indikator_kinerja").val(e.indikator_kinerja);
                         $("#keterangan").val(e.indikator_kinerja);
                     });
                 }

                 $("#modal_add_edit").modal("show");
             });

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 e.stopPropagation();
                 e.stopImmediatePropagation();
                 e.preventDefault();

                 var id = $("#id").val();

                 var program_kerja = $("#program_kerja").val();
                 var target_frekuensi_tahunan = $("#target_frekuensi_tahunan").val();
                 var indikator_kinerja = $("#indikator_kinerja").val();
                 var status_capaian = $("#status_capaian").val();
                 var keterangan = $("#keterangan").val();

                 if (program_kerja == "") {
                     tampilPesan('warning', ' Program Kerja tidak boleh kosong!');
                 } else if (target_frekuensi_tahunan == "") {
                     tampilPesan('warning', ' Target Frekuensi Tahunan tidak boleh kosong!');
                 } else if (indikator_kinerja == "") {
                     tampilPesan('warning', ' Indikator Kinerja tidak boleh kosong!');
                 } else if (status_capaian == null) {
                     tampilPesan('warning', ' Status Capaian tidak boleh kosong!');
                 } else if (keterangan == "") {
                     tampilPesan('warning', ' Keterangan tidak boleh kosong!');
                 } else {
                     $.ajax({
                         url: "simpan-program-kerja-tahunan",
                         cache: false,
                         type: 'post',
                         data: {
                             id,
                             program_kerja,
                             target_frekuensi_tahunan,
                             indikator_kinerja,
                             status_capaian,
                             keterangan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var pesan = result.pesan;

                             if (sukses == "Y") {
                                 var filter_status_capaian = $("#filter_status_capaian").val();
                                 var filter_approvement = $("#filter_approvement").val();

                                 loadTabelData(filter_status_capaian, filter_approvement);
                                 $('.loader').hide();
                                 tampilPesan('success', result.pesan);

                                 resetForm();

                                 $("#modal_add_edit").modal("hide");

                             } else {
                                 $('.loader').hide();
                                 tampilPesan('error', result.pesan);
                             }

                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
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
                             url: '/destroy-program-kerja-tahunan',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 var filter_status_capaian = $("#filter_status_capaian")
                                     .val();
                                 var filter_approvement = $("#filter_approvement").val();

                                 loadTabelData(filter_status_capaian,
                                 filter_approvement);
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
                 $("#id").val('');

                 $("#status_capaian").val('').trigger("change");
                 $("#status_capaian").prop('disabled', false);

                 $("#kode_jenis").val('');

             }

         });
     </script>
 @endsection
