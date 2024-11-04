<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container mx-auto p-4">
                        <h1 class="text-2xl font-bold mb-4">WIA Scanner</h1>

                        <div class="mb-4">
                            <label for="device" class="block text-sm font-medium text-gray-700">
                                Select Scanner
                            </label>
                            <select id="device" name="device_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <button type="button" id="scan-button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Start Scan
                        </button>
                        <div id="scan-result" class="mt-4"></div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const devices = @json($devices); // Pass devices from your backend
                            const deviceSelect = document.getElementById('device');
                            const scanButton = document.getElementById('scan-button');
                            const scanResult = document.getElementById('scan-result');

                            // Populate the device select options
                            devices.forEach(device => {
                                const option = document.createElement('option');
                                option.value = device.id;
                                option.textContent = device.name;
                                deviceSelect.appendChild(option);
                            });

                            // Handle scan button click
                            scanButton.addEventListener('click', function() {
                                const deviceId = deviceSelect.value;

                                fetch('/scan/scan', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content'),
                                        },
                                        body: JSON.stringify({
                                            device_id: deviceId
                                        }),
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Scanning failed. Please try again.');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.image_path) {
                                            scanResult.innerHTML =
                                                `<img src="${data.image_path}" alt="Scanned Image" />`;
                                        }
                                    })
                                    .catch(error => {
                                        alert('Error: ' + error.message);
                                    });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
