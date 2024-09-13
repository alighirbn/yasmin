<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $transfer->contract->url_address) }}"
                                class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan
                        @if (!$transfer->approved)
                            <form action="{{ route('transfer.approve', $transfer->url_address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-custom-edit">قبول التناقل</button>
                            </form>
                        @endif

                        @can('transfer-print')
                            <a href="{{ route('transfer.print', $transfer->url_address) }}" class="btn btn-custom-print">
                                {{ __('word.transfer_print') }}
                            </a>
                        @endcan

                    </div>
                    <!-- Blade template to display the image -->

                    <div class="print-container">
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.transfer_info') }}
                        </h1>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="id" class="w-full mb-1" :value="__('word.transfer_id')" />
                                <p id="id" class="w-full h-9 block mt-1 " type="text" name="id">
                                    {{ $transfer->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_date" class="w-full mb-1" :value="__('word.transfer_date')" />
                                <p id="transfer_date" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_date">
                                    {{ $transfer->transfer_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                <p id="contract_id" class="w-full h-9 block mt-1 " type="text" name="contract_id">
                                    {{ $transfer->contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $transfer->contract->contract_date }}
                            </div>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="newcustomer" class="w-full mb-1" :value="__('word.newcustomer')" />
                                <p id="newcustomer" class="w-full h-9 block mt-1 " type="text" name="newcustomer">
                                    {{ $transfer->newcustomer->customer_full_name }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="oldcustomer" class="w-full mb-1" :value="__('word.oldcustomer')" />
                                <p id="oldcustomer" class="w-full h-9 block mt-1 " type="text" name="oldcustomer">
                                    {{ $transfer->oldcustomer->customer_full_name }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_amount" class="w-full mb-1" :value="__('word.transfer_amount')" />
                                <p id="transfer_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_amount">
                                    {{ number_format($transfer->transfer_amount, 0) }} دينار
                            </div>
                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                <p id="transfer_note" class="w-full h-9 block mt-1 " type="text"
                                    name="transfer_note">
                                    {{ $transfer->transfer_note }}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex justify-center my-8">
                            <div class="border-4 border-gray-300 rounded-md shadow-lg p-2">
                                <img src="{{ asset($transfer->old_customer_picture) }}" alt="Webcam Image"
                                    class="rounded-md shadow-md">
                            </div>
                        </div>
                        <div class="flex justify-center my-8">
                            <div class="border-4 border-gray-300 rounded-md shadow-lg p-2">
                                <img src="{{ asset($transfer->new_customer_picture) }}" alt="Webcam Image"
                                    class="rounded-md shadow-md">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
