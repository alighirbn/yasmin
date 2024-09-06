<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('payment.nav.navigation')

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
                                        <option value="">

                                        </option>
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ old('payment_contract_id') == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->customer->customer_full_name . ' ** رقم العقار --   ' . $contract->building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('payment_contract_id')" class="w-full mt-2" />
                                </div>

                            </div>

                            <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4  w-full">
                                {{ __('word.payment_card') }}
                            </h2>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="payment_amount_display" class="w-full mb-1" :value="__('word.payment_amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="payment_amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('payment_amount', 0), 0) }}" placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="payment_amount" name="payment_amount"
                                        value="{{ old('payment_amount') }}">

                                    <x-input-error :messages="$errors->get('payment_amount')" class="w-full mt-2" />
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
