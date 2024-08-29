<!DOCTYPE html>
@if (LaravelLocalization::getCurrentLocale() == 'ar')
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @else
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'edu') }}</title>

    <!-- app css-->
    <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ url('/css/bootstrap.min.css') }}" />

    <!-- Boxicons CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('/css/boxicons.min.css') }}" />

    <!-- datatable css -->
    <link rel="stylesheet" href="{{ url('/css/dataTables.bootstrap5.min.css') }} ">
    <link rel="stylesheet" href="{{ url('/css/buttons.bootstrap5.min.css') }} ">

    <!-- app css and js-->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap script -->

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Boxicons js -->
    <script src="{{ asset('js/boxicons.js') }}"></script>

    <!--  jquery cdn datatable -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <!--datatable js-->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- buttons datatable js -->
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ url('/') }}/vendor/datatables/buttons.server-side.js"></script>

</head>

<body class="font-sans antialiased">

    <div class="flex" id="wrapper" x-data="{ isOpen: false }">
        <div id="sidebar" class="h-screen  overflow-y-auto gradient-background transition-all  duration-200"
            x-bind:class="isOpen ? 'w-48' : 'w-0'"style="height:100dvh;position: sticky;top:0px;">

            @include('layouts.sidebar')
        </div>

        <div id="body" class="bg-custom w-full h-screen overflow-y-auto  transition-all duration-200 ">
            <div style="position: sticky;top:0px; z-index: 1;">

                @include('layouts.topbar')

            </div>

            <div style="display:flex-col;align-items: start;height:100px;">
                @if (isset($header))
                    <header class="gradient-background-nav shadow ">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

    </div>

    <script>
        function myFunction() {
            var x = document.getElementById("sidebarmenu");
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "block";
            }
        }
    </script>
</body>

</html>
