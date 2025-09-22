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
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
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

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Modal Structure -->
                    <div id="customerModal"
                        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center p-10">
                        <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full p-10">
                            <button id="closeModal" class="text-gray-800 hover:text-gray-900">&times;</button>
                            <form id="customerForm" method="post" action="{{ route('contract.customerstore') }}">
                                @csrf
                                <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 w-full">
                                    {{ __('word.customer_info') }}
                                </h1>
                                <!-- Customer fields -->
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

                    <!-- Contract Form -->
                    <form method="post" action="{{ route('contract.store') }}">
                        @csrf
                        <input type="hidden" id="user_id_create" name="user_id_create"
                            value="{{ auth()->user()->id }}">

                        <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                            {{ __('word.contract_info') }}
                        </h1>

                        <div class="flex">
                            <!-- Customer Select -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                                <select id="contract_customer_id" class="js-example-basic-single w-full block mt-1"
                                    name="contract_customer_id" data-placeholder="ادخل اسم الزبون">
                                    <option value=""></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('contract_customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />
                            </div>

                            <!-- Building Select -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                                <select id="contract_building_id" class="js-example-basic-single w-full block mt-1"
                                    name="contract_building_id" data-placeholder="ادخل رقم العقار">
                                    <option value=""></option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            data-price="{{ $building->calculatePrice() }}"
                                            {{ $building->id == $building_id ? 'selected' : '' }}>
                                            {{ $building->building_number }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_building_id')" class="w-full mt-2" />
                            </div>

                            <!-- Payment Method -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_payment_method_id" class="w-full mb-1"
                                    :value="__('word.contract_payment_method_id')" />
                                <select id="contract_payment_method_id"
                                    class="js-example-basic-single w-full block mt-1"
                                    name="contract_payment_method_id" data-placeholder="ادخل طريقة الدفع">
                                    <option value=""></option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}"
                                            data-is-variable="{{ $payment_method->method_name === 'دفعات متغيرة' ? 'true' : 'false' }}"
                                            {{ old('contract_payment_method_id') == $payment_method->id ? 'selected' : '' }}>
                                            {{ $payment_method->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_payment_method_id')" class="w-full mt-2" />
                            </div>
                        </div>

                        <div class="flex">
                            <!-- Contract Date -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <x-text-input id="contract_date" class="w-full block mt-1" type="text"
                                    name="contract_date"
                                    value="{{ old('contract_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                    placeholder="yyyy-mm-dd" />
                                <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                            </div>

                            <!-- Contract Amount -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_amount_display" class="w-full mb-1" :value="__('word.contract_amount')" />
                                <x-text-input id="contract_amount_display" class="w-full block mt-1" type="text"
                                    value="{{ number_format((float) old('contract_amount', 0), 0) }}"
                                    placeholder="0" />
                                <input type="hidden" id="contract_amount" name="contract_amount"
                                    value="{{ old('contract_amount', 0) }}">
                                <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                            </div>

                            <!-- Discount -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="discount" class="w-full mb-1" :value="__('word.discount')" />
                                <x-text-input id="discount" class="w-full block mt-1" type="number"
                                    name="discount" value="{{ old('discount', 0) }}" step="0.5" min="0"
                                    max="100" placeholder="0.00" />
                                <x-input-error :messages="$errors->get('discount')" class="w-full mt-2" />
                            </div>
                        </div>

                        <!-- Variable Payment Plan -->
                        <div id="variable-payment-fields" class="hidden">
                            <h2 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-4 w-full">
                                {{ __('word.variable_payment_plan') }}
                            </h2>
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="down_payment_amount_display" class="w-full mb-1"
                                        :value="__('word.down_payment_amount')" />
                                    <x-text-input id="down_payment_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('down_payment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="down_payment_amount" name="down_payment_amount"
                                        value="{{ old('down_payment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('down_payment_amount')" class="w-full mt-2" />
                                    <div id="down_payment_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="monthly_installment_amount_display" class="w-full mb-1"
                                        :value="__('word.monthly_installment_amount')" />
                                    <x-text-input id="monthly_installment_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('monthly_installment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="monthly_installment_amount"
                                        name="monthly_installment_amount"
                                        value="{{ old('monthly_installment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('monthly_installment_amount')" class="w-full mt-2" />
                                    <div id="monthly_installment_error" class="text-red-600 text-sm mt-2 hidden">
                                    </div>
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="number_of_months" class="w-full mb-1" :value="__('word.number_of_months')" />
                                    <input id="number_of_months" name="number_of_months" type="range"
                                        min="1" max="120" value="{{ old('number_of_months', 36) }}"
                                        class="w-full" aria-label="Number of months for payment plan"
                                        aria-valuemin="1" aria-valuemax="120"
                                        aria-valuenow="{{ old('number_of_months', 36) }}">
                                    <span id="months-label" class="text-gray-700">36</span>
                                    <x-input-error :messages="$errors->get('number_of_months')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="key_payment_amount_display" class="w-full mb-1"
                                        :value="__('word.key_payment_amount')" />
                                    <x-text-input id="key_payment_amount_display"
                                        class="w-full block mt-1 bg-gray-100" type="text" readonly
                                        value="{{ number_format((float) old('key_payment_amount', 0), 0) }}" />
                                    <input type="hidden" id="key_payment_amount" name="key_payment_amount"
                                        value="{{ old('key_payment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('key_payment_amount')" class="w-full mt-2" />
                                    <div id="key_payment_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                            </div>

                            <!-- Payment Breakdown -->
                            <div class="mx-4 my-4 w-full">
                                <table class="table-auto w-full border text-sm">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="px-2 py-1">{{ __('word.item') }}</th>
                                            <th class="px-2 py-1">{{ __('word.amount') }}</th>
                                            <th class="px-2 py-1">{{ __('word.quantity') }}</th>
                                            <th class="px-2 py-1">{{ __('word.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment-breakdown"></tbody>
                                    <tfoot>
                                        <tr class="font-bold bg-gray-100">
                                            <td colspan="3" class="px-2 py-1 text-right">{{ __('word.total') }}
                                            </td>
                                            <td id="breakdown-total" class="px-2 py-1">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="payment-plan-error" class="text-red-600 text-sm mt-2 hidden"></div>
                            </div>
                        </div>

                        <div class="mx-4 my-4 w-full">
                            <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                            <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                name="contract_note" value="{{ old('contract_note') }}" />
                            <x-input-error :messages="$errors->get('contract_note')" class="w-full mt-2" />
                        </div>

                        <div class="mx-4 my-4 w-full">
                            <x-primary-button id="contract-submit"
                                class="ml-4">{{ __('word.save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for all select fields
            $('.js-example-basic-single').select2();

            // Modal handling
            var modal = document.getElementById('customerModal');
            var closeModal = document.getElementById('closeModal');
            var customerForm = document.getElementById('customerForm');
            var submitButtonModal = document.getElementById('submitButton');
            var customerSelect = document.getElementById('contract_customer_id');

            // Open modal
            document.getElementById('openModal').addEventListener('click', function() {
                modal.classList.remove('hidden');
            });

            // Close modal
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            // Handle customer form submission (AJAX)
            customerForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(customerForm);
                submitButtonModal.textContent = '{{ __('word.saving') }}';
                submitButtonModal.disabled = true;

                fetch(customerForm.action, {
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
                            var newOption = document.createElement('option');
                            newOption.value = data.customer.id;
                            newOption.text = data.customer.customer_full_name;
                            customerSelect.add(newOption);
                            customerSelect.value = data.customer.id;
                            $(customerSelect).trigger('change'); // Sync with Select2
                            modal.classList.add('hidden');
                        } else {
                            document.querySelectorAll('.input-error').forEach(element => {
                                element.innerHTML = '';
                            });
                            for (const [field, errors] of Object.entries(data.errors)) {
                                const errorElement = document.querySelector(`#${field} ~ .input-error`);
                                if (errorElement) {
                                    errorElement.innerHTML = errors.join('<br>');
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        submitButtonModal.textContent = '{{ __('word.save') }}';
                        submitButtonModal.disabled = false;
                    });
            });

            // Contract form elements
            var discountInput = document.getElementById('discount');
            var contractAmountDisplay = document.getElementById('contract_amount_display');
            var contractAmount = document.getElementById('contract_amount');
            var paymentMethodSelect = document.getElementById('contract_payment_method_id');
            var variablePaymentFields = document.getElementById('variable-payment-fields');
            var downPaymentDisplay = document.getElementById('down_payment_amount_display');
            var downPayment = document.getElementById('down_payment_amount');
            var monthlyInstallmentDisplay = document.getElementById('monthly_installment_amount_display');
            var monthlyInstallment = document.getElementById('monthly_installment_amount');
            var numberOfMonths = document.getElementById('number_of_months');
            var monthsLabel = document.getElementById('months-label');
            var keyPaymentDisplay = document.getElementById('key_payment_amount_display');
            var keyPayment = document.getElementById('key_payment_amount');
            var contractSubmitButton = document.getElementById('contract-submit');

            // Helpers
            function formatNumber(value) {
                return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function unformatNumber(value) {
                return (value || '').toString().replace(/,/g, '');
            }

            function formatInput(displayInput, hiddenInput, errorElement) {
                displayInput.addEventListener('input', function() {
                    var numericValue = unformatNumber(displayInput.value);
                    if (isNaN(numericValue) || numericValue < 0) {
                        errorElement.textContent = '{{ __('word.invalid_amount') }}';
                        errorElement.classList.remove('hidden');
                        contractSubmitButton.disabled = true;
                    } else {
                        errorElement.textContent = '';
                        errorElement.classList.add('hidden');
                        var formattedValue = formatNumber(numericValue);
                        displayInput.value = formattedValue;
                        hiddenInput.value = numericValue;
                        validatePaymentPlan();
                        autoCalculateKeyPayment();
                    }
                });
            }

            // Initialize formatting for payment inputs
            formatInput(downPaymentDisplay, downPayment, document.getElementById('down_payment_error'));
            formatInput(monthlyInstallmentDisplay, monthlyInstallment, document.getElementById(
                'monthly_installment_error'));
            formatInput(contractAmountDisplay, contractAmount, document.getElementById('payment-plan-error'));

            // Check if variable payment plan is selected
            function isVariableSelected() {
                var selectedOption = $('#contract_payment_method_id').find('option:selected');
                var fromAttr = selectedOption.attr('data-is-variable');
                var fromData = selectedOption.data('is-variable');
                return (fromAttr === 'true') || (fromData === true) || (fromData === 'true');
            }

            // Calculate contract amount with discount
            function calculateContractAmount() {
                var selectedOption = $('#contract_building_id').find('option:selected');
                var basePrice = parseFloat(selectedOption.data('price')) || 0;
                var discount = parseFloat(discountInput.value) || 0;
                var discountedAmount = basePrice - (basePrice * (discount / 100));

                contractAmount.value = discountedAmount;
                contractAmountDisplay.value = formatNumber(discountedAmount.toString());

                if (isVariableSelected()) {
                    validatePaymentPlan();
                    autoCalculateKeyPayment();
                }
            }

            // Validate payment plan
            function validatePaymentPlan() {
                if (!isVariableSelected()) {
                    contractSubmitButton.disabled = false;
                    document.getElementById('payment-plan-error').classList.add('hidden');
                    return;
                }

                let discountedAmount = parseFloat(contractAmount.value) || 0;
                let down = parseFloat(unformatNumber(downPaymentDisplay.value)) || 0;
                let monthly = parseFloat(unformatNumber(monthlyInstallmentDisplay.value)) || 0;
                let months = parseInt(numberOfMonths.value) || 0;
                let key = parseFloat(unformatNumber(keyPaymentDisplay.value)) || 0;
                let total = down + (monthly * months) + key;
                let errorElement = document.getElementById('payment-plan-error');

                if (Math.abs(total - discountedAmount) > 0.01) {
                    errorElement.textContent = '{{ __('word.payment_plan_mismatch') }}';
                    errorElement.classList.remove('hidden');
                    contractSubmitButton.disabled = true;
                } else if (down > discountedAmount) {
                    errorElement.textContent = '{{ __('word.down_payment_exceeds_contract') }}';
                    errorElement.classList.remove('hidden');
                    contractSubmitButton.disabled = true;
                } else if (monthly * months > discountedAmount) {
                    errorElement.textContent = '{{ __('word.monthly_installments_exceed_contract') }}';
                    errorElement.classList.remove('hidden');
                    contractSubmitButton.disabled = true;
                } else {
                    errorElement.textContent = '';
                    errorElement.classList.add('hidden');
                    contractSubmitButton.disabled = false;
                }
            }

            // Auto-calculate key payment and update breakdown
            function autoCalculateKeyPayment() {
                if (!isVariableSelected()) return;

                let discountedAmount = parseFloat(contractAmount.value) || 0;
                let down = parseFloat(unformatNumber(downPaymentDisplay.value)) || 0;
                let monthly = parseFloat(unformatNumber(monthlyInstallmentDisplay.value)) || 0;
                let months = parseInt(numberOfMonths.value) || 0;

                monthsLabel.textContent = months;
                numberOfMonths.setAttribute('aria-valuenow', months);

                let subtotal = down + (monthly * months);
                let key = discountedAmount - subtotal;
                if (key < 0) key = 0;

                keyPaymentDisplay.value = formatNumber(key.toFixed(0));
                keyPayment.value = key;

                let tbody = document.getElementById('payment-breakdown');
                tbody.innerHTML = `
                    <tr>
                        <td class="px-2 py-1">{{ __('word.down_payment') }}</td>
                        <td class="px-2 py-1">${formatNumber(down)}</td>
                        <td class="px-2 py-1">1</td>
                        <td class="px-2 py-1">${formatNumber(down)}</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1">{{ __('word.monthly_installments') }}</td>
                        <td class="px-2 py-1">${formatNumber(monthly)}</td>
                        <td class="px-2 py-1">${months}</td>
                        <td class="px-2 py-1">${formatNumber(monthly * months)}</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1">{{ __('word.key_payment') }}</td>
                        <td class="px-2 py-1">${formatNumber(key)}</td>
                        <td class="px-2 py-1">1</td>
                        <td class="px-2 py-1">${formatNumber(key)}</td>
                    </tr>
                `;
                document.getElementById('breakdown-total').textContent = formatNumber(discountedAmount);
                validatePaymentPlan();
            }

            // Toggle variable payment fields
            function toggleVariableFields() {
                if (isVariableSelected()) {
                    variablePaymentFields.classList.remove('hidden');
                    autoCalculateKeyPayment();
                } else {
                    variablePaymentFields.classList.add('hidden');
                    document.getElementById('payment-plan-error').classList.add('hidden');
                    contractSubmitButton.disabled = false;
                }
            }

            // Event listeners
            $('#contract_payment_method_id').on('change select2:select', toggleVariableFields);
            $('#contract_building_id').on('change select2:select', calculateContractAmount);
            discountInput.addEventListener('input', calculateContractAmount);
            numberOfMonths.addEventListener('input', autoCalculateKeyPayment);

            // Prevent double submission for contract form
            document.querySelector('form[action="{{ route('contract.store') }}"]').addEventListener('submit',
                function() {
                    contractSubmitButton.textContent = '{{ __('word.saving') }}';
                    contractSubmitButton.disabled = true;
                });

            // Initial setup
            toggleVariableFields();
            calculateContractAmount();

            // Note: Server-side validation should be implemented in the contract.store route to:
            // 1. Verify contract_amount matches building price after discount.
            // 2. Ensure sum of payments (down_payment + monthly_installments * months + key_payment) equals contract_amount for variable plans.
        });
    </script>
</x-app-layout>
