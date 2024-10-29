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
                mix-blend-mode: multiply;
            }

            .visible {
                background-color: rgb(108, 220, 254);
                /* Green */
            }

            .hidden {
                background-color: red;
                /* Red */
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
                        <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image" id="mapImage">

                        @foreach ($buildings as $building)
                            <div class="overlay-div"
                                style="top: {{ $building->building_map_y }}%; left: {{ $building->building_map_x }}%;"
                                data-tooltip="{{ $building->building_number }}" data-id="{{ $building->id }}">
                                <a href="javascript:void(0)"
                                    class="fill-div {{ $building->hidden ? 'hidden' : 'visible' }}"
                                    onclick="toggleVisibility(event, {{ $building->id }})"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(event, buildingId) {
            const target = event.currentTarget;
            target.classList.toggle('hidden');
            target.classList.toggle('visible');

            // Optionally send an AJAX request to update the visibility in the database
            fetch(`/building/${buildingId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        /* any necessary data */
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        console.error('Failed to update visibility');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</x-app-layout>
