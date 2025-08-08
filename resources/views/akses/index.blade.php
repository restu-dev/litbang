 @extends('layouts.main')

 @section('content')
    <div class="row justify-content-center">
        <div class="col-lg-3 col-md-5 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Level</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($level as $d)
                            <div class="col-12 mb-2">
                                <button data-id="{{ $d->id }}" data-name="{{ $d->name }}" type="button"
                                    class="level_akses btn btn-block btn-outline-primary w-100 text-start">
                                    <i class="bi bi-person-badge me-2"></i>{{ $d->name }}
                                </button>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Tidak ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-7">
            <div id="tampil_akses" class="card shadow-sm border-0 p-3 min-vh-50 bg-light"></div>
        </div>
    </div>

    <style>
        @media (max-width: 991.98px) {
            .col-lg-3, .col-lg-9 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            #tampil_akses {
                min-height: 200px;
            }
        }
        .level_akses {
            transition: box-shadow 0.2s;
        }
        .level_akses:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            background: #0d6efd;
            color: #fff;
        }
    </style>
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
