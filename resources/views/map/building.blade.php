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

            /* Optional: Custom tooltip styling */
            .overlay-div:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                top: -35px;
                /* Adjust as needed */
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
        @include('map.nav.navigation')
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
                                <a href="{{ route('building.edit', $building->url_address) }}" class="fill-div"></a>
                            </div>
                        @endforeach
                    </div>

                    <p id="selected-building">No building selected.</p>
                    <p id="coordinates">Click on the image to get new coordinates after selecting a building.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedBuildingId = null;

        // Select overlay div representing a building
        document.querySelectorAll('.overlay-div').forEach(function(div) {
            div.addEventListener('click', function(event) {
                event.preventDefault();
                selectedBuildingId = this.getAttribute('data-id');
                const buildingNumber = this.getAttribute('data-tooltip');
                document.getElementById('selected-building').textContent =
                    `Selected Building: ${buildingNumber} (ID: ${selectedBuildingId})`;
            });
        });

        // Handle image click to get coordinates
        const image = document.getElementById('mapImage');
        const coordinates = document.getElementById('coordinates');

        image.addEventListener('click', function(event) {
            if (!selectedBuildingId) {
                alert("Please select a building first!");
                return;
            }

            const imageWidth = image.clientWidth;
            const imageHeight = image.clientHeight;

            // Get mouse click position relative to the image
            const x = event.offsetX;
            const y = event.offsetY;

            // Convert to percentage
            const xPercent = (x / imageWidth) * 100;
            const yPercent = (y / imageHeight) * 100;

            // Adjust the coordinates to account for the size of the clickable element
            const adjustedXPercent = xPercent - (0.85 / 2); // Adjust for half the width of the circle
            const adjustedYPercent = yPercent - (1.4 / 2); // Adjust for half the height of the circle

            // Ensure the coordinates stay within the bounds of 0-100%
            const finalXPercent = Math.min(Math.max(adjustedXPercent, 0), 100).toFixed(2);
            const finalYPercent = Math.min(Math.max(adjustedYPercent, 0), 100).toFixed(2);

            // Display the adjusted coordinates
            coordinates.textContent = `X: ${finalXPercent}%, Y: ${finalYPercent}%`;

            // Send the coordinates to the server to update the building
            updateBuildingCoordinates(selectedBuildingId, finalXPercent, finalYPercent);
        });


        // Function to update the building's coordinates using AJAX
        function updateBuildingCoordinates(buildingId, xPercent, yPercent) {
            // Get the route for updating building coordinates with the building ID
            let updateUrl = "{{ route('building.updateCoordinates', ':id') }}";
            updateUrl = updateUrl.replace(':id', buildingId); // Replace the placeholder with the actual building ID

            fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        building_map_x: xPercent,
                        building_map_y: yPercent
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        //alert('Building coordinates updated successfully!');
                        // Refresh the page after the update
                        location.reload();
                    } else {
                        alert('Failed to update building coordinates.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</x-app-layout>
