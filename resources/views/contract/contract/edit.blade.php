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
                            <input type="hidden" id="user_id_update" name="user_id_update"
                                value="{{ auth()->user()->id }}">

                            <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 w-full">
                                {{ __('word.contract_info') }}
                            </h1>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_customer_id" class="w-full mb-1"
                                        :value="__('word.contract_customer_id')" />
                                    <select id="contract_customer_id"
                                        class="js-example-basic-single w-full block mt-1" name="contract_customer_id">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ (old('contract_customer_id') ?? $contract->contract_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_building_id" class="w-full mb-1"
                                        :value="__('word.contract_building_id')" />
                                    <select id="contract_building_id"
                                        class="js-example-basic-single w-full block mt-1" name="contract_building_id">
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

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_payment_method_id" class="w-full mb-1"
                                        :value="__('word.contract_payment_method_id')" />
                                    <select id="contract_payment_method_id"
                                        class="js-example-basic-single w-full block mt-1"
                                        name="contract_payment_method_id">
                                        @foreach ($payment_methods as $payment_method)
                                            <option value="{{ $payment_method->id }}"
                                                data-is-variable="{{ $payment_method->method_name === 'دفعات متغيرة' ? 'true' : 'false' }}"
                                                {{ (old('contract_payment_method_id') ?? $contract->contract_payment_method_id) == $payment_method->id ? 'selected' : '' }}>
                                                {{ $payment_method->method_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_payment_method_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                    <x-text-input id="contract_date" class="w-full block mt-1" type="text"
                                        name="contract_date"
                                        value="{{ old('contract_date', $contract->contract_date) }}" />
                                    <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_amount_display" class="w-full mb-1"
                                        :value="__('word.contract_amount')" />
                                    <x-text-input id="contract_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('contract_amount', $contract->contract_amount), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="contract_amount" name="contract_amount"
                                        value="{{ old('contract_amount', $contract->contract_amount) }}">
                                    <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="discount" class="w-full mb-1" :value="__('word.discount')" />
                                    <x-text-input id="discount" class="w-full block mt-1" type="number"
                                        name="discount" value="{{ old('discount', $contract->discount) }}"
                                        step="0.5" min="0" max="100" placeholder="0.00" />
                                    <x-input-error :messages="$errors->get('discount')" class="w-full mt-2" />
                                </div>
                            </div>

                            <!-- Variable Payment Plan Fields -->
                            <div id="variable-payment-fields"
                                class="{{ old('contract_payment_method_id', $contract->contract_payment_method_id) && $payment_methods->firstWhere('method_name', 'دفعات متغيرة') && old('contract_payment_method_id', $contract->contract_payment_method_id) == $payment_methods->firstWhere('method_name', 'دفعات متغيرة')->id ? '' : 'hidden' }}">
                                <h2
                                    class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-4 w-full">
                                    {{ __('word.variable_payment_plan') }}
                                </h2>
                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="down_payment_amount_display" class="w-full mb-1"
                                            :value="__('word.down_payment_amount')" />
                                        <x-text-input id="down_payment_amount_display" class="w-full block mt-1"
                                            type="text"
                                            value="{{ number_format((float) old('down_payment_amount', $variable_payment_details['down_payment_amount'] ?? 0), 0) }}"
                                            placeholder="0" />
                                        <input type="hidden" id="down_payment_amount" name="down_payment_amount"
                                            value="{{ old('down_payment_amount', $variable_payment_details['down_payment_amount'] ?? 0) }}">
                                        <x-input-error :messages="$errors->get('down_payment_amount')" class="w-full mt-2" />
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="monthly_installment_amount_display" class="w-full mb-1"
                                            :value="__('word.monthly_installment_amount')" />
                                        <x-text-input id="monthly_installment_amount_display"
                                            class="w-full block mt-1" type="text"
                                            value="{{ number_format((float) old('monthly_installment_amount', $variable_payment_details['monthly_installment_amount'] ?? 0), 0) }}"
                                            placeholder="0" />
                                        <input type="hidden" id="monthly_installment_amount"
                                            name="monthly_installment_amount"
                                            value="{{ old('monthly_installment_amount', $variable_payment_details['monthly_installment_amount'] ?? 0) }}">
                                        <x-input-error :messages="$errors->get('monthly_installment_amount')" class="w-full mt-2" />
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="number_of_months" class="w-full mb-1"
                                            :value="__('word.number_of_months')" />
                                        <x-text-input id="number_of_months" class="w-full block mt-1" type="number"
                                            name="number_of_months"
                                            value="{{ old('number_of_months', $variable_payment_details['number_of_months'] ?? 1) }}"
                                            min="1" placeholder="1" />
                                        <x-input-error :messages="$errors->get('number_of_months')" class="w-full mt-2" />
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="key_payment_amount_display" class="w-full mb-1"
                                            :value="__('word.key_payment_amount')" />
                                        <x-text-input id="key_payment_amount_display" class="w-full block mt-1"
                                            type="text"
                                            value="{{ number_format((float) old('key_payment_amount', $variable_payment_details['key_payment_amount'] ?? 0), 0) }}"
                                            placeholder="0" />
                                        <input type="hidden" id="key_payment_amount" name="key_payment_amount"
                                            value="{{ old('key_payment_amount', $variable_payment_details['key_payment_amount'] ?? 0) }}">
                                        <x-input-error :messages="$errors->get('key_payment_amount')" class="w-full mt-2" />
                                    </div>

                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="remaining_amount" class="w-full mb-1"
                                            :value="__('word.remaining_amount')" />
                                        <x-text-input id="remaining_amount" class="w-full block mt-1 bg-gray-100"
                                            type="text" readonly value="0" />
                                        <div id="remaining-amount-error" class="text-red-600 text-sm mt-2 hidden">
                                            {{ __('word.remaining_amount_error') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                    <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                        name="contract_note"
                                        value="{{ old('contract_note', $contract->contract_note) }}" />
                                    <x-input-error :messages="$errors->get('contract_note')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-primary-button class="ml-4">
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
                event.preventDefault();
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
                            var newOption = document.createElement('option');
                            newOption.value = data.customer.id;
                            newOption.text = data.customer.customer_full_name;
                            customerSelect.add(newOption);
                            customerSelect.value = data.customer.id;
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

        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            var discountInput = $('#discount');
            var contractAmountDisplay = $('#contract_amount_display');
            var contractAmount = $('#contract_amount');
            var paymentMethodSelect = $('#contract_payment_method_id');
            var variablePaymentFields = $('#variable-payment-fields');
            var downPaymentDisplay = $('#down_payment_amount_display');
            var downPayment = $('#down_payment_amount');
            var monthlyInstallmentDisplay = $('#monthly_installment_amount_display');
            var monthlyInstallment = $('#monthly_installment_amount');
            var numberOfMonths = $('#number_of_months');
            var keyPaymentDisplay = $('#key_payment_amount_display');
            var keyPayment = $('#key_payment_amount');
            var remainingAmount = $('#remaining_amount');
            var remainingAmountError = $('#remaining-amount-error');
            var submitButton = $('button[type="submit"]');

            // تنسيق الأرقام مع الفواصل
            function formatNumber(value) {
                return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // إزالة الفواصل
            function unformatNumber(value) {
                return value.replace(/,/g, '');
            }

            // ربط حقل عرض بالقيمة المخفية
            function formatInput(displayInput, hiddenInput) {
                displayInput.on('input', function() {
                    var formattedValue = formatNumber(displayInput.val());
                    displayInput.val(formattedValue);
                    hiddenInput.val(unformatNumber(formattedValue));
                    calculateRemainingAmount();
                });
            }

            // ربط الحقول
            formatInput(contractAmountDisplay, contractAmount);
            formatInput(downPaymentDisplay, downPayment);
            formatInput(monthlyInstallmentDisplay, monthlyInstallment);
            formatInput(keyPaymentDisplay, keyPayment);

            // حساب مبلغ العقد بعد الخصم
            function calculateContractAmount() {
                var selectedOption = $('#contract_building_id').find('option:selected');
                var contractAmountValue = parseFloat(selectedOption.data('price')) || 0;
                var discount = parseFloat(discountInput.val()) || 0;

                // تطبيق الخصم على مبلغ العقار
                var discountedAmount = contractAmountValue - (contractAmountValue * (discount / 100));

                contractAmount.val(discountedAmount);
                contractAmountDisplay.val(formatNumber(discountedAmount.toString()));

                calculateRemainingAmount();
            }

            // حساب المبلغ المتبقي للدفعات المتغيرة
            function calculateRemainingAmount() {
                var selectedOption = $('#contract_payment_method_id').find('option:selected');
                var isVariable = selectedOption.data('is-variable') === true;

                if (isVariable) {
                    // استخدام المبلغ بعد الخصم من الحقل المخفي
                    var discountedAmount = parseFloat(unformatNumber(contractAmount.val())) || 0;

                    var downPaymentValue = parseFloat(unformatNumber(downPaymentDisplay.val())) || 0;
                    var monthlyInstallmentValue = parseFloat(unformatNumber(monthlyInstallmentDisplay.val())) || 0;
                    var months = parseInt(numberOfMonths.val()) || 0;
                    var keyPaymentValue = parseFloat(unformatNumber(keyPaymentDisplay.val())) || 0;

                    var total = downPaymentValue + (monthlyInstallmentValue * months) + keyPaymentValue;
                    var remaining = discountedAmount - total;

                    remainingAmount.val(formatNumber(remaining.toFixed(0)));

                    if (Math.abs(remaining) > 0.01) {
                        remainingAmount.addClass('border-red-500');
                        remainingAmountError.removeClass('hidden');
                        submitButton.prop('disabled', true);
                    } else {
                        remainingAmount.removeClass('border-red-500');
                        remainingAmountError.addClass('hidden');
                        submitButton.prop('disabled', false);
                    }
                }
            }

            // إظهار/إخفاء حقول الدفعات المتغيرة
            function toggleVariableFields() {
                var selectedOption = $('#contract_payment_method_id').find('option:selected');
                var isVariable = selectedOption.data('is-variable') === true;

                if (isVariable) {
                    variablePaymentFields.removeClass('hidden');
                    calculateRemainingAmount();
                } else {
                    variablePaymentFields.addClass('hidden');
                    submitButton.prop('disabled', false);
                }
            }

            // Bind events
            $('#contract_payment_method_id').on('select2:select', toggleVariableFields);
            $('#contract_building_id').on('select2:select', calculateContractAmount);

            discountInput.on('input', calculateContractAmount);
            downPaymentDisplay.on('input', calculateRemainingAmount);
            monthlyInstallmentDisplay.on('input', calculateRemainingAmount);
            numberOfMonths.on('input', calculateRemainingAmount);
            keyPaymentDisplay.on('input', calculateRemainingAmount);

            // Initialize
            toggleVariableFields();
            calculateContractAmount();

            // منع التكرار بالحفظ
            $('form').on('submit', function() {
                submitButton.text('جاري الحفظ');
                submitButton.prop('disabled', true);
            });
        });
    </script>
</x-app-layout>
