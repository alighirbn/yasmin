<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('contract.nav.navigation')
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
                            <a href="{{ route('contract.customercreate') }}" class="my-1 mx-1 btn btn-custom-edit">
                                {{ __('word.customer_add') }}
                            </a>
                        @endcan
                    </div>
                    <div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <form method="post" action="{{ route('contract.store') }}">
                            @csrf

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                                {{ __('word.contract_info') }}
                            </h1>

                    </div>
                    <div class="flex ">
                        <div class=" mx-4 my-4 w-full">

                            <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                            <select id="contract_customer_id" class="js-example-basic-single w-full block mt-1 "
                                name="contract_customer_id" data-placeholder="ادخل اسم الزبون">
                                <option value="">

                                </option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('contract_customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />

                        </div>

                        <div class=" mx-4 my-4 w-full">
                            <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                            <select id="contract_building_id" class="js-example-basic-single w-full block mt-1 "
                                name="contract_building_id" data-placeholder="ادخل رقم العقار">
                                <option value="">

                                </option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        {{ old('contract_building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->building_number }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('contract_building_id')" class="w-full mt-2" />
                        </div>

                        <div class=" mx-4 my-4 w-full">
                            <x-input-label for="contract_payment_method_id" class="w-full mb-1" :value="__('word.contract_payment_method_id')" />
                            <select id="contract_payment_method_id" class="js-example-basic-single w-full block mt-1 "
                                name="contract_payment_method_id"data-placeholder="ادخل طريقة الدفع">
                                <option value="">

                                </option>
                                @foreach ($payment_methods as $payment_method)
                                    <option value="{{ $payment_method->id }}"
                                        {{ old('contract_payment_method_id') == $payment_method->id ? 'selected' : '' }}>
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
                                name="contract_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                placeholder="yyyy-mm-dd" />
                            <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                        </div>

                        <div class="mx-4 my-4 w-full">
                            <x-input-label for="contract_amount_display" class="w-full mb-1" :value="__('word.contract_amount')" />

                            <!-- Displayed input for formatted number -->
                            <x-text-input id="contract_amount_display" class="w-full block mt-1" type="text"
                                value="{{ number_format(old('contract_amount', 0), 0) }}" placeholder="0" />

                            <!-- Hidden input for the actual number -->
                            <input type="hidden" id="contract_amount" name="contract_amount"
                                value="{{ old('contract_amount') }}">

                            <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                        </div>

                        <div class=" mx-4 my-4 w-full">
                            <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                            <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                name="contract_note" value="{{ old('contract_note') }}" />
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
