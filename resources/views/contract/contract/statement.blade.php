<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
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
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan
                        @can('contract-update')
                            <a href="{{ route('contract.edit', $contract->url_address) }}" class="btn btn-custom-edit">
                                {{ __('word.contract_edit') }}
                            </a>
                        @endcan
                        <button id="print" class="btn btn-custom-statement" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container">
                        <div class="flex">
                            <div class=" mx-4 my-4 w-full ">
                                <h2 class="text-xl font-semibold mb-2">{{ __('كشف الحساب للعقد رقم') }}
                                    #{{ $contract->id }}
                                </h2>
                                <div class="mb-6">
                                    <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $contract->contract_date }}</p>
                                    <p><strong>{{ __('مبلغ العقد:') }}</strong>
                                        {{ number_format($contract->contract_amount, 0) }}
                                    </p>
                                    <p><strong>{{ __('الاسم :') }}</strong>
                                        {{ $contract->customer->customer_full_name }}
                                    </p>
                                    <p><strong>{{ __('رقم العقار:') }}</strong>
                                        {{ $contract->building->building_number }}
                                    </p>
                                    <p><strong>{{ __('إجمالي الأقساط:') }}</strong>
                                        {{ number_format($total_installments, 0) }}
                                    </p>
                                    <p><strong>{{ __('إجمالي المدفوعات:') }}</strong>
                                        {{ number_format($total_payments, 0) }}</p>
                                    <p><strong>{{ __('إجمالي الخدمات:') }}</strong>
                                        {{ number_format($total_services, 0) }}</p>
                                    <p><strong>{{ __('المبلغ الكلي:') }}</strong>
                                        {{ number_format($outstanding_amount, 0) }}
                                    </p>
                                </div>
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo" class="h-30 mr-4">
                            </div>

                        </div>
                        @php
                            // Merge and sort data
                            $items = collect();

                            foreach ($contract->contract_installments as $installment) {
                                $items->push([
                                    'date' => $installment->installment_date,
                                    'description' => __('قسط'),
                                    'credit' => $installment->installment_amount,
                                    'debit' => 0,
                                    'note' => $installment->installment->installment_name,
                                ]);
                            }

                            foreach ($contract->payments as $payment) {
                                $items->push([
                                    'date' => $payment->payment_date,
                                    'description' => __('مدفوعية بالعدد') . ' ' . $payment->id,
                                    'credit' => 0,
                                    'debit' => $payment->payment_amount,
                                    'note' =>
                                        $payment->payment_note .
                                        ' ' .
                                        $payment->contract_installment->installment->installment_name,
                                ]);
                            }

                            foreach ($contract->services as $service) {
                                $items->push([
                                    'date' => $service->service_date,
                                    'description' => __('خدمة بالعدد') . ' ' . $service->id,
                                    'credit' => $service->service_amount,
                                    'debit' => 0,
                                    'note' => $service->service_note,
                                ]);
                            }

                            $sortedItems = $items->sortBy('date');

                            // Calculate running total
                            $runningTotal = 0;
                        @endphp

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('التاريخ') }}</th>
                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('الوصف') }}</th>

                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('المدين') }}</th>
                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('الدائن') }}</th>
                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('الرصيد الجاري') }}</th>
                                    <th class="px-6 py-3 text-right  font-medium uppercase tracking-wider">
                                        {{ __('الملاحظات') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach ($sortedItems as $item)
                                    @php
                                        $runningTotal += $item['credit'] - $item['debit'];
                                    @endphp
                                    <tr>
                                        <td class="  text-gray-700">{{ $item['date'] }}</td>
                                        <td class="  text-gray-700">{{ $item['description'] }}</td>

                                        <td class="  text-gray-700 text-right">
                                            {{ number_format($item['credit'], 0) }}</td>
                                        <td class="  text-gray-700 text-right">
                                            {{ number_format($item['debit'], 0) }}</td>
                                        <td class="  text-gray-700 text-right">
                                            {{ number_format($runningTotal, 0) }}</td>
                                        <td class="  text-gray-700">{{ $item['note'] }}</td>
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
