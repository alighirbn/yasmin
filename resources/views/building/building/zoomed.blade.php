<x-app-layout>
    <x-slot name="header">
        <style>
            .image-container {
                position: relative;
                width: 100%;
                height: auto;
                overflow: hidden;
                /* Set a fixed height to the container */
                height: 1000px;
                border: 1px solid #ccc;
            }

            .zoomed-image {
                width: 100%;
                height: auto;
                /* Adjust zoom level by increasing the width to create a zoom effect */
                position: absolute;
            }

            /* Highlight the building position */
            .highlight {
                position: absolute;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: rgba(0, 195, 255, 0.8);
                border: 2px solid red;
            }
        </style>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="image-container">
                        <!-- Image of the background zoomed in -->
                        <img src="{{ asset('images/background.jpg') }}" alt="Zoomed Image" class="zoomed-image">

                        <!-- Highlight the specific building -->
                        <div class="highlight" data-tooltip="Building {{ $building->building_number }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
