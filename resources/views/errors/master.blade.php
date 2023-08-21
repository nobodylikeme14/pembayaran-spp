<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_name') | @yield('page_title', config('app.name'))</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,500,700&display=swap" rel="stylesheet">
    <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/custom-style.css')}}" rel="stylesheet">
</head>
<body>
    <div class="container auth-container d-flex">
        <div class="m-auto">
            <div class="row row-cols-1 row-cols-md-2 text-gray-800 py-5">
                <div class="col d-flex order-2 order-md-1">
                    <div class="my-auto">
                        <h1 class="text-danger">@yield('page_name')</h1>
                        @yield('error-text')
                    </div>
                </div>
                <div class="col order-1 order-md-2 text-danger mb-0">
                    <div class="h2 d-flex justify-content-start justify-content-md-center mb-2">
                        <i class="fab fa-pied-piper my-auto mr-2"></i>
                        <span class="my-auto font-weight-bold">{{config('app.name')}}</span>
                    </div>
                    <h1 class="display-1 d-flex justify-content-start justify-content-md-center mb-0">
                        <span>@yield('error-code')</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</body>
</html>