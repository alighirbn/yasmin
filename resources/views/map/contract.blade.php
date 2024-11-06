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

            .fill-div.temporary {
                background-color: rgb(255, 125, 125);
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

            .stat-container {
                position: absolute;
                top: 10px;
                /* Adjust as needed */
                left: 10px;
                /* Adjust as needed */
                background-color: rgba(255, 255, 255, 0.8);
                /* Change to a bright color */
                padding: 5px;
                /* Reduced padding for a smaller size */
                border-radius: 5px;
                /* Rounded corners */
                z-index: 10;
                /* Ensure it appears above the image */
                font-size: 0.9em;
                /* Smaller font size */
                color: rgb(0, 13, 126);
                /* Text color for better contrast */
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
                        <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image">

                        <div class="stat-container">
                            <p>المباني الممتلئة : {{ $contractCount }} ({{ number_format($percentageContracts, 2) }}%)
                            </p>
                            <p>المباني الشاغرة: {{ $buildingsWithoutContracts }}</p>
                        </div>

                        @foreach ($contracts as $contract)
                            <div class="overlay-div"
                                style="top: {{ $contract->building->building_map_y }}%; left: {{ $contract->building->building_map_x }}%;"
                                data-tooltip="الاسم: {{ $contract->customer->customer_full_name }} | رقم العقار: {{ $contract->building->building_number }}">
                                <a href="{{ route('contract.show', $contract->url_address) }}"
                                    class="fill-div {{ $contract->stage === 'temporary' ? 'temporary' : '' }}"></a>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
