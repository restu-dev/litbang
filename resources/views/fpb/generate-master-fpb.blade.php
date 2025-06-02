 @extends('layouts.main')

 @section('content')
     <div id="tampil_content">
         <div class="row">
             <div class="col-3">
                 <button id="generate_nosurat" style="width: 80% !important;" type="button"
                     class="btn btn-primary btn-block"><i class="fa fa-gear"></i> Generate No Surat</button>
                 <p class="pt-2">Generate <code>no surat</code> untuk dapat melakukan penginputan FPB</p>
             </div>

         </div>
     </div>
 @endsection

 @section('script')
     <script>
         $(function() {

             $(document).on("click", "#generate_nosurat", function(e) {
                 $.post('{{ URL::to('generate-no-surat') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var sukses = e.sukses;
                     var pesan = e.pesan;

                     if (sukses == "Y") {
                         var id_master_fpb = e.id_master_fpb;

                         //  tampil data input fpb
                         tampilDataInputFpb(id_master_fpb);

                     } else {
                         tampilPesan('error', pesan);
                     }
                 });
             });

             function tampilDataInputFpb(id_master_fpb) {
                 $.post('{{ URL::to('tampil-input-detail-fpb') }}', {
                     id_master_fpb,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                    $('#tampil_content').html(e);
                 });
             }

         });
     </script>
 @endsection
