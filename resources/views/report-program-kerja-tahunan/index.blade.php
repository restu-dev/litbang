 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                <div class="card-body">
                    <form>
                        <div class="row g-2">
                            {{-- filter tahun --}}
                            <div class="col-12 col-md-3">
                                <select id="filter_tahun_pelajaran" class="form-control select2" style="width: 100%;">
                                </select>
                            </div>

                            @if (Auth::guard('admin')->check())
                                {{-- filter bidang --}}
                                {{-- <div class="col-12 col-md-3">
                                    <select id="filter_bidang" class="form-control select2" style="width: 100%;">
                                    </select>
                                </div> --}}
                            @endif

                            {{-- filter status --}}
                            <div class="col-12 col-md-3">
                                <select id="filter_status_capaian" class="form-control select2" style="width: 100%;">
                                </select>
                            </div>

                            {{-- filter approvement --}}
                            {{-- 
                            <div class="col-12 col-md-3">
                                <select id="filter_approvement" class="form-control select2" style="width: 100%;">
                                    <option value="">-Approvement-</option>
                                    <option value="Belum">Belum</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div> 
                            --}}
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
                                 <th>Program Kerja</th>
                                 <th>Tahun</th>
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
 @endsection

 @section('script')
     <script src="/datatable/dataTables.responsive.min.js"></script>
     <script src="/datatable/dataTables.fixedColumns.min.js"></script>

     <script>
         $(function() {

             var id_tahun_ajaran = {{ $id_tahun_ajaran }};
             var nama_tahun_ajarantahun_ajaran = {{ $nama_tahun_ajaran }};

             loadFilterTahunPelajaran();
             loadFilterStatusCapaian();
             loadFilterBidang();

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

             function loadFilterTahunPelajaran() {
                 $('.loader').show()
                 $('#filter_tahun_pelajaran').empty()

                 $.post('{{ URL::to('select-tahun-pelajaran') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#filter_tahun_pelajaran").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()

                     $("#filter_tahun_pelajaran").val(id_tahun_ajaran).trigger("change");
                 });

                 //  $("#filter_tahun_pelajaran").append("<option value='' selected>-Tahun Pelajaran-</option>");

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

             // on change filter_tahun_pelajaran
             $('#filter_tahun_pelajaran').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();
                 var filter_tahun = $("#filter_tahun_pelajaran").val();
                 var filter_bidang = $("#filter_bidang").val();

                 loadTabelData(filter_status_capaian, filter_approvement, filter_tahun, filter_bidang);
             });

             // on change filter_status_capaian
             $('#filter_status_capaian').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();
                 var filter_tahun = $("#filter_tahun_pelajaran").val();
                 var filter_bidang = $("#filter_bidang").val();

                 loadTabelData(filter_status_capaian, filter_approvement, filter_tahun, filter_bidang);
             });

             // on change filter_approvement
             $('#filter_approvement').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();
                 var filter_tahun = $("#filter_tahun_pelajaran").val();
                 var filter_bidang = $("#filter_bidang").val();

                 loadTabelData(filter_status_capaian, filter_approvement, filter_tahun, filter_bidang);
             });

             // on change filter_bidang
             $('#filter_bidang').on("change", function(e) {

                 var filter_status_capaian = $("#filter_status_capaian").val();
                 var filter_approvement = $("#filter_approvement").val();
                 var filter_tahun = $("#filter_tahun_pelajaran").val();
                 var filter_bidang = $("#filter_bidang").val();

                 loadTabelData(filter_status_capaian, filter_approvement, filter_tahun, filter_bidang);
             });

             loadTabelData('', '', '', '');

             // load tabel unit
             function loadTabelData(filter_status_capaian, filter_approvement, filter_tahun, filter_bidang) {
                 $('.loader').show();

                 $('#tabel_master').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-report-program-tahunan') }}', {
                     filter_status_capaian,
                     filter_approvement,
                     filter_tahun,
                     filter_bidang,
                     id_tahun_ajaran,
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
                                 data: 'program_kerja',
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
                 });
             }

         });
     </script>
 @endsection
