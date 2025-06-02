 @extends('layouts.main')

 @section('css')
     <link rel="stylesheet" href="/easyui/easyui.css">
 @endsection

 @section('content')
     <div class="row">
         <div class="col-md-3">
             <div id="tampil_1"></div>
         </div>
     </div>
 @endsection

 @section('script')
     <script src="/easyui/jquery.easyui.min.js"></script>
     <script src="/easyui/datagrid-scrollview.js"></script>
     <script src="/js/apexcharts.js"></script>

     <script>
         $(function() {
            tampil_1();
         });

         function tampil_1(){
             $.post('{{ URL::to('tampil-halaman-chart-satu') }}', {
                 _token: '{{ csrf_token() }}'
             }, function(e) {
                 $("#tampil_1").html(e);
             });
         }

         /*
         //  combobox load bulan
         function loadSelecBulan() {
             $.post('{{ URL::to('select-bulan-trans-operasional') }}', {
                 _token: '{{ csrf_token() }}'
             }, function(e) {
                 $('#bulan').combobox({
                     value: "",
                     data: e
                 });
             });
         }

         
         function selectBulan() {
             var bulan = $('#bulan').combobox('getText');

             loadGrafikJmlBarangOperasional(bulan);
         }

         function loadGrafikJmlBarangOperasional(bulan_trx) {

             $.post('{{ URL::to('load-grafik-jml-barang-operasional') }}', {
                 bulan_trx,
                 _token: '{{ csrf_token() }}'
             }, function(e) {
                 var options = {

                     series: [{
                         data: e
                     }],

                     chart: {
                         height: 350,
                         type: 'treemap',
                         toolbar: {
                             show: false
                         }
                     },
                 };

                 var operasional = new ApexCharts(document.querySelector("#operasional"), options);

                 operasional.render();
             });
         }
         */
     </script>
 @endsection
