 @extends('layouts.main')

 @section('css')
     <link href="/datatable/responsive.dataTables.min.css" rel="stylesheet">
     <link href="/datatable/fixedColumns.dataTables.min.css" rel="stylesheet">

     <link href="/date_agenda/main.min.css" rel="stylesheet">
 @endsection

 @section('content')

     @if ($tahunAjar)
         @if ($mappingAgenda)
             <div class="row">
                 <div class="col-lg-12">
                     <div class="card">
                         <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

                         <div class="card-body">
                             <div id="calendar"></div>
                         </div>

                     </div>
                 </div>
             </div>

             <!-- Modal -->
             <div class="modal fade" id="agendaModal" tabindex="-1" role="dialog">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="modalTitle">Tambah Agenda</h5>
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                         </div>
                         <div class="modal-body">
                             <form id="agendaForm">

                                 <input type="hidden" name="id" id="agenda_id">

                                 <div class="form-group">
                                     <label>Jenis</label>
                                     <input value="{{ $mappingAgenda->jenis }}" name="jenis" id="jenis"
                                         class="form-control" readonly>
                                 </div>

                                 <div class="form-group">
                                     <label>Tanggal Awal</label>
                                     <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" required
                                         readonly>
                                 </div>

                                 <div class="form-group">
                                     <label>Tanggal Akhir</label>
                                     <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control">
                                 </div>

                                 <div class="form-group">
                                     <label>Keterangan</label>
                                     <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                                 </div>

                             </form>
                         </div>
                         <div class="modal-footer">
                             <button type="button" id="deleteAgenda" class="btn btn-danger"
                                 style="display:none;">Hapus</button>
                             <button type="button" id="saveAgenda" class="btn btn-primary">Simpan</button>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Modal List Agenda -->
             <div class="modal fade" id="listAgendaModal" tabindex="-1" role="dialog">
                 <div class="modal-dialog modal-lg" role="document">
                     <div class="modal-content">

                         <div class="modal-header">
                             <h5 class="modal-title" id="modalTitle">List Agenda</h5>
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                         </div>

                         <div class="modal-body">
                             <table id="tabel_list_agenda" class="table table-bordered table-striped">
                                 <thead>
                                     <tr>
                                         <th>No</th>
                                         <th>Jenis</th>
                                         <th>Bidang Pembuat</th>
                                         <th>Tgl Awal</th>
                                         <th>Tgl Akhir</th>
                                     </tr>
                                 </thead>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         @else
             <div class="alert alert-danger">
                 User tidak memiliki akses!
             </div>
         @endif
     @else
         <div class="alert alert-danger">
             Saat ini <strong>tidak berada</strong> dalam rentang tahun ajaran manapun.
         </div>
     @endif
 @endsection

 @if ($tahunAjar)
     @if ($mappingAgenda)
         @section('script')
             <script src="/datatable/dataTables.responsive.min.js"></script>
             <script src="/datatable/dataTables.fixedColumns.min.js"></script>

             <script src="/date_agenda/main.min.js"></script>

             <script>
                 document.addEventListener('DOMContentLoaded', function() {

                     var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                         initialView: 'dayGridMonth',
                         locale: 'id', // Bahasa Indonesia
                         customButtons: {
                             myCustomButton: {
                                 text: 'List Agenda',
                                 click: function() {
                                     $('#listAgendaModal').modal('show');
                                     loadTabeListAgenda();
                                 }
                             }
                         },
                         headerToolbar: {
                             left: 'title',
                             right: 'prev,next today myCustomButton',
                             //  right: 'dayGridMonth,timeGridWeek,timeGridDay'
                         },
                         events: '/agenda-events',
                         loading: function(isLoading) {
                             if (!isLoading) {
                                 $('.loader').hide(); // sembunyikan loader kalau data sudah selesai di-load
                             }
                         },
                         @if (session('yt_add') == 'Y')
                             dateClick: function(info) {
                                 $('#agendaForm')[0].reset();
                                 $('#agenda_id').val('');
                                 $('#tgl_awal').val(info.dateStr);
                                 $('#tgl_akhir').val(info.dateStr);
                                 $('#modalTitle').text('Tambah Agenda');
                                 $('#deleteAgenda').hide();
                                 $('#agendaModal').modal('show');
                             },
                         @endif

                         @if (session('yt_edit') == 'Y')
                             eventClick: function(info) {
                                 $.get('/agenda/' + info.event.id, function(data) {
                                     $('#agenda_id').val(data.id);
                                     $('#tgl_awal').val(data.tgl_awal);
                                     $('#tgl_akhir').val(data.tgl_akhir);
                                     $('#keterangan').val(data.keterangan);
                                     $('#jenis').val(data.jenis);
                                     $('#modalTitle').text('Edit Agenda');
                                     $('#deleteAgenda').show();
                                     $('#agendaModal').modal('show');
                                 });
                             }
                         @endif

                     });

                     calendar.render();

                     $('#saveAgenda').click(function() {
                         var id = $('#agenda_id').val();
                         var url = id ? '/agenda/update/' + id : '/agenda/store';

                         $.ajax({
                             url: url,
                             method: 'POST',
                             data: $('#agendaForm').serialize(),
                             headers: {
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                             },
                             success: function(res) {
                                 if (res.success) {
                                     $('#agendaModal').modal('hide');
                                     calendar.refetchEvents();
                                 } else {
                                     tampilPesan('warning',
                                         ' Gagal, sudah ada agendauntuk semua bidang di tgl tersebut!'
                                     );
                                 }
                             }
                         });
                     });

                     $('#deleteAgenda').click(function() {
                         var id = $('#agenda_id').val();

                         @if (session('yt_del') == 'Y')
                             if (confirm('Yakin ingin menghapus agenda ini?')) {
                                 $.ajax({
                                     url: '/agenda/delete/' + id,
                                     method: 'post',
                                     headers: {
                                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                     },
                                     success: function(res) {
                                         if (res.success) {
                                             $('#agendaModal').modal('hide');
                                             calendar.refetchEvents();
                                         }
                                     }
                                 });
                             }
                         @else
                             alert('user tidak memiliki akses untuk hapus..');
                         @endif

                     });

                     // load tabel list agenda
                     function loadTabeListAgenda() {
                         $('.loader').show();

                         $('#tabel_list_agenda').DataTable().destroy();

                         $.post('{{ URL::to('agenda/list-agenda') }}', {
                             _token: '{{ csrf_token() }}'
                         }, function(e) {
                             var tabel = $("#tabel_list_agenda").DataTable({
                                 @if (session('yt_print') == 'Y')
                                     "buttons": ["excel", "pdf", "print"],
                                 @endif
                                 "autoWidth": false,
                                 "searching": true,
                                 "paging": false,
                                 "fixedColumns": true,
                                 "scrollX": true,
                                 "data": e,
                                 "columns": [
                                    {
                                         data: 'id',
                                         render: function(data, type, row, meta) {
                                             return meta.row + 1;
                                         },
                                         className: "text-center",
                                     },
                                      {
                                         data: 'jenis',
                                         className: "text-left",
                                     },
                                     {
                                         data: 'nama_bidang',
                                         className: "text-left",
                                     },
                                     {
                                         data: 'tgl_awal',
                                         className: "text-left",
                                     },
                                     {
                                         data: 'tgl_akhir',
                                         className: "text-left",
                                     },

                                 ]
                             }).buttons().container().appendTo('#tabel_list_agenda_wrapper .col-md-6:eq(0)');

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

                 });
             </script>
         @endsection
     @endif
 @endif
