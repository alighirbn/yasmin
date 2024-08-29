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
                        @can('contract-statement')
                            <a href="{{ route('contract.statement', $contract->url_address) }}"
                                class="btn btn-custom-statement">
                                {{ __('word.statement') }}
                            </a>
                        @endcan
                        @can('contract-update')
                            <a href="{{ route('contract.edit', $contract->url_address) }}" class="btn btn-custom-edit">
                                {{ __('word.contract_edit') }}
                            </a>
                        @endcan

                    </div>
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.contract_info') }}
                        </h1>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="id" class="w-full mb-1" :value="__('word.id')" />
                                <p id="id" class="w-full h-9 block mt-1 " type="text" name="id">
                                    {{ $contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $contract->contract_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="method_name" class="w-full mb-1" :value="__('word.method_name')" />
                                <p id="method_name" class="w-full h-9 block mt-1 " type="text" name="method_name">
                                    {{ $contract->payment_method->method_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_amount" class="w-full mb-1" :value="__('word.contract_amount')" />
                                <p id="contract_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_amount">
                                    {{ number_format($contract->contract_amount, 0) }} دينار
                            </div>
                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.customer_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                                <p id="contract_customer_id" class="w-full h-9 block mt-1" type="text"
                                    name="contract_customer_id">
                                    {{ $contract->customer->customer_full_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1" type="text"
                                    name="customer_card_number">
                                    {{ $contract->customer->customer_card_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                <p id="customer_phone" class="w-full h-9 block mt-1" type="text"
                                    name="customer_phone">
                                    {{ $contract->customer->customer_phone }}
                            </div>
                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.building_info') }}
                        </h1>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_type_id" class="w-full mb-1" :value="__('word.building_type_id')" />
                                <p id="building_type_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_type_id">
                                    {{ $contract->building->building_type->type_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_category_id">

                                    {{ $contract->building->building_category->category_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                                <p id="contract_building_id" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_building_id">
                                    {{ $contract->building->building_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1 " type="text" name="block_number">
                                    {{ $contract->building->block_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text"
                                    name="house_number">
                                    {{ $contract->building->house_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1 " type="text"
                                    name="building_area">
                                    {{ $contract->building->building_area }}
                            </div>

                        </div>

                        <div class="flex">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                <p id="contract_note" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_note">
                                    {{ $contract->contract_note }}
                            </div>
                        </div>

                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.installment_info') }}
                        </h1>
                        <div class="container mt-4">

                            <table class="table table-striped">
                                <thead>
                                    <th scope="col" width="14%">{{ __('word.installment_number') }}</th>
                                    <th scope="col" width="14%">{{ __('word.installment_name') }}</th>
                                    <th scope="col" width="14%">{{ __('word.installment_percent') }}</th>
                                    <th scope="col" width="14%">{{ __('word.installment_amount') }}</th>
                                    <th scope="col" width="14%">{{ __('word.installment_date') }}</th>
                                    <th scope="col" width="14%">{{ __('word.installment_payment') }}</th>
                                    <th scope="col" width="14%">{{ __('word.action') }}</th>
                                </thead>
                                @php
                                    $hide = 0;
                                @endphp
                                @foreach ($contract_installments as $installment)
                                    <tr>
                                        <td>{{ $installment->installment->installment_number }}</td>
                                        <td>{{ $installment->installment->installment_name }}</td>
                                        <td>{{ $installment->installment->installment_percent * 100 }} %</td>
                                        <td>{{ number_format($installment->installment_amount, 0) }} دينار</td>
                                        <td>{{ $installment->installment_date }}</td>
                                        <td>{{ $installment->payment == null ? 'لم تسدد' : 'مسددة في ' . $installment->payment->payment_date }}
                                        </td>
                                        <td>
                                            @if ($installment->payment != null)
                                                @can('contract-show')
                                                    <a
                                                        href="{{ route('payment.show', $installment->payment->url_address) }}">
                                                        {{ __('word.view') }}
                                                    </a>
                                                @endcan
                                            @else
                                                @if ($hide == 0)
                                                    @can('contract-show')
                                                        <a href="{{ route('contract.add', $installment->url_address) }}"
                                                            class="add_payment">
                                                            {{ __('word.add_payment') }}
                                                        </a>

                                                        @php
                                                            $hide = 1;
                                                        @endphp
                                                    @endcan
                                                @else
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="flex">
                            @if (isset($contract->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $contract->user_create->name }}
                                    {{ $contract->created_at }}
                                </div>
                            @endif

                            @if (isset($contract->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $contract->user_update->name }}
                                    {{ $contract->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.add_payment').on('click', function(event) {
                if ($(this).data('clicked')) {
                    event.preventDefault();
                    return false;
                }
                $(this).data('clicked', true);
                $(this).text('جاري الاضافة');
            });
        });
    </script>

</x-app-layout>
