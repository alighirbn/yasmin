<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('payment.nav.navigation')
            @include('expense.nav.navigation')
            @include('cash_account.nav.navigation')
            @include('cash_transfer.nav.navigation')
        </div>

        <!-- Add this inline CSS to handle hiding the "Actions" column during printing -->
        <style>
            @media print {
                .no-print {
                    display: none;
                }
            }
        </style>
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
                    <div class="a4-width mx-auto">
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
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                {!! QrCode::size(90)->generate($cashAccount->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="height: 90px; width: auto;">
                            </div>
                        </div>
                        <h2 class="text-xl font-semibold mb-2">{{ __('كشف الحساب للصندوق ') }}
                            {{ $cashAccount->account_name }}
                        </h2>

                        <p>الرصيد الحالي: {{ number_format($cashAccount->balance, 0) }} دينار </p>

                        <!-- Add Period Display -->
                        @if (request('start_date') || request('end_date'))
                            <p>
                                الفترة من
                                {{ request('start_date') ? request('start_date') : '---' }}
                                إلى
                                {{ request('end_date') ? request('end_date') : '---' }}
                            </p>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="no-print">الإجراءات</th> <!-- Hide this during print -->
                                    <th>التاريخ</th>
                                    <th>التفاصيل</th>
                                    <th>دائن</th>
                                    <th>مدين</th>
                                    <th>الرصيد </th>
                                    <th>الملاحظات </th>
                                </tr>
                            </thead>
                            <tbody>
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
                                                @endif
                                            </td>
                                            <td>{{ $transaction->transaction_date }}</td>
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
                                                    @else
                                                    @endif
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Expense')
                                                    عدد الصرف: {{ $transaction->transactionable->id }}
                                                    ,
                                                    النوع:
                                                    {{ $transaction->transactionable->expense_type->expense_type }}
                                                @endif
                                            </td>
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
                                            <td>{{ number_format($transaction->running_balance, 0) }}</td>
                                            <td>
                                                @if ($transaction->transactionable_type === 'App\Models\Payment\Payment')
                                                    {{ $transaction->transactionable->payment_note }}
                                                @elseif($transaction->transactionable_type === 'App\Models\Cash\Expense')
                                                    {{ $transaction->transactionable->expense_note }}
                                                @endif
                                            </td>

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
