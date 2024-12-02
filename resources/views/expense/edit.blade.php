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
                        <form method="post" action="{{ route('expense.update', $expense->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $expense->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $expense->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.expense_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_type_id" class="w-full mb-1" :value="__('word.expense_type_id')" />
                                    <select id="expense_type_id" class="w-full block mt-1 " name="expense_type_id">
                                        @foreach ($expense_types as $expense_type)
                                            <option value="{{ $expense_type->id }}"
                                                {{ (old('expense_type_id') ?? $expense_type->expense_type_id) == $expense_type->id ? 'selected' : '' }}>
                                                {{ $expense_type->expense_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('expense_type_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_amount" class="w-full mb-1" :value="__('word.expense_amount')" />
                                    <x-text-input id="expense_amount" class="w-full block mt-1" type="text"
                                        name="expense_amount"
                                        value="{{ old('expense_amount') ?? $expense->expense_amount }}" />
                                    <x-input-error :messages="$errors->get('expense_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_date" class="w-full mb-1" :value="__('word.expense_date')" />
                                    <x-text-input id="expense_date" class="w-full block mt-1" type="text"
                                        name="expense_date"
                                        value="{{ old('expense_date') ?? $expense->expense_date }}" />
                                    <x-input-error :messages="$errors->get('expense_date')" class="w-full mt-2" />
                                </div>
                            </div>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="expense_note" class="w-full mb-1" :value="__('word.expense_note')" />
                                    <x-text-input id="expense_note" class="w-full block mt-1" type="text"
                                        name="expense_note"
                                        value="{{ old('expense_note') ?? $expense->expense_note }}" />
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

</x-app-layout>
