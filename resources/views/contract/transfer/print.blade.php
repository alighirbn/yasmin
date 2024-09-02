<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto  lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>

                    </div>
                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($transfer->contract->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transfer->id, 'C39') }}"
                                    alt="barcode" />

                                <p><strong>{{ __('رقم التناقل:') }}</strong>
                                    {{ $transfer->id }}
                                </p>
                                <p><strong>{{ __('تاريخ التناقل:') }}</strong> {{ $transfer->transfer_date }}</p>

                            </div>
                        </div>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="id" class="w-full mb-1" :value="__('word.transfer_id')" />
                                <p id="id" class="w-full h-9 block mt-1 " type="text" name="id">
                                    {{ $transfer->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_date" class="w-full mb-1" :value="__('word.transfer_date')" />
                                <p id="transfer_date" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_date">
                                    {{ $transfer->transfer_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                <p id="contract_id" class="w-full h-9 block mt-1 " type="text" name="contract_id">
                                    {{ $transfer->contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $transfer->contract->contract_date }}
                            </div>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="newcustomer" class="w-full mb-1" :value="__('word.newcustomer')" />
                                <p id="newcustomer" class="w-full h-9 block mt-1 " type="text" name="newcustomer">
                                    {{ $transfer->newcustomer->customer_full_name }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_number">
                                    {{ $transfer->newcustomer->customer_card_number }}
                            </div>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="oldcustomer" class="w-full mb-1" :value="__('word.oldcustomer')" />
                                <p id="oldcustomer" class="w-full h-9 block mt-1 " type="text" name="oldcustomer">
                                    {{ $transfer->oldcustomer->customer_full_name }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_number">
                                    {{ $transfer->oldcustomer->customer_card_number }}
                            </div>
                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_amount" class="w-full mb-1" :value="__('word.transfer_amount')" />
                                <p id="transfer_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_amount">
                                    {{ number_format($transfer->transfer_amount, 0) }} دينار
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                <p id="transfer_note" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_note">
                                    {{ $transfer->transfer_note }}
                            </div>
                        </div>

                        <div class="flex ">
                            <div style="text-align: right; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                <p>اسم وتوقيع (المشتري)
                                </p>
                            </div>
                            <div style="text-align: right; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                <p>إسم وتوقيع (البائع)</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div style="text-align: right; margin: 0 auto; font-size: 0.875rem; font-weight: bold;">
                                <p>{{ $transfer->newcustomer->customer_full_name }}</p>
                            </div>

                            <div style="text-align: right; margin: 0 auto; font-size: 0.875rem; font-weight: bold;">
                                <p>{{ $transfer->oldcustomer->customer_full_name }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
