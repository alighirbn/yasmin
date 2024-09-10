<x-app-layout>
    <x-slot name="header">
        <!-- App CSS -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <style>
            .table tfoot td {
                font-weight: bold;
                background-color: #f3f4f6;
            }

            .customer-section {
                margin-bottom: 20px;
            }

            .customer-total,
            .grand-total {
                margin-top: 10px;
            }

            .font-bold {
                font-weight: bold;
            }

            .striped-row:nth-child(even) {
                background-color: #f3f4f6;
            }

            .customer-section+hr {
                border: none;
                border-top: 1px solid #000000;
                margin: 20px 0;
            }
        </style>
        @include('contract.nav.navigation')
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

                    <div class="print-container a4-width mx-auto bg-white">
                        <div style="text-align: center; margin: 1rem auto; font-size: 1rem;">
                            <p><strong>الدفعات المستحقة</strong>
                            </p>
                        </div>

                        @php $grandTotal = 0; @endphp

                        @if ($dueInstallments->isEmpty())
                            <p>لا توجد دفعات مستحقة</p>
                        @else
                            @foreach ($dueInstallments as $customerId => $contracts)
                                @php
                                    $customer = \App\Models\Customer\Customer::find($customerId);
                                    $customerTotal = 0;
                                    $customerRowCounter = 0;
                                @endphp

                                <div class="customer-section">
                                    <p class="font-bold">{{ __('word.customer_full_name') }}:
                                        {{ $customer->customer_full_name }}</p>

                                    @foreach ($contracts as $contractId => $installments)
                                        @php
                                            $contract = \App\Models\Contract\Contract::find($contractId);
                                            $contractTotal = 0;
                                        @endphp

                                        <div class="flex">
                                            <div class="mx-2 my-2 w-full">
                                                {!! QrCode::size(90)->generate($contract->id) !!}
                                            </div>
                                            <div class="mx-2 my-2 w-full">
                                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract->building->building_number, 'C39') }}"
                                                    alt="barcode" />
                                                <p class="font-bold">عدد العقد: {{ $contract->id }}</p>
                                                <p class="font-bold">تاريخ العقد: {{ $contract->contract_date }}</p>
                                            </div>
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('word.installment_name') }}</th>
                                                    <th>{{ __('word.installment_amount') }}</th>
                                                    <th>{{ __('word.installment_date') }}</th>
                                                    <th>{{ __('word.building_number') }}</th>
                                                    <th>{{ __('word.payment_status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($installments->chunk(2) as $chunk)
                                                    @foreach ($chunk as $index => $installment)
                                                        @php
                                                            $contractTotal += $installment->installment_amount;
                                                            $customerTotal += $installment->installment_amount;
                                                            $grandTotal += $installment->installment_amount;
                                                            $rowClass =
                                                                $customerRowCounter++ % 2 == 0
                                                                    ? 'bg-gray-100'
                                                                    : 'bg-white';
                                                        @endphp
                                                        <tr class="{{ $rowClass }}">
                                                            <td>{{ $installment->installment->installment_name }}</td>
                                                            <td>{{ number_format($installment->installment_amount, 0) }}
                                                            </td>
                                                            <td>{{ $installment->installment_date }}</td>
                                                            <td>{{ $installment->contract->building->building_number }}
                                                            </td>
                                                            <td>
                                                                @if (is_null($installment->payment))
                                                                    لم تدفع
                                                                @elseif(!$installment->payment->approved)
                                                                    لم تتم الموافقة على الدفع
                                                                @else
                                                                    مدفوعة
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-right font-bold">
                                                        مجموع دفعات العقد المستحقة:
                                                        {{ number_format($contractTotal, 0) }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endforeach

                                    <div class="customer-total">
                                        <p class="font-bold">
                                            مجموع المبالغ المستحقة على الزبون: {{ number_format($customerTotal, 0) }}
                                        </p>
                                    </div>
                                </div>

                                @if (!$loop->last)
                                    <hr />
                                @endif
                            @endforeach

                            <div class="grand-total">
                                <p class="font-bold">
                                    المجموع الكلي: {{ number_format($grandTotal, 0) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
