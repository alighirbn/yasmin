<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
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
                background-color: rgb(0, 0, 0);
                mix-blend-mode: multiply;
            }

            /* Print specific styles */
            @media print {

                /* Set landscape orientation */
                @page {
                    size: landscape;
                    margin: 0;
                }

                /* Hide the print button on print */
                .print-btn {
                    display: none;
                }
            }
        </style>
        <!-- app css-->

        <div class="flex justify-start">
            @include('contract.nav.navigation')
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Header Buttons -->
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <button onclick="window.print()" class="btn btn-custom-print">
                            طباعة
                        </button>

                    </div>

                    <div class="flex flex-wrap print-container">
                        <!-- Contract Information -->
                        <div class="w-full">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h2 class="text-2xl font-bold mb-4">خارطة مجمع واحة الياسمين السكني - موقع العقار</h2>
                                <div class="flex">
                                    <div class=" w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ __('word.customer_full_name') }}:</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $contract->customer->customer_full_name }}</p>
                                    </div>
                                    <div class=" w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ __('word.contract_amount') }}:</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ number_format($contract->contract_amount, 0) }} دينار</p>
                                    </div>
                                    <div class=" w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ __('word.block_number') }}:</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $contract->building->block_number }}
                                        </p>
                                    </div>
                                    <div class=" w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ __('word.house_number') }}:</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $contract->building->house_number }}
                                        </p>
                                    </div>
                                    <div class=" w-full">
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ __('word.building_area') }}:</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $contract->building->building_area }}
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Map Image -->
                        <div class="w-full">
                            <div class="image-container ">
                                <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image"
                                    id="mapImage">

                                <div class="overlay-div"
                                    style="top: {{ $contract->building->building_map_y }}%; left: {{ $contract->building->building_map_x }}%;">
                                    <a class="fill-div"></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
