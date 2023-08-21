<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_name') | @yield('page_title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,500,700&display=swap" rel="stylesheet">
    <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/custom-style.css')}}" rel="stylesheet">
</head>
    <body class="@yield('body_class')">
        <div class="container-fluid p-0">
            <div class="row align-items-center no-gutters">
                <div class="col-lg-4 col-12 d-flex auth-container bg-white shadow">
                    <div class="card my-auto w-100 px-3 px-md-5 py-3 border-0 rounded-0">
                        <div class="text-center">
                            <div class="logo-container mx-auto text-danger">
                                <i class="fab fa-pied-piper fa-4x"></i>
                            </div>
                            <h2 class="h2 text-bold text-danger my-3">
                                <strong>{{ config('app.name') }}</strong>
                            </h2>
                        </div>
                        <h5 class="font-weight-bold text-danger">@yield('page_name')</h5>
                        <!-- Content -->
                        @yield('content')
                        <!-- Content -->
                        <div class="text-center mt-3">
                            <span class="small">Copyright© {{ config('app.name') }} <?php echo date('Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- JavaScript -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        @yield('page_script')
        <!-- JavaScript-->
    </body>
</html>

