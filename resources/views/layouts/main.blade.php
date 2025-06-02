<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="icon" type="image/png" href="{{ asset('admin_template/favicon.png') }}" /> --}}

    <title>{{ $title }} | {{ env('APP_NAME') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin_template/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="/admin_template/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin_template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/admin_template/plugins/jqvmap/jqvmap.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/admin_template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/admin_template/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/admin_template/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/admin_template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/admin_template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/admin_template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="/admin_template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="/admin_template/plugins/toastr/toastr.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/admin_template/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/admin_template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin_template/dist/css/adminlte.min.css">

    <style>
        .no-sort::after {
            display: none !important;
        }

        .no-sort {
            pointer-events: none !important;
            cursor: default !important;
        }
    </style>

    @yield('css')

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">

        @include('layouts.partials.preloader')

        @include('layouts.partials.navbar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('layouts.partials.content_header')

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        @include('layouts.partials.footer')


        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <!-- jQuery -->
    <script src="/admin_template/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="/admin_template/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="/admin_template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="/admin_template/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    {{-- <script src="/admin_template/plugins/sparklines/sparkline.js"></script> --}}
    <!-- JQVMap -->
    <script src="/admin_template/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/admin_template/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/admin_template/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="/admin_template/plugins/moment/moment.min.js"></script>
    <script src="/admin_template/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/admin_template/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="/admin_template/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/admin_template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/admin_template/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="/admin_template/dist/js/pages/dashboard.js"></script> --}}

    <!-- DataTables  & Plugins -->
    <script src="/admin_template/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/admin_template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/admin_template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/admin_template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/admin_template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/admin_template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/admin_template/plugins/jszip/jszip.min.js"></script>
    <script src="/admin_template/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/admin_template/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/admin_template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/admin_template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/admin_template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="/admin_template/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="/admin_template/plugins/toastr/toastr.min.js"></script>

    <!-- Select2 -->
    <script src="/admin_template/plugins/select2/js/select2.full.min.js"></script>

    <script src="/admin_template/plugins/inputmask/jquery.inputmask.min.js"></script>

    <!-- AdminLTE App -->
    <script src="/admin_template/dist/js/adminlte.js"></script>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function tampilPesan(icon, pesan) {
            Toast.fire({
                icon: icon,
                title: pesan
            })
        }

        function tampilPesanScan(pesan) {
            toastr.success(pesan);
        }

        //Initialize Select2 Elements
        $('.select2').select2()
    </script>

    @yield('script')

    <script src="/js/app.js"></script>

</body>

</html>
