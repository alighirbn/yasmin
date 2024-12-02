<x-app-layout>

    <x-slot name="header">
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
                    <div class="container">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <h1>تعديل التحويل النقدي</h1>

                        <form action="{{ route('cash_transfer.update', $transfer->url_address) }}" method="POST">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $transfer->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $transfer->url_address }}">
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="from_account_id" class="w-full mb-1" :value="__('word.from_account_id')" />
                                    <select id="from_account_id" class="js-example-basic-single w-full block mt-1 "
                                        name="from_account_id" data-placeholder="من حساب">
                                        <option value="">

                                        </option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ (old('from_account_id') ?? $transfer->from_account_id) == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('from_account_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="to_account_id" class="w-full mb-1" :value="__('word.to_account_id')" />
                                    <select id="to_account_id" class="js-example-basic-single w-full block mt-1 "
                                        name="to_account_id" data-placeholder="الى حساب">
                                        <option value="">

                                        </option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ (old('to_account_id') ?? $transfer->to_account_id) == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('to_account_id')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="amount_display" class="w-full mb-1" :value="__('word.amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('amount') ?? $transfer->amount, 0) }}"
                                        placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="amount" name="amount"
                                        value="{{ old('amount') ?? $transfer->amount }}">

                                    <x-input-error :messages="$errors->get('amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="transfer_date" class="w-full mb-1" :value="__('word.transfer_date')" />
                                    <x-text-input id="transfer_date" class="w-full block mt-1" type="text"
                                        name="transfer_date"
                                        value="{{ old('transfer_date') ?? $transfer->transfer_date }}" />
                                    <x-input-error :messages="$errors->get('transfer_date')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                    <x-text-input id="transfer_note" class="w-full block mt-1" type="text"
                                        name="transfer_note"
                                        value="{{ old('transfer_note') ?? $transfer->transfer_note }}" />
                                    <x-input-error :messages="$errors->get('transfer_note')" class="w-full mt-2" />
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
            var displayInput = document.getElementById('amount_display');
            var hiddenInput = document.getElementById('amount');

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
