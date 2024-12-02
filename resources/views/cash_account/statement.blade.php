<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('payment.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('cash_account.nav.navigation')
            @include('cash_transfer.nav.navigation')
        </div>
        <style>
            /* Table styling */
            .statement-table {
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
                color: black;
            }

            .statement-table th,
            .statement-table td {
                border: 2px solid #444;
                /* Make border thicker */
                padding: 5px;
                /* Increase padding */
                text-align: center;
                font-size: 16px;
                /* Increase font size */
            }

            .statement-table th {
                background-color: #f4f4f4;
                font-weight: bold;
                font-size: 24px;
                /* Larger font for headers */
                border: 2px solid #444;
                /* Extra thick border for header */
                text-align: center;
                vertical-align: middle;
            }

            .table-header {
                text-align: center;
                margin-bottom: 20px;
                color: black;
            }

            .table-header h2 {
                font-size: 22px;
                /* Larger font for header title */
                margin: 0;
            }

            .table-header p {
                margin: 5px 0;
                font-size: 20px;
                /* Increase font size for subtitle */
            }

            .table-container {
                margin: 0 auto;
                width: 95%;
                color: black;
            }

            /* Landscape orientation for printing */
            @page {
                size: A4 landscape;
                margin: 5mm;
            }

            /* Hide buttons during printing */
            @media print {
                .no-print {
                    display: none;
                }

                .statement-table th {
                    font-size: 16px;
                    /* Adjust font size for printing */
                }

                .statement-table td {
                    font-size: 14px;
                    /* Adjust font size for printing */
                }
            }

            /* Print button styling */
            .print-button {
                display: block;
                margin: 20px auto;
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                text-align: center;
                border-radius: 5px;
                text-decoration: none;
                font-size: 16px;
                font-weight: bold;
            }

            .print-button:hover {
                background-color: #45a049;
            }
        </style>

        <!-- Add this inline CSS to handle hiding the "Actions" column during printing -->

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class=" mx-auto">
                        <!-- Date Filter Form -->
                        <form method="GET" action="{{ route('cash_account.statement', $cashAccount->url_address) }}"
                            class="form-inline mb-4">
                            <div class="form-group">
                                <div class="flex">
                                    <label for="start_date">من:</label>
                                    <input type="date" name="start_date" class="form-control mx-2"
                                        value="{{ request('start_date') }}">

                                    <label for="end_date">الى:</label>
                                    <input type="date" name="end_date" class="form-control mx-2"
                                        value="{{ request('end_date') }}">
                                    <button type="submit" class="btn btn-custom-show">بحث</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="print-container  mx-auto bg-white">

                        <!-- Header -->
                        <div class="table-header">

                            <h2>كشف تفصيلي لحساب {{ $cashAccount->account_name }} </h2>
                            <p>الرصيد: {{ number_format($cashAccount->balance, 0) }} دينار عراقي</p>
                            <p> الفترة من :{{ request('start_date') ? request('start_date') : '---' }} إلى
                                {{ request('end_date') ? request('end_date') : '---' }}</p>
                        </div>

                        <table class="table table-bordered statement-table">
                            <thead>
                                <tr>
                                    <th class="no-print" rowspan="2">الإجراءات</th> <!-- Stays single-row -->
                                    <th rowspan="2">ت</th>
                                    <th rowspan="2">التاريــخ</th>
                                    <th colspan="2">الحركــات</th> <!-- Movement under this -->
                                    <th rowspan="2">التفاصيــل</th>
                                    <th rowspan="2">نوع النشــاط</th>
                                    <th rowspan="2">الرصيــد</th>
                                </tr>
                                <tr>
                                    <th>دائــن</th> <!-- Credit -->
                                    <th>مديــن</th> <!-- Debit -->
                                </tr>
                            </thead>
                            <tbody>
                                @php $serial = 1; @endphp
                                @foreach ($transactions as $transaction)
                                    <!-- Filter transactions based on the selected period -->
                                    @if (
                                        (!isset($startDate) || $transaction->transaction_date >= $startDate) &&
                                            (!isset($endDate) || $transaction->transaction_date <= $endDate))
                                        <tr>
                                            <td class="no-print">
                                                <!-- Button to show polymorphic model details -->
                                                @if ($transaction->transactionable_type === 'App\Models\Payment\Payment')
                                                    <a href="{{ route('payment.show', $transaction->transactionable->url_address) }}"
                                                        class="btn btn-custom-show">
                                                        {{ __('عرض ') }}
                                                    </a>
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Expense')
                                                    <a href="{{ route('expense.show', $transaction->transactionable->url_address) }}"
                                                        class="btn btn-custom-show">
                                                        {{ __('عرض ') }}
                                                    </a>
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Income')
                                                    <a href="{{ route('income.show', $transaction->transactionable->url_address) }}"
                                                        class="btn btn-custom-show">
                                                        {{ __('عرض ') }}
                                                    </a>
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\CashTransfer')
                                                    <a href="{{ route('cash_transfer.show', $transaction->transactionable->url_address) }}"
                                                        class="btn btn-custom-show">
                                                        {{ __('عرض ') }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $serial++ }}</td>
                                            <td>{{ $transaction->transaction_date }}</td>
                                            <td>
                                                @if ($transaction->transaction_type === 'credit')
                                                    {{ number_format($transaction->transaction_amount, 0) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->transaction_type === 'debit')
                                                    {{ number_format($transaction->transaction_amount, 0) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->transactionable_type === 'App\Models\Payment\Payment')
                                                    عدد الدفعة: {{ $transaction->transactionable->id }}
                                                    ,
                                                    الاسم:
                                                    {{ $transaction->transactionable->contract->customer->customer_full_name }}
                                                    ,
                                                    @if ($transaction->transactionable->contract_installment)
                                                        الدفعة:
                                                        {{ $transaction->transactionable->contract_installment->installment->installment_name }}
                                                        ,
                                                    @else
                                                    @endif
                                                    {{ $transaction->transactionable->contract->building->building_number }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Expense')
                                                    عدد الصرف: {{ $transaction->transactionable->id }}
                                                    ,
                                                    النوع:
                                                    {{ $transaction->transactionable->expense_type->expense_type }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Income')
                                                    عدد الايراد: {{ $transaction->transactionable->id }}
                                                    ,
                                                    النوع:
                                                    {{ $transaction->transactionable->income_type->income_type }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\CashTransfer')
                                                    تحويل مبلغ من:
                                                    {{ $transaction->transactionable->fromAccount->account_name }}
                                                    ,
                                                    الى:
                                                    {{ $transaction->transactionable->toAccount->account_name }}
                                                @endif
                                                --
                                                @if ($transaction->transactionable_type === 'App\Models\Payment\Payment')
                                                    {{ $transaction->transactionable->payment_note }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Expense')
                                                    {{ $transaction->transactionable->expense_note }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Income')
                                                    {{ $transaction->transactionable->income_note }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\CashTransfer')
                                                    {{ $transaction->transactionable->transfer_note }}
                                                @endif
                                            </td>
                                            <td>{{ $transaction->transaction_type === 'credit' ? 'قبض نقدي' : 'صرف نقدي' }}
                                            </td>

                                            <td>{{ number_format($transaction->running_balance, 0) }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
