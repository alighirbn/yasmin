<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <script src="https://cdn.jsdelivr.net/npm/webcamjs@1.0.25/webcam.min.js"></script>

        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>
                    <div>
                        <form method="post" action="{{ route('transfer.update', $transfer->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $transfer->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $transfer->url_address }}">

                            <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 w-full">
                                {{ __('word.transfer_info') }}
                            </h1>

                            <div class="flex">
                                <!-- Contract Selection -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                    <select id="contract_id" class="js-example-basic-single w-full block mt-1 "
                                        name="contract_id">
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ (old('contract_id') ?? $transfer->contract_id) == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->id . ' ** اسم الزبون  --   ' . $contract->customer->customer_full_name . ' ** رقم العقار --   ' . $contract->building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">
                                <!-- New Customer ID -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="new_customer_id" class="w-full mb-1" :value="__('word.new_customer_id')" />
                                    <select id="new_customer_id" class="js-example-basic-single w-full block mt-1 "
                                        name="new_customer_id" data-placeholder="ادخل اسم الزبون">
                                        <option value=""></option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ (old('new_customer_id') ?? $transfer->new_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('new_customer_id')" class="w-full mt-2" />
                                </div>

                                <!-- Transfer Date -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="transfer_date" class="w-full mb-1" :value="__('word.transfer_date')" />
                                    <x-text-input id="transfer_date" class="w-full block mt-1" type="text"
                                        name="transfer_date"
                                        value="{{ old('transfer_date') ?? $transfer->transfer_date }}" />
                                    <x-input-error :messages="$errors->get('transfer_date')" class="w-full mt-2" />
                                </div>

                                <!-- Transfer Amount -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="transfer_amount_display" class="w-full mb-1"
                                        :value="__('word.transfer_amount')" />
                                    <x-text-input id="transfer_amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('transfer_amount') ?? $transfer->transfer_amount, 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="transfer_amount" name="transfer_amount"
                                        value="{{ old('transfer_amount') ?? $transfer->transfer_amount }}">
                                    <x-input-error :messages="$errors->get('transfer_amount')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex">
                                <!-- Transfer Note -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                    <x-text-input id="transfer_note" class="w-full block mt-1" type="text"
                                        name="transfer_note"
                                        value="{{ old('transfer_note') ?? $transfer->transfer_note }}" />
                                    <x-input-error :messages="$errors->get('transfer_note')" class="w-full mt-2" />
                                </div>
                            </div>

                            <!-- Old Customer Picture -->
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="old_customer_picture" class="w-full mb-1" :value="__('word.old_customer_picture')" />
                                    <div id="old-customer-webcam-container" class="w-full">
                                        <div id="old-customer-camera"></div>
                                        <button type="button" id="old-customer-capture-button"
                                            class="btn btn-custom-edit mt-2">
                                            {{ __('word.capture') }}
                                        </button>
                                        <input type="hidden" id="old_customer_picture" name="old_customer_picture"
                                            value="{{ old('old_customer_picture') ?? $transfer->old_customer_picture }}">
                                        @if ($transfer->old_customer_picture)
                                            <img src="{{ asset($transfer->old_customer_picture) }}" alt="Webcam Image"
                                                class="rounded-md shadow-md">
                                        @endif
                                    </div>
                                </div>

                                <!-- New Customer Picture -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="new_customer_picture" class="w-full mb-1" :value="__('word.new_customer_picture')" />
                                    <div id="new-customer-webcam-container" class="w-full">
                                        <div id="new-customer-camera"></div>
                                        <button type="button" id="new-customer-capture-button"
                                            class="btn btn-custom-edit mt-2">
                                            {{ __('word.capture') }}
                                        </button>
                                        <input type="hidden" id="new_customer_picture" name="new_customer_picture"
                                            value="{{ old('new_customer_picture') ?? $transfer->new_customer_picture }}">
                                        @if ($transfer->new_customer_picture)
                                            <img src="{{ asset($transfer->new_customer_picture) }}"
                                                alt="Webcam Image" class="rounded-md shadow-md">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mx-4 my-4 w-full">
                                <x-primary-button x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize WebcamJS for old customer
        Webcam.set({
            width: 540,
            height: 400,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#old-customer-camera');

        document.getElementById('old-customer-capture-button').addEventListener('click', function() {
            Webcam.snap(function(data_uri) {
                document.getElementById('old_customer_picture').value = data_uri;
                document.getElementById('old-customer-camera').innerHTML = '<img src="' + data_uri + '"/>';
            });
        });

        // Initialize WebcamJS for new customer
        Webcam.set({
            width: 540,
            height: 400,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#new-customer-camera');

        document.getElementById('new-customer-capture-button').addEventListener('click', function() {
            Webcam.snap(function(data_uri) {
                document.getElementById('new_customer_picture').value = data_uri;
                document.getElementById('new-customer-camera').innerHTML = '<img src="' + data_uri + '"/>';
            });
        });

        // Format transfer amount
        document.addEventListener("DOMContentLoaded", function() {
            var displayInput = document.getElementById('transfer_amount_display');
            var hiddenInput = document.getElementById('transfer_amount');

            function formatNumber(value) {
                return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            displayInput.addEventListener('input', function() {
                var formattedValue = formatNumber(displayInput.value);
                displayInput.value = formattedValue;
                hiddenInput.value = formattedValue.replace(/,/g, '');
            });
        });
    </script>

</x-app-layout>
