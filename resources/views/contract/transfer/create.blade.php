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
                            <button id="openModal" class="btn btn-primary">
                                {{ __('word.customer_add') }}
                            </button>
                        @endcan

                    </div>
                    <!-- Modal Structure -->
                    <div id="customerModal"
                        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center p-10">
                        <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full p-10">
                            <button id="closeModal" class="text-gray-800 hover:text-gray-900">
                                &times;
                            </button>
                            <form id="customerForm" method="post" action="{{ route('contract.customerstore') }}">
                                @csrf
                                <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 w-full">
                                    {{ __('word.customer_info') }}
                                </h1>

                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                        <x-text-input id="customer_full_name" class="w-full block mt-1" type="text"
                                            name="customer_full_name" value="{{ old('customer_full_name') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="mother_full_name" class="w-full mb-1" :value="__('word.mother_full_name')" />
                                        <x-text-input id="mother_full_name" class="w-full block mt-1" type="text"
                                            name="mother_full_name" value="{{ old('mother_full_name') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                        <x-text-input id="customer_phone" class="w-full block mt-1" type="text"
                                            name="customer_phone" value="{{ old('customer_phone') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_email" class="w-full mb-1" :value="__('word.customer_email')" />
                                        <x-text-input id="customer_email" class="w-full block mt-1" type="text"
                                            name="customer_email" value="{{ old('customer_email') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>

                                <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4 w-full">
                                    {{ __('word.customer_card') }}
                                </h2>

                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_number" class="w-full mb-1"
                                            :value="__('word.customer_card_number')" />
                                        <x-text-input id="customer_card_number" class="w-full block mt-1" type="text"
                                            name="customer_card_number" value="{{ old('customer_card_number') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_issud_auth" class="w-full mb-1"
                                            :value="__('word.customer_card_issud_auth')" />
                                        <x-text-input id="customer_card_issud_auth" class="w-full block mt-1"
                                            type="text" name="customer_card_issud_auth"
                                            value="{{ old('customer_card_issud_auth') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_issud_date" class="w-full mb-1"
                                            :value="__('word.customer_card_issud_date')" />
                                        <x-text-input id="customer_card_issud_date" class="w-full block mt-1"
                                            type="text" name="customer_card_issud_date"
                                            value="{{ old('customer_card_issud_date') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>
                                <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4 w-full">
                                    {{ __('word.customer_address') }}
                                </h2>
                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="full_address" class="w-full mb-1" :value="__('word.full_address')" />
                                        <x-text-input id="full_address" class="w-full block mt-1" type="text"
                                            name="full_address" value="{{ old('full_address') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="address_card_number" class="w-full mb-1"
                                            :value="__('word.address_card_number')" />
                                        <x-text-input id="address_card_number" class="w-full block mt-1"
                                            type="text" name="address_card_number"
                                            value="{{ old('address_card_number') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="saleman" class="w-full mb-1" :value="__('word.saleman')" />
                                        <x-text-input id="saleman" class="w-full block mt-1" type="text"
                                            name="saleman" value="{{ old('saleman') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-primary-button id="submitButton" class="ml-4">
                                        {{ __('word.save') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
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
                                    <x-text-input id="transfer_amount_display" class="w-full block mt-1"
                                        type="text" value="{{ number_format(old('transfer_amount', 0), 0) }}"
                                        placeholder="0" />

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
                                            <input type="hidden" id="old_customer_picture"
                                                name="old_customer_picture">
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
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('customerModal');
            var closeModal = document.getElementById('closeModal');
            var form = document.getElementById('customerForm');
            var submitButton = document.getElementById('submitButton');
            var customerSelect = document.getElementById('new_customer_id');

            // Open the modal
            document.getElementById('openModal').addEventListener('click', function() {
                modal.classList.remove('hidden');
            });

            // Close the modal
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Handle form submission
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // alert(data.message); // Inform the user

                            // Add the new customer to the select dropdown
                            var newOption = document.createElement('option');
                            newOption.value = data.customer.id;
                            newOption.text = data.customer.customer_full_name;
                            customerSelect.add(newOption);

                            // Optionally set the new customer as selected
                            customerSelect.value = data.customer.id;

                            modal.classList.add('hidden'); // Close the modal
                        } else {
                            // Clear previous errors
                            document.querySelectorAll('.input-error').forEach(element => {
                                element.innerHTML = '';
                            });

                            // Display validation errors
                            for (const [field, errors] of Object.entries(data.errors)) {
                                const errorElement = document.querySelector(`#${field} ~ .input-error`);
                                if (errorElement) {
                                    errorElement.innerHTML = errors.join('<br>');
                                }
                            }

                            // Re-enable the submit button and reset its text
                            submitButton.textContent = '{{ __('word.save') }}';
                            submitButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Prevent double submission
            form.addEventListener('submit', function() {
                submitButton.textContent = 'جاري الحفظ';
                submitButton.disabled = true;
            });
        });
    </script>

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
