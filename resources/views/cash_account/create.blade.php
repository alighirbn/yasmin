<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('expense.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                    </div>
                    <div>
                        <form method="post" action="{{ route('expense.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.expense_info') }}
                            </h1>

                            <div class="flex ">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_type_id" class="w-full mb-1" :value="__('word.expense_type_id')" />
                                    <select id="expense_type_id" class="js-example-basic-single w-full block mt-1 "
                                        name="expense_type_id" data-placeholder="ادخل باب الصرف">
                                        <option value="">

                                        </option>
                                        @foreach ($expense_types as $expense_type)
                                            <option value="{{ $expense_type->id }}"
                                                {{ old('expense_type_id') == $expense_type->id ? 'selected' : '' }}>
                                                {{ $expense_type->expense_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('expense_type_id')" class="w-full mt-2" />
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="expense_amount_display" class="w-full mb-1" :value="__('word.expense_amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="expense_amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('expense_amount', 0), 0) }}" placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="expense_amount" name="expense_amount"
                                        value="{{ old('expense_amount') }}">

                                    <x-input-error :messages="$errors->get('expense_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_date" class="w-full mb-1" :value="__('word.expense_date')" />
                                    <x-text-input id="expense_date" class="w-full block mt-1" type="text"
                                        name="expense_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                    <x-input-error :messages="$errors->get('expense_date')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_note" class="w-full mb-1" :value="__('word.expense_note')" />
                                    <x-text-input id="expense_note" class="w-full block mt-1" type="text"
                                        name="expense_note" value="{{ old('expense_note') }}" />
                                    <x-input-error :messages="$errors->get('expense_note')" class="w-full mt-2" />
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
            var displayInput = document.getElementById('expense_amount_display');
            var hiddenInput = document.getElementById('expense_amount');

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
