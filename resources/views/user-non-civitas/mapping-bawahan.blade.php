<div class="row">

    <div class="col-8">

        <select id="user_level" class="form-control select2" style="width: 100%;">
        </select>

    </div>

    <div class="col-4">
        <button type="button" id="save_form_bawahan" class="btn btn-block bg-gradient-success btn-sm">Add</button>
    </div>

</div>

<br>

<input type="hidden" class="form-control" value="{{ $id }}" id="id_user_level">

<table id="table_maping" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
</table>


<script>
    $(function() {

        loadData();
        loadUserLevel();

        function loadUserLevel() {

            $('#user_level').empty()

            $.post('{{ URL::to('select-user-level') }}', {
                _token: '{{ csrf_token() }}'
            }, function(e) {

                $("#user_level").select2({
                    data: e,
                    theme: 'bootstrap4'
                })

                $("#user_level").val('').trigger("change");
            });

            $("#user_level").append("<option value='' selected>-Nama-</option>");

        }

        // load tabel user wifi
        function loadData() {
            var id_user_level = $("#id_user_level").val();

            $('.loader').show();

            $('#table_maping').DataTable().destroy();

            $.post('{{ URL::to('tabel-mapping-bawahan') }}', {
                id_user_level,
                _token: '{{ csrf_token() }}'
            }, function(e) {

                var tabel = $("#table_maping").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "searching": false,
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
                            data: 'bawahan',
                            className: "text-left",
                        },
                        {
                            data: 'aksi',
                            className: "text-left",
                        },

                    ]
                }).buttons().container().appendTo('#table_maping_wrapper .col-md-6:eq(0)');

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

        //  save add
        $(document).on("click", "#save_form_bawahan", function(e) {
            
            e.preventDefault(); // cegah reload form default

            var id_user_level = $("#id_user_level").val();
            var id_user_level_bawahan = $("#user_level").val();

            if (id_user_level == "") {  
                tampilPesan('warning', ' Atasan tidak boleh kosong!');
            } else if(id_user_level_bawahan == ""){
                tampilPesan('warning', ' Bawahan tidak boleh kosong!');
            } else {

                $.ajax({
                    url: "/save-data-bawahan",
                    cache: false,
                    type: 'post',
                    data: {
                        id_user_level,
                        id_user_level_bawahan,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {

                        var sukses = result.sukses;
                        var status = result.status;
                        var message = result.message;

                        if (sukses == 'Y') {
                            loadUserLevel();

                            loadData()

                            tampilPesan(status, message);
                        } else {
                            tampilPesan(status, message);
                        }

                    },
                    fail: function(xhr, textStatus, errorThrown) {
                        tampilPesan('error', 'request failed');
                    }
                });
            
            }

        });

        $(document).on("click", ".hapus_data_bawahan", function(e) {
            var id = $(this).data("id");

            Swal.fire({
                title: 'Hapus Data?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.value) {
                    $('.loader').show();
                    $.ajax({
                        url: 'hapus-data-bawahan',
                        cache: false,
                        type: 'post',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(result) {

                            if (result.sukses == "Y") {
                                loadData();
                                tampilPesan(result.status, result.message);
                            } else {
                                tampilPesan(result.status, result.message);
                            }
                        },
                        fail: function(xhr, textStatus, errorThrown) {
                            tampilPesan('error', 'request failed');
                        }
                    })
                }
            })
        });

    });
</script>
