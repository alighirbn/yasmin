<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
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

                    <div>
                        <form method="post" action="{{ route('contract.update', $contract->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $contract->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $contract->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.contract_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_customer_id" class="w-full mb-1"
                                        :value="__('word.contract_customer_id')" />
                                    <select id="contract_customer_id"
                                        class="js-example-basic-single w-full block mt-1 "
                                        name="contract_customer_id">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ (old('contract_customer_id') ?? $contract->contract_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_building_id" class="w-full mb-1"
                                        :value="__('word.contract_building_id')" />
                                    <select id="contract_building_id"
                                        class="js-example-basic-single w-full block mt-1 "
                                        name="contract_building_id">
                                        @foreach ($buildings as $building)
                                            <option value="{{ $building->id }}"
                                                data-price="{{ $building->calculatePrice() }}"
                                                {{ (old('contract_building_id') ?? $contract->contract_building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_building_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_payment_method_id" class="w-full mb-1"
                                        :value="__('word.contract_payment_method_id')" />
                                    <select id="contract_payment_method_id"
                                        class="js-example-basic-single w-full block mt-1 "
                                        name="contract_payment_method_id">
                                        @foreach ($payment_methods as $payment_method)
                                            <option value="{{ $payment_method->id }}"
                                                {{ (old('contract_payment_method_id') ?? $contract->contract_payment_method_id) == $payment_method->id ? 'selected' : '' }}>
                                                {{ $payment_method->method_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_payment_method_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                    <x-text-input id="contract_date" class="w-full block mt-1" type="text"
                                        name="contract_date"
                                        value="{{ old('contract_date') ?? $contract->contract_date }}" />
                                    <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_amount_display" class="w-full mb-1"
                                        :value="__('word.contract_amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="contract_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format(old('contract_amount') ?? $contract->contract_amount, 0) }}"
                                        placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="contract_amount" name="contract_amount"
                                        value="{{ old('contract_amount') ?? $contract->contract_amount }}">

                                    <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                    <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                        name="contract_note"
                                        value="{{ old('contract_note') ?? $contract->contract_note }}" />
                                    <x-input-error :messages="$errors->get('contract_note')" class="w-full mt-2" />
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('customerModal');
            var closeModal = document.getElementById('closeModal');
            var form = document.getElementById('customerForm');
            var submitButton = document.getElementById('submitButton');
            var customerSelect = document.getElementById('contract_customer_id');

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
        document.addEventListener("DOMContentLoaded", function() {
            var displayInput = document.getElementById('contract_amount_display');
            var hiddenInput = document.getElementById('contract_amount');

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
            // Function to calculate contract amount
            function calculateContractAmount() {
                var selectedOption = $('#contract_building_id').find('option:selected');
                var contractAmount = parseFloat(selectedOption.data('price')) || 0;

                $('#contract_amount').val(contractAmount);
                $('#contract_amount_display').val(numberWithCommas(contractAmount));
            }

            // Call the function on page load
            calculateContractAmount();

            // Event listener for change on building selection
            $('#contract_building_id').change(function() {
                calculateContractAmount();
            });

            // Function to format number with commas
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

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
