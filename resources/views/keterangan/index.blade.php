 @extends('layouts.main')

 @section('content')
     <div class="row">

         <div class="col-lg-8">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h3 class="card-title">Tabel {{ $title }}</h3>
                 </div>
                
                 <div class="card-body">
                     <table id="tabel_keterangan" class="table table-bordered table-striped">
                         <thead>
                             <tr>
                                 <th>No</th>
                                 <th>Ketrangan</th>
                                 <th>Aksi</th>
                             </tr>
                         </thead>
                     </table>
                 </div>
                
             </div>
         </div>

         <div class="col-lg-4">
             <div class="card">
                 <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                 <div class="card-header">
                     <h5 class="m-0">Action</h5>
                 </div>
                 <div class="card-body">

                     <div class="card-body pl-0 pt-0">
                         <input type="hidden" name="id_keterangan" class="form-control" id="id_keterangan">

                         <div class="form-group">
                             <label>Keterangan</label>
                             <textarea id="keterangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
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

             loadTabelKeterangan();

             // load tabel unit
             function loadTabelKeterangan() {
                 $('.loader').show();

                 $('#tabel_keterangan').DataTable().destroy();

                 $.post('{{ URL::to('load-table-keterangan') }}', {
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#tabel_keterangan").DataTable({
                         "responsive": true,
                         "lengthChange": false,
                         "autoWidth": false,
                         "buttons": ["copy", "csv", "excel", "pdf", "print"],
                         "data": e,
                         "columns": [{
                                 data: 'id',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 className: "text-center",
                             },
                             {
                                 data: 'keterangan',
                                 className: "text-left",
                             },
                             {
                                 data: null,
                                 "render": function(data, type, row) {
                                     return '<div class="btn-group">' +
                                         '<button data-id="' + row.id + '" data-keterangan="' + row.keterangan + '" data-toggle="tooltip" data-placement="top" title="Edit" type="button" class="btn btn-info btn-sm edit_keterangan"><i class="fas fa-edit"></i></button>' +
                                         '<button data-id="' + row.id +
                                         '" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm hapus_keterangan"><i class="fa fa-trash"></i></button>' +
                                         '</div>'
                                 },
                             },
                         ]
                     }).buttons().container().appendTo('#tabel_keterangan_wrapper .col-md-6:eq(0)');

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

             $(document).on("click", ".edit_keterangan", function(e) {
                 var id_keterangan = $(this).data("id");
                 var keterangan = $(this).data("keterangan");

                 $("#id_keterangan").val(id_keterangan);
                 $("#keterangan").val(keterangan);
             });

             //  save_form
             $(document).on("click", "#save_form", function(e) {
                 var id_keterangan = $("#id_keterangan").val();
                 var keterangan = $("#keterangan").val();

                 if (keterangan == "") {
                     tampilPesan('warning', ' Keterangan tidak boleh kosong!');
                 } else {
                     $.ajax({
                         url: "/store-keterangan",
                         cache: false,
                         type: 'post',
                         data: {
                             id_keterangan,
                             keterangan,
                             _token: '{{ csrf_token() }}'
                         },
                         success: function(result) {
                             console.log(result);
                             loadTabelKeterangan();
                             $('.loader').hide();
                             tampilPesan(result.status, result.message);
                             resetForm();
                         },
                         fail: function(xhr, textStatus, errorThrown) {
                             $('.loader').hide();
                             tampilPesan('error', 'request failed');
                         }
                     });
                 }

             });

             //  hapus_keteranan
             $(document).on("click", ".hapus_keterangan", function(e) {
                 var id_keterangan = $(this).data('id');

                 Swal.fire({
                     title: 'Are you sure?',
                     text: "You won't be able to revert this!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Yes, delete it!'
                 }).then((result) => {
                      if (result.value) {
                          $('.loader').show();

                          $.ajax({
                              url: '/destroy-keterangan',
                              cache: false,
                              type: 'post',
                              data: {
                                  id: id_keterangan,
                                  _token: '{{ csrf_token() }}'
                              },
                              success: function(result) {
                                  console.log(result);
                                  loadTabelKeterangan();
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

             // reset_form
             $(document).on("click", "#reset_form", function() {
                 resetForm();
             });

             function resetForm() {
                 $("#id_keterangan").val('');
                 $("#keterangan").val('');
             }
         });
     </script>
 @endsection
