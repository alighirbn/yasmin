<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('customer.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
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
                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="flex">

                            <div class="mx-2 my-2 w-full">
                                <h2 class="text-xl font-semibold mb-2">{{ __('word.statement_of_account') }}
                                    : {{ $customer->customer_full_name }}
                                </h2>

                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: auto; height: 90px;">
                            </div>

                        </div>

                        @foreach ($data as $contract)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="flex">
                                        <div class=" mx-2 my-2 w-full ">
                                            {!! QrCode::size(90)->generate($contract['contract_id']) !!}
                                        </div>
                                        <div class=" mx-2 my-2 w-full ">
                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract['building_number'], 'C39') }}"
                                                alt="barcode" />

                                            <p><strong>{{ __('word.contract_id') }}</strong>
                                                : {{ $contract['contract_id'] }}
                                            </p>
                                            <p><strong>{{ __('word.contract_date') }}</strong>
                                                : {{ $contract['contract_date'] }}
                                            </p>
                                            <p><strong>{{ __('word.contract_amount') }}</strong>
                                                : {{ number_format($contract['contract_amount'], 0) }}
                                            </p>
                                            <p><strong>{{ __('word.building_number') }}</strong>
                                                : {{ $contract['building_number'] }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('word.date') }}</th>
                                                <th>{{ __('word.type') }}</th>
                                                <th>{{ __('word.amount') }}</th>
                                                <th>{{ __('word.status') }}</th>
                                                <th>{{ __('word.running_total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contract['entries'] as $entry)
                                                <tr>
                                                    <td>{{ $entry['date'] }}</td>
                                                    <td>{{ $entry['type'] }}</td>
                                                    <td>{{ number_format($entry['amount'], 0) }}</td>
                                                    <td>{{ $entry['status'] }}</td>
                                                    <td>{{ number_format($entry['running_total'], 0) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <h5>{{ __('word.total_for_contract') }}</h5>
                                    <p>{{ __('word.contract_total') }}:
                                        {{ number_format($contract['contract_total'], 0) }}</p>
                                    <!-- Display contract total -->
                                </div>
                            </div>
                        @endforeach

                        <div class="card mt-4">
                            <div class="card-body">
                                <h4>{{ __('word.grand_total') }}: {{ number_format($grandTotal, 0) }}</h4>
                                <!-- Display grand total -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
