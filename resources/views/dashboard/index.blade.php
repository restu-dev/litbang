 @extends('layouts.main')

 @section('css')
     <link rel="stylesheet" href="/easyui/easyui.css">
 @endsection

 @section('content')
     <div class="row">
         {{-- tampil absen --}}
         <div class="col-12">
             <div class="card">
                 <div id="loader_data_absensi" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Data Absensi</h3>
                 </div>

                 <div class="card-body">
                     <div id="tampil_data_absensi"></div>
                 </div>

             </div>
         </div>
     </div>

     <div class="row">
         {{-- Kolom Kiri: Aktifitas --}}
         <div class="col-12 col-lg-5 mb-3">
             <div class="card">
                 <div id="loader_data_aktivitas" class="overlay">
                     <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                 </div>

                 <div class="card-header">
                     <h3 class="card-title">Aktifitas Terakhir</h3>
                 </div>

                 <div class="card-body">
                     @if ($ada_struktur == 'Y')
                         <div class="row">
                             {{-- filter pegawai --}}
                             <div class="col-12 mb-2">
                                 <select id="filter_pegawai" class="form-control select2 w-100">
                                 </select>
                             </div>
                         </div>
                     @endif
                     <div id="tampil_data_aktivitas"></div>
                 </div>
             </div>

             <div class="card">
                 <div id="loader_data_agenda" class="overlay">
                     <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                 </div>

                 <div class="card-header">
                     <h3 class="card-title">Agenda</h3>
                 </div>

                 <div class="card-body">
                     <div id="tampil_data_agenda"></div>
                 </div>
             </div>
         </div>

         {{-- Kolom Kanan: Chart --}}
         <div class="col-12 col-lg-7 mb-3">
             <div class="card">
                 <div id="loader_data_chart_jml_aktivitas" class="overlay">
                     <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                 </div>

                 <div class="card-header">
                     <h3 class="card-title">Chart Aktifitas Terakhir</h3>
                 </div>

                 <div class="card-body">
                     <div class="row">
                         {{-- filter tahun --}}
                         <div class="col-12 col-sm-6 col-md-3 mb-2">
                             <select id="filter_tahun" class="form-control select2 w-100">
                                 <option selected="selected" value="">-Tahun-</option>
                                 <?php
                                 $tahun = date('Y');
                                 $start = $tahun - 4;
                                 for ($i = 1; $i <= 5; $i++) {
                                     $selected = $tahun == $start ? 'selected="selected"' : '';
                                     echo "<option $selected value='$start'>$start</option>";
                                     $start++;
                                 }
                                 ?>
                             </select>
                         </div>

                         {{-- filter ada_tidak_program_kerja --}}
                         <div class="col-12 col-sm-6 col-md-3 mb-2">
                             <select id="filter_ada_tidak_program_kerja" class="form-control select2 w-100">
                                 <option value="">-Ada / Tidak Program-</option>
                                 <option value="Y">Ada</option>
                                 <option value="T">Tidak</option>
                             </select>
                         </div>

                         @if (Auth::guard('admin')->check())
                             {{-- filter bidang --}}
                             <div class="col-12 col-sm-6 col-md-3 mb-2">
                                 <select id="filter_bidang" class="form-control select2 w-100">
                                 </select>
                             </div>
                         @endif

                         {{-- filter jenis kegiatan --}}
                         <div class="col-12 col-sm-6 col-md-3 mb-2">
                             <select id="filter_jenis_kegiatan" class="form-control select2 w-100">
                             </select>
                         </div>

                         {{-- filter status pencapaian (jika diperlukan, aktifkan) --}}
                         {{-- 
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <select id="filter_status_pencapaian" class="form-control select2 w-100">
                        </select>
                    </div>
                    --}}
                     </div>

                     <div class="row mt-3">
                         <div class="col-12">
                             <div id="tampil_data_chart_jml_aktivitas"></div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 @endsection

 @section('script')
     <script src="/easyui/jquery.easyui.min.js"></script>
     <script src="/easyui/datagrid-scrollview.js"></script>
     <script src="/js/apexcharts.js"></script>

     <script>
         $(function() {

             tampilDataAbsensi();
             tampilDataAktivitas('');
             tampilDataAgenda();
             tampilDataChartJmlAktivitas('', '', '', '', '')

             // tampil_data_absensi
             function tampilDataAbsensi(filter_pegawai) {
                 $('#loader_data_absensi').show();

                 $.post('{{ URL::to('dashboard-tampil-data-absensi') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#tampil_data_absensi").html(e);
                 }).done(function(data) {
                     $('#loader_data_absensi').hide();
                 })
             }

             // tampil_data_aktivitas
             function tampilDataAktivitas(filter_pegawai) {

                 $('#loader_data_aktivitas').show();

                 $.post('{{ URL::to('dashboard-tampil-data-aktivitas') }}', {
                     filter_pegawai,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#tampil_data_aktivitas").html(e);
                 }).done(function(data) {
                     $('#loader_data_aktivitas').hide();
                 });
             }

             // tampil_data_agenda
             function tampilDataAgenda() {
                 $('#loader_data_agenda').show();

                 $.post('{{ URL::to('dashboard-tampil-data-agenda') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#tampil_data_agenda").html(e);
                 }).done(function(data) {
                     $('#loader_data_agenda').hide();
                 })
             }

             // tampil_data_chart_jml_aktivitas
             function tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                 filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang) {
                 $('#loader_data_chart_jml_aktivitas').show();

                 $.post('{{ URL::to('dashboard-tampil-data-chart-jml-aktivitas') }}', {
                     filter_tahun,
                     filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan,
                     filter_status_pencapaian,
                     filter_bidang,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $("#tampil_data_chart_jml_aktivitas").html(e);
                 }).done(function(data) {
                     $('#loader_data_chart_jml_aktivitas').hide();
                 });
             }

             //  -----------------

             loadFilterDataPegawaiBySo();

             function loadFilterDataPegawaiBySo() {
                 $('.loader').show()
                 $('#filter_pegawai').empty()

                 $.post('{{ URL::to('select-data-pegawai-by-so') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {

                     $("#filter_pegawai").select2({
                         data: e,
                         theme: 'bootstrap4'
                     })

                     $('.loader').hide()
                     $("#filter_pegawai").val('').trigger("change");
                 });

                 $("#filter_pegawai").append("<option value='' selected>-Pegawai-</option>");

             }

             // on change filter_pegawai
             $('#filter_pegawai').on("change", function(e) {

                 var filter_pegawai = $("#filter_pegawai").val();

                 tampilDataAktivitas(filter_pegawai);
             });

             //  --------------

             loadFilterJenisKegiatan();
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

             // on change filter_tahun
             $('#filter_tahun').on("change", function(e) {

                 var filter_tahun = $("#filter_tahun").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();
                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();

                 tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang);

             });

             // on change filter_ada_tidak_program_kerja
             $('#filter_ada_tidak_program_kerja').on("change", function(e) {

                 var filter_tahun = $("#filter_tahun").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();
                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();

                 tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang);
             });

             $('#filter_jenis_kegiatan').on("change", function(e) {

                 var filter_tahun = $("#filter_tahun").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();
                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();

                 tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang);
             });

             // on change filter_status_pencapaian
             $('#filter_status_pencapaian').on("change", function(e) {

                 var filter_tahun = $("#filter_tahun").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();
                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();

                 tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang);

             });

             // on change filter_bidang
             $('#filter_bidang').on("change", function(e) {

                 var filter_tahun = $("#filter_tahun").val();
                 var filter_ada_tidak_program_kerja = $("#filter_ada_tidak_program_kerja").val();
                 var filter_jenis_kegiatan = $("#filter_jenis_kegiatan").val();
                 var filter_status_pencapaian = $("#filter_status_pencapaian").val();
                 var filter_bidang = $("#filter_bidang").val();

                 tampilDataChartJmlAktivitas(filter_tahun, filter_ada_tidak_program_kerja,
                     filter_jenis_kegiatan, filter_status_pencapaian, filter_bidang);

             });


         });
     </script>
 @endsection
