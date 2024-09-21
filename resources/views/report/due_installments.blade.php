<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <style>
            @media print {
                .no-print {
                    display: none;
                }
            }
        </style>
        @include('report.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    <div class="filter-container a4-width mx-auto ">
                        <form method="GET" action="{{ route('report.due_installments') }}">
                            <!-- Update with the appropriate route -->
                            <div class="flex items-center mb-4">
                                <label for="block_number" class="mr-2">{{ __('word.block_number') }}</label>
                                <select name="block_number" id="block_number" class="form-select">
                                    <option value="">{{ __('word.all') }}</option>
                                    @foreach ($block_numbers as $block)
                                        <!-- Make sure to pass building blocks to the view -->
                                        <option value="{{ $block }}">{{ $block }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="btn btn-custom-filter ml-2">{{ __('word.filter') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                <h1 class="text-xl font-semibold mb-4">تقرير الدفعات المستحقة</h1>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: auto; height: 90px;">
                            </div>
                        </div>

                        <table class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="no-print">
                                        {{ __('word.action') }}
                                    </th>
                                    <th>
                                        {{ __('word.contract_id') }}
                                    </th>
                                    <th>
                                        {{ __('word.customer_full_name') }}
                                    </th>
                                    <th>
                                        {{ __('word.customer_phone') }}
                                    </th>
                                    <th>
                                        {{ __('word.building_number') }}
                                    </th>
                                    <th>
                                        {{ __('word.due_installments_count') }}
                                    </th>
                                    <th>
                                        {{ __('word.due_installments_total') }}
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['due_installments'] as $data)
                                    <tr>
                                        <td class="no-print">
                                            <a href="{{ route('contract.show', $data->url_address) }}"
                                                class="btn btn-custom-show">
                                                {{ __('word.view') }}
                                            </a>
                                        </td>
                                        <td>{{ $data->contract_id }}
                                        </td>
                                        <td>
                                            {{ $data->customer_full_name }}</td>
                                        <td>
                                            {{ $data->customer_phone }}
                                        </td>
                                        <td>
                                            {{ $data->building_number }}</td>
                                        <td>
                                            {{ $data->due_installments_count }}</td>
                                        <td>
                                            {{ number_format($data->total_due_amount, 0) }} دينار</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
