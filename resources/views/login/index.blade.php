<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | LITBANG PIA</title>
    <link rel="icon" type="image/png" href="{{ asset('admin_template/favicon.png') }}" />


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/admin_template/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/admin_template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin_template/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        @if (session()->has('loginError'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> {{ session('loginError') }}</h5>
            </div>
        @endif

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">


                <div class="login-logo mb-0">
                    <img class="text-center mb-2" src="/img/logo-pia.png" alt="" width="105" height="105">
                </div>

                <h4 class="text-center mb-0"><b>LITBANG</b></h4>
                <p class="text-center">Login Form</p>


                <form action="/login" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input name="user" type="text" class="form-control @error('user') is-invalid @enderror"
                            placeholder="NIP / User">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>

                        @error('user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input name="password" type="password"
                            class="form-control  @error('password') is-invalid @enderror" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="form-group clearfix p-2">
                            <div class="icheck-primary d-inline p-1">
                                <input type="radio" id="radioPrimary1" value="Y" name="yt_civitas"
                                    checked="">
                                <label for="radioPrimary1">
                                    Civitas
                                </label>
                            </div>

                            <div class="icheck-primary d-inline p-1">
                                <input type="radio" id="radioPrimary2" value="T" name="yt_civitas">
                                <label for="radioPrimary2">
                                    Non Civitas
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="input-group mb-3">
                            <div class="col-5">
                                <span class="capchaa pr-2">{!! captcha_img() !!}</span>
                            </div>

                            <div class="col-2">
                                <button type="button" class="btn btn-danger" class="reload" id="reload">
                                    &#x21bb;
                                </button>
                            </div>
                        </div>


                        <div class="col-12">
                            <input id="captcha" type="text"
                                class="form-control @error('captcha') is-invalid @enderror" placeholder="Input Captcha"
                                name="captcha">

                            @error('captcha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>

                </form>

              <p class="text-center mt-3 mb-3 text-body-secondary">Login mengunakan user & password ESA <br>~ Pesantren
                Islam Al-Irsyad ~</p>

            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="/admin_template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/admin_template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/admin_template/dist/js/adminlte.min.js"></script>

    <script type="text/javascript">
        $(document).on("click", "#reload", function(e) {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function(data) {
                    $(".capchaa").html(data.captcha);
                }
            });
        });
    </script>
</body>

</html>
