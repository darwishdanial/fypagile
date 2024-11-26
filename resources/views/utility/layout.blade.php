<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>Laravel 9 Custom Login Registration</title> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('assets/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sidebars.css') }}" rel="stylesheet">
    
    
</head>
<body>
    <div class="">
        @yield('navbar')
    </div>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- <div class="col-2 d-none d-xxl-block px-0"> -->
                <!-- @yield('sidebar') -->
            <!-- </div> -->
            <div class="col-12 px-0">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebars.js') }}"></script>
    @yield('modal')

    @yield('script')
</body>
</html>
