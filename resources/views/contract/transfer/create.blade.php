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
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('customer-create')
                            <a href="{{ route('transfer.customercreate') }}" class="my-1 mx-1 btn btn-custom-edit">
                                {{ __('word.customer_add') }}
                            </a>
                        @endcan

                    </div>

                    <form action="{{ route('transfer.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="flex ">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                    <select id="contract_id" class="js-example-basic-single w-full block mt-1 "
                                        name="contract_id" data-placeholder="ادخل عدد العقد">
                                        <option value="">

                                        </option>
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ old('contract_id', $contract_id) == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->id . ' ** اسم الزبون  --   ' . $contract->customer->customer_full_name . ' ** رقم العقار --   ' . $contract->building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_id')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">

                                    <x-input-label for="new_customer_id" class="w-full mb-1" :value="__('word.new_customer_id')" />
                                    <select id="new_customer_id" class="js-example-basic-single w-full block mt-1 "
                                        name="new_customer_id" data-placeholder="ادخل اسم الزبون">
                                        <option value="">

                                        </option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('new_customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('new_customer_id')" class="w-full mt-2" />

                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="transfer_date" class="w-full mb-1" :value="__('word.transfer_date')" />
                                    <x-text-input id="transfer_date" class="w-full block mt-1" type="text"
                                        name="transfer_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        placeholder="yyyy-mm-dd" />
                                    <x-input-error :messages="$errors->get('transfer_date')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="transfer_amount_display" class="w-full mb-1"
                                        :value="__('word.transfer_amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="transfer_amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('transfer_amount', 0), 0) }}" placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="transfer_amount" name="transfer_amount"
                                        value="{{ old('transfer_amount') }}">

                                    <x-input-error :messages="$errors->get('transfer_amount')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                    <x-text-input id="transfer_note" class="w-full block mt-1" type="text"
                                        name="transfer_note" value="{{ old('transfer_note') }}" />
                                    <x-input-error :messages="$errors->get('transfer_note')" class="w-full mt-2" />
                                </div>

                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <!-- Existing webcam capture for old customer -->
                                    <div class=" mx-4 my-4 w-full">
                                        <x-input-label for="old_customer_picture" class="w-full mb-1"
                                            :value="__('word.old_customer_picture')" />
                                        <div id="old-customer-webcam-container" class="w-full">
                                            <div id="old-customer-camera"></div>
                                            <button type="button" id="old-customer-capture-button"
                                                class="btn btn-custom-edit mt-2">
                                                {{ __('word.capture') }}
                                            </button>
                                            <input type="hidden" id="old_customer_picture" name="old_customer_picture">
                                        </div>
                                    </div>
                                </div>
                                <div class=" mx-4 my-4 w-full">
                                    <!-- New webcam capture for new customer -->
                                    <div class=" mx-4 my-4 w-full">
                                        <x-input-label for="new_customer_picture" class="w-full mb-1"
                                            :value="__('word.new_customer_picture')" />
                                        <div id="new-customer-webcam-container" class="w-full">
                                            <div id="new-customer-camera"></div>
                                            <button type="button" id="new-customer-capture-button"
                                                class="btn btn-custom-edit mt-2">
                                                {{ __('word.capture') }}
                                            </button>
                                            <input type="hidden" id="new_customer_picture"
                                                name="new_customer_picture">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class=" mx-4 my-4 w-full">
                                <x-primary-button x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                    </form>
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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var displayInput = document.getElementById('transfer_amount_display');
            var hiddenInput = document.getElementById('transfer_amount');

            function formatNumber(value) {
                return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function unformatNumber(value) {
                return value.replace(/,/g, '');
            }

            displayInput.addEventListener('input', function() {
                var formattedValue = formatNumber(displayInput.value);
                displayInput.value = formattedValue;
                hiddenInput.value = unformatNumber(formattedValue);
            });

            // On form submission, make sure the hidden input is set correctly
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput.value = unformatNumber(displayInput.value);
            });
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        // Prevent double submission
        $('form').on('submit', function() {
            // Find the submit button
            var $submitButton = $(this).find('button[type="submit"]');

            // Change the button text to 'Submitting...'
            $submitButton.text('جاري الحفظ');

            // Disable the submit button
            $submitButton.prop('disabled', true);
        });
    </script>
</x-app-layout>
