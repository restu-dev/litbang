 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">
 @endsection

 @section('content')
     <style>
         .modal-open {
             overflow: hidden;
         }

         input.largerCheckbox {
             width: 28px;
             height: 28px;
         }

         th,
         td {
             white-space: nowrap;
         }

         div.dataTables_wrapper {
             width: 100%;
             margin: 0 auto;
         }
     </style>

     @include('fpb.partials.modal_add_fpb')

     <div id="tampil_content">
         <div class="row">

             <div class="col-lg-12">
                 <div class="card">
                     <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                     <div class="card-header ui-sortable-handle">
                         {{-- <h5> <b>No Surat</b> : {{ $no_surat }}</h5> --}}
                         <h3 class="card-title">
                             <i class="fas fa-file mr-1"></i>
                             <b>No Surat</b> : {{ $no_surat }}
                         </h3>

                         <div class="card-tools">
                             <ul class="nav nav-pills ml-auto">
                                 <li class="nav-item bg-danger">
                                     <a class="nav-link" href="/master-fpb">Close</a>
                                 </li>
                             </ul>
                         </div>
                     </div>

                     <div class="card-body card-body">
                         <iframe width="100%" height="400px" src="/view-cetak-fpb?data={{ $no_surat }}"></iframe>
                     </div>

                 </div>
             </div>

         </div>
     </div>
 @endsection

 @section('script')
     <script src="/datatable/dataTables.responsive.min.js"></script>
     <script src="/datatable/dataTables.fixedColumns.min.js"></script>

     <script>
         //Date range picker
         $('#tgl_fpb').daterangepicker();

         $(function() {

             loadTabeFpb('');

             $('#modal-add-fpb').on('hidden.bs.modal', function() {
                 loadTabeFpb('');
             })

             $("#tgl_fpb").on('change', function(e) {
                 var tgl_fpb = $("#tgl_fpb").val();

                 loadTabeFpb(tgl_fpb);
             })

             // load tabel gudang
             function loadTabeFpb(tgl_fpb) {
                 $('.loader').show();

                 $('#table_fpb').DataTable().destroy();

                 $.post('{{ URL::to('load-tabel-fpb') }}', {
                     tgl_fpb,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     var tabel = $("#table_fpb").DataTable({
                         "bDestroy": true,
                         "buttons": ["copy", "excel", "pdf", "print"],
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
                         //  "fixedColumns": {
                         //      left: 3,
                         //  },
                         "data": e,
                         "columns": [{
                                 data: 'id',
                                 render: function(data, type, row, meta) {
                                     return meta.row + 1;
                                 },
                                 width: 5,
                                 className: "text-center",
                             },
                             {
                                 data: 'aksi',
                                 width: 5,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'no_surat',
                                 width: 20,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'nama_unit',
                                 width: 10,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'nama_pegawai',
                                 width: 20,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'status',
                                 width: 8,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'tgl_fpb',
                                 width: 10,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'tgl_penggunaan',
                                 width: 10,
                                 className: "align-middle text-left"
                             },
                             {
                                 data: 'jml_barang',
                                 width: 8,
                                 className: "align-middle text-center"
                             },

                         ]
                     }).buttons().container().appendTo('#table_fpb_wrapper .col-md-6:eq(0)');

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

             // cetak_fpb
             $(document).on("click", ".cetak_fpb", function(e) {
                 $("#modal_cetak").modal("show");

                 var id = $(this).data('id');
                 var surat = $(this).data('surat');
                 $("#judul_surat").html(surat);

                 $('#view_cetak').attr('src', 'view-cetak-fpb/' + id);

             });

             //  detail_fpb
             $(document).on("click", ".detail_fpb", function(e) {
                 var id_master_fpb = $(this).data('id');

                 $.post('{{ URL::to('tampil-input-detail-fpb') }}', {
                     id_master_fpb,
                     _token: '{{ csrf_token() }}'
                 }, function(e) {
                     $('#tampil_content').html(e);
                 });
             });

             //  hapus_master
             $(document).on("click", ".hapus_master", function(e) {
                 var id = $(this).data('id');

                 Swal.fire({
                     title: 'Apa kamu yakin?',
                     text: "Anda tidak akan dapat mengembalikan ini!",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Ya, hapus!'
                 }).then((result) => {
                     if (result.value) {
                         $('.loader').show();

                         $.ajax({
                             url: 'hapus-master-fpb',
                             cache: false,
                             type: 'post',
                             data: {
                                 id: id,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(e) {
                                 var sukses = e.sukses;
                                 var pesan = e.pesan;

                                 if (sukses == "Y") {
                                     loadTabeFpb('');
                                     $('.loader').hide();
                                     tampilPesan('success', pesan);
                                 } else {
                                     $('.loader').hide();
                                     tampilPesan('error', pesan);
                                 }

                             },
                             fail: function(xhr, textStatus, errorThrown) {
                                 $('.loader').hide();
                                 tampilPesan('error', 'request failed');
                             }
                         })
                     }
                 })
             });
         });
     </script>
 @endsection
