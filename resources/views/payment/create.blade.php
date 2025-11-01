<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <div class="flex justify-start">
            @include('payment.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('cash_account.nav.navigation')
            @include('cash_transfer.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('payment.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.payment_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_contract_id" class="w-full mb-1" :value="__('word.building_number')" />
                                    <select id="payment_contract_id" class="js-example-basic-single w-full block mt-1 "
                                        name="payment_contract_id" data-placeholder="ادخل الاسم او رقم العقار">
                                        <option value=""></option>
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ old('payment_contract_id') == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->id . ' الاسم : ' . $contract->customer->customer_full_name . ' ** رقم العقار --   ' . $contract->building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('payment_contract_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <!-- Installment Selection Section (Hidden until contract is selected) -->
                            <div id="installment-section" class="hidden">
                                <div class="mx-4 my-4">
                                    <x-input-label for="contract_installment_id" class="w-full mb-1"
                                        :value="__('اختر القسط')" />
                                    <select id="contract_installment_id"
                                        class="w-full block mt-1 border-gray-300 rounded-md"
                                        name="contract_installment_id">
                                        <option value="">اختر القسط...</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_installment_id')" class="w-full mt-2" />
                                </div>

                                <!-- Installment Details Display -->
                                <div id="installment-details"
                                    class="mx-4 my-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="font-semibold">المبلغ الكلي:</span>
                                            <span id="detail-total-amount" class="text-lg">0</span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">المبلغ المدفوع:</span>
                                            <span id="detail-paid-amount" class="text-lg text-green-600">0</span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">المبلغ المتبقي:</span>
                                            <span id="detail-remaining-amount" class="text-lg text-red-600">0</span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">نسبة الإنجاز:</span>
                                            <span id="detail-progress" class="text-lg">0%</span>
                                        </div>
                                    </div>
                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="w-full bg-gray-200 rounded-full h-4">
                                            <div id="progress-bar"
                                                class="bg-green-500 h-4 rounded-full transition-all duration-300"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="payment_amount_display" class="w-full mb-1" :value="__('word.payment_amount')" />
                                    <x-text-input id="payment_amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('payment_amount', 0), 0) }}" placeholder="0" />
                                    <input type="hidden" id="payment_amount" name="payment_amount"
                                        value="{{ old('payment_amount') }}">
                                    <x-input-error :messages="$errors->get('payment_amount')" class="w-full mt-2" />
                                    <small id="amount-hint" class="text-gray-500 hidden">الحد الأقصى: <span
                                            id="max-amount">0</span></small>
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_date" class="w-full mb-1" :value="__('word.payment_date')" />
                                    <x-text-input id="payment_date" class="w-full block mt-1" type="text"
                                        name="payment_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                    <x-input-error :messages="$errors->get('payment_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_note" class="w-full mb-1" :value="__('word.payment_note')" />
                                    <x-text-input id="payment_note" class="w-full block mt-1" type="text"
                                        name="payment_note" value="{{ old('payment_note') }}" />
                                    <x-input-error :messages="$errors->get('payment_note')" class="w-full mt-2" />
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
        document.addEventListener("DOMContentLoaded", function() {
            var displayInput = document.getElementById('payment_amount_display');
            var hiddenInput = document.getElementById('payment_amount');
            var contractSelect = document.getElementById('payment_contract_id');
            var installmentSection = document.getElementById('installment-section');
            var installmentSelect = document.getElementById('contract_installment_id');
            var installmentDetails = document.getElementById('installment-details');
            var maxAmountAllowed = 0;

            function formatNumber(value) {
                // Preserve negative sign and remove non-numeric characters except the negative sign
                let isNegative = value.startsWith('-');
                let cleanValue = value.replace(/[^\d-]/g, '');
                // Ensure only one negative sign at the start
                cleanValue = cleanValue.replace(/-+/g, (match, offset) => offset === 0 ? '-' : '');
                // Format with commas
                let formatted = cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return (isNegative && !formatted.startsWith('-') ? '-' : '') + formatted;
            }

            function unformatNumber(value) {
                // Preserve negative sign and remove commas
                let isNegative = value.startsWith('-');
                let cleanValue = value.replace(/,/g, '');
                // Ensure only one negative sign at the start
                cleanValue = cleanValue.replace(/-+/g, (match, offset) => offset === 0 ? '-' : '');
                return cleanValue;
            }

            displayInput.addEventListener('input', function() {
                var formattedValue = formatNumber(displayInput.value);
                displayInput.value = formattedValue;
                hiddenInput.value = unformatNumber(formattedValue);

                // Validate against max amount
                var enteredAmount = parseFloat(unformatNumber(formattedValue));
                if (maxAmountAllowed > 0 && enteredAmount > maxAmountAllowed) {
                    displayInput.classList.add('border-red-500');
                    document.getElementById('amount-hint').classList.remove('text-gray-500');
                    document.getElementById('amount-hint').classList.add('text-red-500', 'font-bold');
                } else {
                    displayInput.classList.remove('border-red-500');
                    document.getElementById('amount-hint').classList.remove('text-red-500', 'font-bold');
                    document.getElementById('amount-hint').classList.add('text-gray-500');
                }
            });

            // Load installments when contract is selected
            $(contractSelect).on('change', function() {
                var contractId = $(this).val();

                if (contractId) {
                    // Show loading state
                    installmentSelect.innerHTML = '<option value="">جاري التحميل...</option>';
                    installmentSection.classList.remove('hidden');
                    installmentDetails.classList.add('hidden');

                    // Fetch installments via AJAX
                    fetch(`/payment/contract/${contractId}/installments`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                installmentSelect.innerHTML = '<option value="">اختر القسط...</option>';

                                data.installments.forEach(function(installment) {
                                    var option = document.createElement('option');
                                    option.value = installment.id;
                                    option.dataset.amount = installment.amount;
                                    option.dataset.paidAmount = installment.paid_amount;
                                    option.dataset.remaining = installment.remaining;
                                    option.dataset.progress = installment.progress;
                                    option.dataset.isFullyPaid = installment.is_fully_paid;

                                    var statusIcon = installment.is_fully_paid ? '✅' :
                                        (installment.paid_amount > 0 ? '⏳' : '❌');

                                    var displayText = `${statusIcon} ${installment.name} - ` +
                                        `المبلغ: ${formatNumber(installment.amount.toString())} - ` +
                                        `المتبقي: ${formatNumber(installment.remaining.toString())}`;

                                    option.textContent = displayText;

                                    // Disable if fully paid
                                    if (installment.is_fully_paid) {
                                        option.disabled = true;
                                    }

                                    installmentSelect.appendChild(option);
                                });
                            } else {
                                installmentSelect.innerHTML =
                                    '<option value="">حدث خطأ في تحميل الأقساط</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            installmentSelect.innerHTML =
                                '<option value="">حدث خطأ في تحميل الأقساط</option>';
                        });
                } else {
                    installmentSection.classList.add('hidden');
                    installmentDetails.classList.add('hidden');
                }
            });

            // Show installment details when selected
            installmentSelect.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];

                if (this.value && selectedOption.dataset.amount) {
                    var totalAmount = parseFloat(selectedOption.dataset.amount);
                    var paidAmount = parseFloat(selectedOption.dataset.paidAmount);
                    var remaining = parseFloat(selectedOption.dataset.remaining);
                    var progress = parseFloat(selectedOption.dataset.progress);

                    // Update details display
                    document.getElementById('detail-total-amount').textContent = formatNumber(totalAmount
                        .toString());
                    document.getElementById('detail-paid-amount').textContent = formatNumber(paidAmount
                        .toString());
                    document.getElementById('detail-remaining-amount').textContent = formatNumber(remaining
                        .toString());
                    document.getElementById('detail-progress').textContent = progress.toFixed(1) + '%';
                    document.getElementById('progress-bar').style.width = progress + '%';

                    // Set max amount and show hint
                    maxAmountAllowed = remaining;
                    document.getElementById('max-amount').textContent = formatNumber(remaining.toString());
                    document.getElementById('amount-hint').classList.remove('hidden');

                    // Auto-fill with remaining amount
                    displayInput.value = formatNumber(remaining.toString());
                    hiddenInput.value = remaining;

                    installmentDetails.classList.remove('hidden');
                } else {
                    installmentDetails.classList.add('hidden');
                    document.getElementById('amount-hint').classList.add('hidden');
                    maxAmountAllowed = 0;
                }
            });

            // On form submission, make sure the hidden input is set correctly
            document.querySelector('form').addEventListener('submit', function(e) {
                hiddenInput.value = unformatNumber(displayInput.value);

                // Validate amount doesn't exceed maximum
                var enteredAmount = parseFloat(hiddenInput.value);
                if (maxAmountAllowed > 0 && enteredAmount > maxAmountAllowed) {
                    e.preventDefault();
                    alert('مبلغ الدفعة يتجاوز المبلغ المتبقي للقسط!\nالحد الأقصى: ' + formatNumber(
                        maxAmountAllowed.toString()));
                    return false;
                }
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $('form').on('submit', function() {
            var $submitButton = $(this).find('button[type="submit"]');
            $submitButton.text('جاري الحفظ');
            $submitButton.prop('disabled', true);
        });
    </script>
</x-app-layout>
