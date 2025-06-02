 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-2">
             <div class="card">
                 <div class="card-header">
                     <h5 class="m-0">Level</h5>
                 </div>
                 <div class="card-body">
                     <div class="card-body p-0 m-0">
                         @forelse ($level as $d)
                             <button data-id="{{ $d->id }}" data-name="{{ $d->name }}" type="button"
                                 class="level_akses btn btn-block btn-primary">{{ $d->name }}</button>
                         @empty
                             <p>Tidak ada data</p>
                         @endforelse


                     </div>
                 </div>
             </div>
         </div>

         <div class="col-lg-10">
             <div id="tampil_akses"></div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>
         $(function() {

             //  level_akses
             $(document).on("click", ".level_akses", function(e) {
                 var id_level = $(this).data("id");
                 var name_level = $(this).data("name");

                 $.ajax({
                     url: "/admin/tampil-level-akses",
                     cache: false,
                     type: 'post',
                     data: {
                         id_level,
                         name_level,
                         _token: '{{ csrf_token() }}'
                     },
                     success: function(result) {
                         $("#tampil_akses").html(result);
                     },
                     fail: function(xhr, textStatus, errorThrown) {
                         tampilPesan('error', 'request failed');
                     }
                 });

             });

             // simpan akses menu
            $(document).on("click", "#simpanaksesmenu", function(e) {
    
                var data = $("#form_akses").serialize();

                $.ajax({
                    url: '/admin/simpan-akses-menu',
                    cache: false,
                    type: 'post',
                    data: {
                        data: data,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(e) {
                        tampilPesan('success', 'succes');
                       
                    }
                })

            });


         });
     </script>
 @endsection
