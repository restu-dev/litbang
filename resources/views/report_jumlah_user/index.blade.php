 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-12">
             <div class="card">
                 <div class="card-header">
                     <h3 class="card-title">Filter</h3>
                 </div>

                 <div class="card-body">
                     <div class="row">
                         <div class="col-2">
                             <div class="form-group">
                                 <label>Tahun</label>
                                 <select class="form-control" style="width: 100%;" id="tahun"
                                     onchange="loadTampilData()">
                                     <option selected="selected" value="">Pilih Tahun</option>
                                     <?php
                                     $tahun = date('Y');
                                     $start = $tahun - 4;
                                     $i = 1;
                                     $year = date('Y');
                                     
                                     for ($i = 1; $i <= 5; $i++) {
                                         if ($year == $start) {
                                             echo "<option selected='selected' value='$start'>$start</option>";
                                         } else {
                                             echo "<option value='$start'>$start</option>";
                                         }
                                     
                                         $start++;
                                     }
                                     
                                     ?>
                                 </select>
                             </div>
                         </div>

                         <div class="col-2">
                             <div class="form-group">
                                 <label>Bulan</label>
                                 <select class="form-control" style="width: 100%;" id="bulan"
                                     onchange="loadTampilData()">
                                     <?php
                                     $today = date('d M Y');
                                     $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                     
                                     $month = date('n');
                                     for ($j = 1; $j <= 12; $j++) {
                                         if ($month == $j) {
                                             echo "<option selected='selected' value='$j'>$bulan[$j]</option>";
                                         } else {
                                             echo "<option value='$j'>$bulan[$j]</option>";
                                         }
                                     }
                                     ?>
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

                 <div class="card-body" id="tampil_data">

                 </div>
             </div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>
         loadTampilData();

         function loadTampilData() {
             var tahun = $("#tahun").val();
             var bulan = $("#bulan").val();

             $('.loader').show();

             $.post('{{ URL::to('load-tampil-data-report-jumlah-user') }}', {
                tahun,
                bulan,
                 _token: '{{ csrf_token() }}'
             }, function(e) {
                 $("#tampil_data").html(e);
             }).done(function(data) {
                 $('.loader').hide();
             });
         }
     </script>
 @endsection
