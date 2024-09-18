<x-app-layout>

    <x-slot name="header">
        <style>
            .image-container {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-image {
                width: 100%;
                height: auto;
            }

            .overlay-div {
                position: absolute;
                width: 0.85%;
                height: 1.4%;
            }

            a.fill-div {
                display: block;
                height: 100%;
                width: 100%;
                text-decoration: none;
                border-radius: 100%;
                text-align: center;
                line-height: 1%;
                background-color: rgb(0, 195, 255);
                mix-blend-mode: multiply;
            }

            .overlay-div:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                top: -35px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 3px;
                border-radius: 5px;
                white-space: nowrap;
                z-index: 10;
            }
        </style>
        <div class="flex justify-start">
            @include('map.nav.navigation')
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="image-container">
                        <!-- Map Image -->
                        <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image" id="mapImage">

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
