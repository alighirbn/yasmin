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
                    <div>
                        <form method="post" action="{{ route('income.update', $income->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $income->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $income->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.income_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="income_type_id" class="w-full mb-1" :value="__('word.income_type_id')" />
                                    <select id="income_type_id" class="w-full block mt-1 " name="income_type_id">
                                        @foreach ($income_types as $income_type)
                                            <option value="{{ $income_type->id }}"
                                                {{ (old('income_type_id') ?? $income_type->income_type_id) == $income_type->id ? 'selected' : '' }}>
                                                {{ $income_type->income_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('income_type_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="income_amount" class="w-full mb-1" :value="__('word.income_amount')" />
                                    <x-text-input id="income_amount" class="w-full block mt-1" type="text"
                                        name="income_amount"
                                        value="{{ old('income_amount') ?? $income->income_amount }}" />
                                    <x-input-error :messages="$errors->get('income_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="income_date" class="w-full mb-1" :value="__('word.income_date')" />
                                    <x-text-input id="income_date" class="w-full block mt-1" type="text"
                                        name="income_date" value="{{ old('income_date') ?? $income->income_date }}" />
                                    <x-input-error :messages="$errors->get('income_date')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="income_note" class="w-full mb-1" :value="__('word.income_note')" />
                                    <x-text-input id="income_note" class="w-full block mt-1" type="text"
                                        name="income_note" value="{{ old('income_note') ?? $income->income_note }}" />
                                    <x-input-error :messages="$errors->get('income_note')" class="w-full mt-2" />
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
