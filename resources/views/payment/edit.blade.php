<x-app-layout>

    <x-slot name="header">
        @include('payment.nav.navigation')
        @include('expense.nav.navigation')
        @include('cash_account.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('payment.update', $payment->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $payment->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $payment->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.payment_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_contract_id" class="w-full mb-1" :value="__('word.payment_contract_id')" />
                                    <select id="payment_contract_id" class="w-full block mt-1 "
                                        name="payment_contract_id">
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ (old('payment_contract_id') ?? $contract->payment_contract_id) == $contract->id ? 'selected' : '' }}>
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
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_amount" class="w-full mb-1" :value="__('word.payment_amount')" />
                                    <x-text-input id="payment_amount" class="w-full block mt-1" type="text"
                                        name="payment_amount"
                                        value="{{ old('payment_amount') ?? $payment->payment_amount }}" />
                                    <x-input-error :messages="$errors->get('payment_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_date" class="w-full mb-1" :value="__('word.payment_date')" />
                                    <x-text-input id="payment_date" class="w-full block mt-1" type="text"
                                        name="payment_date"
                                        value="{{ old('payment_date') ?? $payment->payment_date }}" />
                                    <x-input-error :messages="$errors->get('payment_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="payment_note" class="w-full mb-1" :value="__('word.payment_note')" />
                                    <x-text-input id="payment_note" class="w-full block mt-1" type="text"
                                        name="payment_note"
                                        value="{{ old('payment_note') ?? $payment->payment_note }}" />
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

</x-app-layout>
