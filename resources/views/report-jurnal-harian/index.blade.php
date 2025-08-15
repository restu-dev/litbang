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
                 <div class="card-body">
                     <form>
                         <div class="row g-3">
                             {{-- date range --}}
                             <div class="col-12 col-md-3">
                                 <div class="input-group">
                                     <span class="input-group-text">
                                         <i class="far fa-calendar-alt"></i>
                                     </span>
                                     <input type="text" class="form-control" id="filter_tgl" placeholder="Tanggal">
                                 </div>
                             </div>
                             {{-- filter ada_tidak_program_kerja --}}
                             <div class="col-12 col-md-2">
                                 <select id="filter_ada_tidak_program_kerja" class="form-control select2"
                                     style="width: 100%;">
                                     <option value="">-Ada / Tidak Program-</option>
                                     <option value="Y">Ada</option>
                                     <option value="T">Tidak</option>
                                 </select>
                             </div>
                             @if (Auth::guard('admin')->check())
                                 {{-- filter_bidang --}}
                                 {{-- <div class="col-12 col-md-2">
                                    <select id="filter_bidang" class="form-control select2" style="width: 100%;">
                                    </select>
                                </div> --}}
                             @endif
                             {{-- filter_jenis_kegiatan --}}
                             <div class="col-12 col-md-2">
                                 <select id="filter_jenis_kegiatan" class="form-control select2" style="width: 100%;">
                                 </select>
                             </div>
                             {{-- filter pencapaian --}}
                             <div class="col-12 col-md-3">
                                 <select id="filter_status_pencapaian" class="form-control select2" style="width: 100%;">
                                 </select>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <div class="row">
         <div class="col-lg-12">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel {{ $title }}</h3>
                 </div>

                 <div class="card-body">
                     <table id="tabel_master" class="table table-bordered table-striped table-sm">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Bidang</th>
                                 <th>Tahun</th>
                                 <th>Nama Pegawai</th>
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

     {{-- modal preview dokumen --}}
     <div class="modal fade" id="modal_preview_dokumen">
         <div class="modal-dialog modal-lg">
             <div class="modal-content">

                 <div class="modal-header">
                     <h4 class="modal-title">Preview Dokumen</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>

                 <div class="modal-body">
                     <div id="preview_dokumen"></div>
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
         $('#filter_tgl').daterangepicker();

         $(function() {

             var id_tahun_ajaran = {{ $id_tahun_ajaran }};

             loadFilterJenisKegiatan();
             loadFilterProgramKerja();
             loadFilterStatusPencapaian();
             loadFilterBidang();

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
                     _token: '{{ csrf_token() }}',
                     id_tahun_ajaran
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

             function loadFilterBidang() {
                 $('.loader').show()
                 $('#filter_bidang').empty()

                 $.post('{{ URL::to('select-bidang') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#filter_bidang").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()

                     $("#filter_bidang").val("").trigger("change");
                 });

                 $("#filter_bidang").append("<option value='' selected>-Bidang-</option>");

             }

             // on change filter_tgl
             $('#filter_tgl').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             // on change filter_ada_tidak_program_kerja
             $('#filter_ada_tidak_program_kerja').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             // on change filter_jenis_kegiatan
             $('#filter_jenis_kegiatan').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             // on change filter_program_kerja
             $('#filter_program_kerja').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             // on change filter_status_pencapaian
             $('#filter_status_pencapaian').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             // on change filter_bidang
             $('#filter_bidang').on("change", function(e) {

                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_program_kerja = $("#filter_program_kerja").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();
                 var filter_tgl = $("#filter_tgl").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();

                 loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                     filter_status_pencapaian, filter_bidang, filter_ada_tidak_program_kerja);
             });

             loadTabelData('', '', '', '', '', '');

             // load tabel
             function loadTabelData(filter_tgl, filter_jenis_kegiatan, filter_program_kerja,
                 filter_status_pencapaian,
                 filter_bidang, filter_ada_tidak_program_kerja) {
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-report-jurnal-harian') }}', {
                     filter_tgl,
                     filter_jenis_kegiatan,
                     filter_program_kerja,
                     filter_status_pencapaian,
                     id_tahun_ajaran,
                     filter_bidang,
                     filter_ada_tidak_program_kerja,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_master").DataTable({
                         "bDestroy": true,
                         @if (session('yt_print') == 'Y')
                             "buttons": ["excel", "pdf", "print"],
                         @endif
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
                                 data: 'nama_bidang',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_tahun_pelajaran',
                                 className: "text-left",
                             },
                             {
                                 data: 'nama_pegawai',
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
                 });
             }

             //  modal_preview_dokumen
             $(document).on("click", ".preview_dokumen", function(e) {
                 var id = $(this).data('id');
                 var jenis = $(this).data('jenis');

                 $.post('{{ URL::to('preview-dokumen') }}', {
                     id,
                     jenis,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#modal_preview_dokumen").modal("show");
                     $("#preview_dokumen").html(e);
                 });

             });

         });
     </script>
 @endsection
