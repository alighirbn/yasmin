<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

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
                    </div>
                    <div>
                        <form method="post" action="{{ route('contract.update', $contract->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $contract->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $contract->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                                {{ __('word.contract_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                                    <select id="contract_customer_id" class="w-full block mt-1 "
                                        name="contract_customer_id">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ (old('contract_customer_id') ?? $contract->contract_customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->customer_full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                                    <select id="contract_building_id" class="w-full block mt-1 "
                                        name="contract_building_id">
                                        @foreach ($buildings as $building)
                                            <option value="{{ $building->id }}"
                                                {{ (old('contract_building_id') ?? $contract->contract_building_id) == $building->id ? 'selected' : '' }}>
                                                {{ $building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('contract_building_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_payment_method_id" class="w-full mb-1"
                                        :value="__('word.contract_payment_method_id')" />
                                    <select id="contract_payment_method_id" class="w-full block mt-1 "
                                        name="contract_payment_method_id">
                                        @foreach ($payment_methods as $payment_method)
                                            <option value="{{ $payment_method->id }}"
                                                {{ (old('contract_payment_method_id') ?? $contract->contract_payment_method_id) == $payment_method->id ? 'selected' : '' }}>
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
                                        name="contract_date"
                                        value="{{ old('contract_date') ?? $contract->contract_date }}" />
                                    <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_amount" class="w-full mb-1" :value="__('word.contract_amount')" />
                                    <x-text-input id="contract_amount" class="w-full block mt-1" type="text"
                                        name="contract_amount"
                                        value="{{ old('contract_amount') ?? $contract->contract_amount }}" />
                                    <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                    <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                        name="contract_note"
                                        value="{{ old('contract_note') ?? $contract->contract_note }}" />
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
    </div>

</x-app-layout>
