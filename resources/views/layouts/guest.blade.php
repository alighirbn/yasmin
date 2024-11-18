<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            .gradient-background {
                background: linear-gradient(to bottom, #5e4215, #5e4215);
                /* Adjust colors as needed  #2b6a9a, #0c6e70 */
            }

            .gradient-background-nav {
                background: linear-gradient(to bottom, #9f7126, #a3762e);
                /* Adjust colors as needed   #2b6a9a, #d0e3ff*/
            }

            .bg-custom {
                background-color: #fff6ef;
                /* Replace with your desired color */
                color: #fff1f1;
                /* Text color */
            }
        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="gradient-background font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 ">
            <div>
                <a href="/">
                    <div class="w-full h-auto p-2 flex justify-center " style="height:34dvh;">
                        <img src="{{ URL('images/logo.png') }}">
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>

</html>
