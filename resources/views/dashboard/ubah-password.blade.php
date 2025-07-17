 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-4">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h5 class="m-0">Action</h5>
                 </div>
                 <div class="card-body">

                     <div class="card-body pl-0 pt-0">
                         <input type="hidden" name="id_master" class="form-control" id="id_master">

                         {{-- pass_lama --}}
                         <div class="form-group">
                             <label for="pass_lama">Password Lama</label>

                             <input type="password" name="pass_lama" class="form-control" id="pass_lama"
                                 placeholder="Input Password Lama">
                         </div>

                         {{-- pass_baru --}}
                         <div class="form-group">
                             <label for="pass_baru">Password Baru</label>

                             <input type="password" name="pass_baru" class="form-control" id="pass_baru"
                                 placeholder="Input Password Baru">
                         </div>

                         {{-- ulangi_pass_baru --}}
                         <div class="form-group">
                             <label for="ulangi_pass_baru">Ulangi Password Baru</label>

                             <input type="password" name="ulangi_pass_baru" class="form-control" id="ulangi_pass_baru"
                                 placeholder="Input Ulangi Password Baru">
                         </div>
                     </div>

                     <div class="card-footer">
                         <button id="reset_form" class="btn btn-warning"><i class="fas fa-refresh"></i> Reset</button>
                         <button id="save_form" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                     </div>


                 </div>
             </div>
         </div>

     </div>
 @endsection

 @section('script')
     <script>
         $(function() {
            $('.loader').hide();

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 var pass_lama          = $("#pass_lama").val();
                 var pass_baru          = $("#pass_baru").val();
                 var ulangi_pass_baru   = $("#ulangi_pass_baru").val();

                 if (pass_lama == "") {
                     tampilPesan('warning', ' Password Lama tidak boleh kosong!');
                 } else if (pass_baru == "") {
                     tampilPesan('warning', ' Password Baru tidak boleh kosong!');
                 } else if (ulangi_pass_baru == "") {
                     tampilPesan('warning', ' Ulangi Password Baru tidak boleh kosong!');
                 } else if (pass_baru.length <= 6) {
                     tampilPesan('warning', ' Gagal, Panjang karakter harus >= 6 karakter');
                 } else if (pass_baru != ulangi_pass_baru) {
                     tampilPesan('warning', ' Gagal, Password Baru tidak sama dengan Ulangi Password Baru!');
                 } else {
                     $.ajax({
                         url: "/simpan-ubah-password",
                         cache: false,
                         type: 'post',
                         data: {
                             pass_lama,
                             pass_baru,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             var sukses = result.sukses;
                             var pesan = result.pesan;

                             if (sukses == "Y") {
                                  $('.loader').hide();
                                    tampilPesan('success', pesan);
                                    resetForm();
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

             // reset_form
             $(document).on("click", "#reset_form", function() {
                 resetForm();
             });

             function resetForm() {
                 $("#pass_lama").val('');
                 $("#pass_baru").val('');
                 $("#ulangi_pass_baru").val('');
             }

         });
     </script>
 @endsection
