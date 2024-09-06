<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <style>
            .table tfoot td {
                font-weight: bold;
                background-color: #f3f4f6;
            }

            .customer-section {
                margin-bottom: 20px;
                /* Space between each customer section */
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
                /* Space around the horizontal line */
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
                        <p class="font-bold">الدفعات المستحقة</p>

                        @php
                            $grandTotal = 0;
                        @endphp

                        @if ($dueInstallments->isEmpty())
                            <p>لا توجد دفعات مستحقة</p>
                        @else
                            @php
                                $customerRowCounter = 0;
                            @endphp

                            @foreach ($dueInstallments as $customerId => $contracts)
                                @php
                                    $customer = \App\Models\Customer\Customer::find($customerId);
                                    $customerTotal = 0;
                                @endphp

                                <div class="customer-section">
                                    <p class="font-bold">الاسم: {{ $customer->customer_full_name }}</p>

                                    @foreach ($contracts as $contractId => $installments)
                                        @php
                                            $contract = \App\Models\Contract\Contract::find($contractId);
                                            $contractTotal = 0;
                                        @endphp

                                        <p class="font-bold">عدد العقد: {{ $contract->id }}</p>
                                        <p class="font-bold">تاريخ العقد: {{ $contract->contract_date }}</p>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th> الدفعة</th>
                                                    <th>مبلغ الدفعة</th>
                                                    <th>تأريخ استحقاق الدفعة</th>
                                                    <th>رقم العقار</th>
                                                    <th>حالة الدفع</th>
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
                                    @if (!$contract_id)
                                        <div class="customer-total">
                                            <p class="font-bold">
                                                مجموع المبالغ المستحقة على الزبون:
                                                {{ number_format($customerTotal, 0) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                @if (!$loop->last)
                                    <hr />
                                @endif
                            @endforeach

                            @if (!$contract_id)
                                <div class="grand-total">
                                    <p class="font-bold">
                                        المجموع الكلي: {{ number_format($grandTotal, 0) }}
                                    </p>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
