<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('payment.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $payment->contract->url_address) }}"
                                class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        @if (!$payment->approved)
                            <form action="{{ route('payment.approve', $payment->url_address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-custom-edit">قبول الدفعة</button>
                            </form>
                        @endif
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container w-full">

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.payment_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="payment_id" class="w-full mb-1" :value="__('word.payment_id')" />
                                <p id="payment_id" class="w-full h-9 block mt-1" type="text" name="payment_id">
                                    {{ $payment->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="payment_date" class="w-full mb-1" :value="__('word.payment_date')" />
                                <p id="payment_date" class="w-full h-9 block mt-1 " type="text" name="payment_date">
                                    {{ $payment->payment_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="installment_name" class="w-full mb-1" :value="__('word.installment_name')" />
                                <p id="installment_name" class="w-full h-9 block mt-1 " type="text"
                                    name="installment_name">
                                    {{ $payment->contract_installment->installment->installment_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="payment_amount" class="w-full mb-1" :value="__('word.payment_amount')" />
                                <p id="payment_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="payment_amount">
                                    {{ number_format($payment->payment_amount, 0) }} دينار
                            </div>

                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.customer_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                <p id="customer_full_name" class="w-full h-9 block mt-1" type="text"
                                    name="customer_full_name">
                                    {{ $payment->contract->customer->customer_full_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_number">
                                    {{ $payment->contract->customer->customer_card_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                <p id="contract_id" class="w-full h-9 block mt-1" type="text" name="contract_id">
                                    {{ $payment->contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $payment->contract->contract_date }}
                            </div>

                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.building_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_category_id">
                                    {{ $payment->contract->building->building_category->category_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                <p id="building_number" class="w-full h-9 block mt-1 " type="text"
                                    name="building_number">
                                    {{ $payment->contract->building->building_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1" type="text"
                                    name="block_number">
                                    {{ $payment->contract->building->block_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text"
                                    name="house_number">
                                    {{ $payment->contract->building->house_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1 " type="text"
                                    name="building_area">
                                    {{ $payment->contract->building->building_area }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="payment_note" class="w-full mb-1" :value="__('word.payment_note')" />
                                <p id="payment_note" class="w-full h-9 block mt-1" type="text"
                                    name="payment_note">
                                    {{ $payment->payment_note }}
                            </div>

                        </div>

                    </div>
                    <div class="flex">
                        @if (isset($payment->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $payment->user_create->name }}
                                {{ $payment->created_at }}
                            </div>
                        @endif

                        @if (isset($payment->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $payment->user_update->name }}
                                {{ $payment->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
