<x-app-layout>
    <x-slot name="header">
        <!-- CSS Styling -->
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

            .overlay-div a.fill-div {
                display: block;
                height: 100%;
                width: 100%;
                text-decoration: none;
                border-radius: 100%;
                text-align: center;
                line-height: 1%;
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
                padding: 5px;
                border-radius: 5px;
                white-space: nowrap;
                z-index: 10;
            }

            #classification-selector {
                margin-bottom: 20px;
                padding: 8px;
                font-size: 16px;
                border-radius: 5px;
            }
        </style>

        <div class="flex justify-start">
            @include('map.nav.navigation')
        </div>
    </x-slot>

    <!-- Classification Selector -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b  text-gray-900 border-gray-200">
                    <label for="classification-selector" class="block text-gray-700 text-sm font-bold mb-2">
                        المزايا:
                    </label>
                    <select id="classification-selector"
                        class="border-gray-300  text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach ($classifications as $classification)
                            <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Buildings -->
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="image-container">
                        <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image"
                            id="mapImage">

                        <!-- Building Overlays -->
                        <!-- Building Overlays -->
                        @foreach ($buildings as $building)
                            @php
                                // Determine the building's classification color
$color = $classificationColors[$building->classification_id] ?? 'rgba(0, 0, 0, 0.5)'; // Default color if undefined
                            @endphp
                            <div class="overlay-div" id="building-{{ $building->id }}"
                                style="top: {{ $building->building_map_y }}%; left: {{ $building->building_map_x }}%; background-color: {{ $color }};"
                                data-tooltip="Building {{ $building->building_number }}">
                                <a href="#" class="fill-div" data-building-id="{{ $building->id }}"></a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classificationSelector = document.getElementById('classification-selector');
            const buildingOverlays = document.querySelectorAll('.overlay-div .fill-div');

            buildingOverlays.forEach(overlay => {
                overlay.addEventListener('click', function(event) {
                    event.preventDefault();

                    const buildingId = this.getAttribute('data-building-id');
                    const selectedClassificationId = classificationSelector.value;

                    fetch("{{ route('building.ajax-update-classification') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                building_id: buildingId,
                                classification_id: selectedClassificationId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                // alert(data.message);

                                // Update tooltip to show success
                                const tooltip = document.querySelector(
                                    `#building-${data.building_id}`);
                                tooltip.setAttribute('data-tooltip',
                                    `Building: ${data.building_id}, Updated!`);

                                // Update the overlay color dynamically
                                const newColor = data
                                    .color; // Assuming the server returns the new color
                                tooltip.style.backgroundColor = newColor;
                            } else {
                                alert("Failed to update classification.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("An error occurred while updating the classification.");
                        });
                });
            });
        });
    </script>

</x-app-layout>
