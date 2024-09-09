<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto  lg:px-8">
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
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($contract->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract->building->building_number, 'C39') }}"
                                    alt="barcode" />
                                <p><strong>{{ __('رقم العقد:') }}</strong>
                                    {{ $contract->id }}
                                </p>
                                <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $contract->contract_date }}</p>

                            </div>
                        </div>

                        <div style="text-align: center; margin: 1rem auto; font-size: 1rem;">
                            <p><strong>التناقلات الخاصة بالعقد </strong>
                            </p>
                        </div>

                        @if ($transfers->isEmpty())
                            <p>لا توجد اي تناقلات خاصة بالعقد .</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('word.transfer_id') }}</th>
                                        <th>{{ __('word.transfer_date') }}</th>
                                        <th>{{ __('word.oldcustomer') }}</th>
                                        <th>{{ __('word.newcustomer') }}</th>
                                        <th>{{ __('word.transfer_amount') }}</th>
                                        <th>{{ __('word.transfer_approve') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transfers as $transfer)
                                        <tr>
                                            <td>{{ $transfer->id }}</td>
                                            <td>{{ $transfer->transfer_date }}</td>
                                            <td>{{ $transfer->oldCustomer->customer_full_name }}</td>
                                            <td>{{ $transfer->newCustomer->customer_full_name }}</td>
                                            <td>{{ number_format($transfer->transfer_amount, 0) . ' دينار' }}</td>
                                            <td>{{ $transfer->approved ? 'نعم' : 'كلا' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
