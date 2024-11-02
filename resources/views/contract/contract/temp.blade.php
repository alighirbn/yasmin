<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('contract.nav.navigation')
            @include('service.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        @can('map-empty')
                            <a href="{{ route('map.empty') }}" class="btn btn-custom-transfer">
                                {{ __('word.map_empty_buildings') }}
                            </a>
                        @endcan

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>

                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto  bg-white">

                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($contract->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract->building->building_number, 'C39') }}"
                                    alt="barcode" />

                                <p><strong>{{ __('العدد :') }}</strong>
                                    {{ $contract->id }}
                                </p>
                                <p><strong>{{ __('التاريخ :') }}</strong> {{ $contract->contract_date }}</p>

                            </div>
                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>حجز اولي</p>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                <p id="customer_full_name" class="outlined-text" type="text"
                                    name="customer_full_name">
                                    {{ $contract->customer->customer_full_name }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_number">
                                    {{ $contract->customer->customer_card_number }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                <p id="customer_phone" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_phone">
                                    {{ $contract->customer->customer_phone }}
                                </p>
                            </div>

                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_category_id">
                                    {{ $contract->building->building_category->category_name }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                <p id="building_number" class="w-full h-9 block mt-1 " type="text"
                                    name="building_number">
                                    {{ $contract->building->building_number }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1" type="text" name="block_number">
                                    {{ $contract->building->block_number }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text" name="house_number">
                                    {{ $contract->building->house_number }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1 " type="text"
                                    name="building_area">
                                    {{ $contract->building->building_area }}
                                </p>
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                <p id="contract_note" class="w-full h-9 block mt-1" type="text" name="contract_note">
                                    {{ $contract->contract_note }}
                                </p>
                            </div>

                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 0.8rem; font-weight: bold;">
                            <p>يلغى الحجز في حالة عدم دفع مبلغ الدفعة الأولى خلال موعد اقصاه 24 ساعة من تأريخ الحجز .
                            </p>
                        </div>
                    </div>
                    <div class="flex">
                        @if (isset($contract->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $contract->user_create->name }}
                                {{ $contract->created_at }}
                            </div>
                        @endif

                        @if (isset($contract->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $contract->user_update->name }}
                                {{ $contract->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
