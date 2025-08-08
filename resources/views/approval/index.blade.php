 @extends('layouts.main')

 @section('content')
     @if ($tahunAjar)
         @if ($ada_struktur == 'T')
             @if (session('nama_level')=='Ketua Harian' || session('nama_level')=='Kepala Biro')
                @include('approval.partials.content')
             @else
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Peringatan !</h5>
                    User tidak memiliki struktur dibawahnya..
                </div>
             @endif

         @else
            @include('approval.partials.content')
         @endif
     @else
         <div class="alert alert-danger">
             Saat ini <strong>tidak berada</strong> dalam rentang tahun ajaran manapun.
         </div>
     @endif
 @endsection

 @if ($tahunAjar)
     @section('script')
         <script>
             $(function() {

                 var id_tahun_ajaran = {{ $id_tahun_ajaran }};
                 var id_nama_tahun_ajarantahun_ajaran = {{ $nama_tahun_ajaran }};

                 loadFilterTahunPelajaran();
                 loadFilterStatusCapaian();

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
                         $("#filter_tahun_pelajaran").val('').trigger("change");
                     });

                     $("#filter_tahun_pelajaran").append("<option value='' selected>-Tahun Pelajaran-</option>");

                 }

                 // on change filter_tahun_pelajaran
                 $('#filter_tahun_pelajaran').on("change", function(e) {

                     var filter_status_capaian = $("#filter_status_capaian").val();
                     var filter_approvement = $("#filter_approvement").val();
                     var filter_tahun = $("#filter_tahun_pelajaran").val();

                     loadTabelData(filter_status_capaian, filter_approvement, filter_tahun);
                 });


                 // on change filter_status_capaian
                 $('#filter_status_capaian').on("change", function(e) {

                     var filter_status_capaian = $("#filter_status_capaian").val();
                     var filter_approvement = $("#filter_approvement").val();
                     var filter_tahun = $("#filter_tahun_pelajaran").val();

                     loadTabelData(filter_status_capaian, filter_approvement, filter_tahun);

                 });

                 // on change filter_approvement
                 $('#filter_approvement').on("change", function(e) {

                     var filter_status_capaian = $("#filter_status_capaian").val();
                     var filter_approvement = $("#filter_approvement").val();
                     var filter_tahun = $("#filter_tahun_pelajaran").val();

                     loadTabelData(filter_status_capaian, filter_approvement, filter_tahun);

                 });

                 loadSelectTahunPelajaran();
                 loadSelectStatusCapaian();
                 loadTabelData('', '', '');

                 function loadSelectStatusCapaian() {
                     $('.loader').show()
                     $('#status_capaian').empty()

                     $.post('{{ URL::to('select-status-capaian') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#status_capaian").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#status_capaian").val('').trigger("change");
                     });
                 }

                 function loadSelectTahunPelajaran() {
                     $('.loader').show()
                     $('#tahun_pelajaran').empty()

                     $.post('{{ URL::to('select-tahun-pelajaran') }}', {
                         _token: '{{ csrf_token() }}'
                     }, function(e) {

                         $("#tahun_pelajaran").select2({
                             data: e,
                             theme: 'bootstrap4'
                         })

                         $('.loader').hide()
                         $("#tahun_pelajaran").val('').trigger("change");
                     });
                 }

                 // load tabel unit
                 function loadTabelData(filter_status_capaian, filter_approvement, filter_tahun) {
                     $('.loader').show();

                     $('#tabel_master').DataTable().destroy();

                     $.post('{{ URL::to('load-tabel-approval') }}', {
                         filter_status_capaian,
                         filter_approvement,
                         filter_tahun,
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
                                     data: 'aksi',
                                     className: "text-left",
                                 },
                                 {
                                     data: 'approvement',
                                     className: "text-left",
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
                         resetForm();
                     });
                 }

                 // add modal_add_edit
                 $(document).on("click", ".add_edit_data", function(e) {
                     e.stopPropagation();
                     e.stopImmediatePropagation();
                     e.preventDefault();

                     var btn = $(this).data("btn");

                     if (btn == "edit") {
                         resetForm();

                         $(".modal-title").html("Data Approval");

                         var id = $(this).data("id");

                         // get data edit by id
                         $.post('{{ URL::to('get-data-edit-program-kerja-tahunan-by-id') }}', {
                             id,
                             _token: '{{ csrf_token() }}'
                         }, function(e) {
                             $("#id").val(id);
                             $("#tahun_pelajaran").val(e.id_tahun_pelajaran).trigger("change");
                             $("#tahun_pelajaran").prop('disabled', true);

                             $("#status_capaian").val(e.id_status_capaian).trigger("change");
                             $("#status_capaian").prop('disabled', true);

                             $("#penanggung_jawab").val(e.nama_penanggung_jawab);
                             $("#program_kerja").val(e.program_kerja);
                             $("#target_frekuensi_tahunan").val(e.target_frekuensi_tahunan);
                             $("#indikator_kinerja").val(e.indikator_kinerja);
                             $("#keterangan").val(e.indikator_kinerja);

                             $("#approvement").val(e.approvement).trigger("change");
                         });
                     }

                     $("#modal_add_edit").modal("show");
                 });

                 //  save_form
                 $(document).on("click", "#save_form", function(e) {
                     e.stopPropagation();
                     e.stopImmediatePropagation();
                     e.preventDefault();

                     var id = $("#id").val();

                     var approvement = $("#approvement").val();

                     if (approvement == "") {
                         tampilPesan('warning', ' Approvement tidak boleh kosong!');
                     } else {
                         $.ajax({
                             url: "simpan-approval",
                             cache: false,
                             type: 'post',
                             data: {
                                 id,
                                 approvement,
                                 _token: '{{ csrf_token() }}'
                             },
                             success: function(result) {
                                 var sukses = result.sukses;
                                 var pesan = result.pesan;

                                 if (sukses == "Y") {
                                     var filter_status_capaian = $("#filter_status_capaian").val();
                                     var filter_approvement = $("#filter_approvement").val();
                                     var filter_tahun = $("#filter_tahun_pelajaran").val();

                                     loadTabelData(filter_status_capaian, filter_approvement,
                                         filter_tahun);

                                     $('.loader').hide();
                                     tampilPesan('success', result.pesan);

                                     resetForm();

                                     $("#modal_add_edit").modal("hide");

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

                 function resetForm() {
                     $("#id").val('');
                     $("#tahun_pelajaran").val('').trigger("change");
                     $("#penanggung_jawab").val('');
                     $("#program_kerja").val('');
                     $("#target_frekuensi_tahunan").val('');
                     $("#indikator_kinerja").val('');
                     $("#status_capaian").val('').trigger("change");
                     $("#keterangan").val('');
                     $("#approvement").val('');
                 }

             });
         </script>
     @endsection
 @endif
