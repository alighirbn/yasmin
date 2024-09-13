<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')

    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $service->contract->url_address) }}"
                                class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($service->contract->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($service->id, 'C39') }}"
                                    alt="barcode" />

                                <p><strong>{{ __('عدد الخدمة:') }}</strong>
                                    {{ $service->id }}
                                </p>
                                <p><strong>{{ __('تاريخ الخدمة:') }}</strong> {{ $service->service_date }}</p>

                            </div>
                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.service_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="service_id" class="w-full mb-1" :value="__('word.service_id')" />
                                <p id="service_id" class="w-full h-9 block mt-1" type="text" name="service_id">
                                    {{ $service->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="service_date" class="w-full mb-1" :value="__('word.service_date')" />
                                <p id="service_date" class="w-full h-9 block mt-1 " type="text" name="service_date">
                                    {{ $service->service_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="service_type_id" class="w-full mb-1" :value="__('word.service_type_id')" />
                                <p id="service_type_id" class="outlined-text" type="text" name="service_type_id">
                                    {{ $service->service_type->type_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="service_amount" class="w-full mb-1" :value="__('word.service_amount')" />
                                <p id="service_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="service_amount">
                                    {{ number_format($service->service_amount, 0) }} دينار
                            </div>

                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.customer_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                <p id="contract_id" class="w-full h-9 block mt-1" type="text" name="contract_id">
                                    {{ $service->contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $service->contract->contract_date }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                <p id="customer_full_name" class="outlined-text" type="text"
                                    name="customer_full_name">
                                    {{ $service->contract->customer->customer_full_name }}
                            </div>

                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.building_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_category_id">
                                    {{ $service->contract->building->building_category->category_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                <p id="building_number" class="w-full h-9 block mt-1 " type="text"
                                    name="building_number">
                                    {{ $service->contract->building->building_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1" type="text" name="block_number">
                                    {{ $service->contract->building->block_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text"
                                    name="house_number">
                                    {{ $service->contract->building->house_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1 " type="text"
                                    name="building_area">
                                    {{ $service->contract->building->building_area }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="service_note" class="w-full mb-1" :value="__('word.service_note')" />
                                <p id="service_note" class="w-full h-9 block mt-1" type="text"
                                    name="service_note">
                                    {{ $service->service_note }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($service->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $service->user_create->name }}
                                    {{ $service->created_at }}
                                </div>
                            @endif

                            @if (isset($service->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $service->user_update->name }}
                                    {{ $service->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
