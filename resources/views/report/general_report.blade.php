<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
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
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                <h1 class="text-xl font-semibold mb-4"> التقرير العام</h1>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="max-width: auto; height: 90px;">
                            </div>
                        </div>
                        <h1>Contracts, Payments, and Expenses Report</h1>

                        <!-- Date Filter Form -->
                        <form method="GET" action="{{ route('report.general_report') }}">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" required>
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" required>
                            <button type="submit">Generate Report</button>
                        </form>

                        @if (isset($contracts) && isset($payments) && isset($expenses))
                            <h2>Contracts</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Contract ID</th>
                                        <th>Date</th>
                                        <th>Building number</th>
                                        <th>Building Area</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->id }}</td>
                                            <td>{{ $contract->contract_date }}</td>
                                            <td>{{ $contract->building->building_number }}</td>
                                            <td>{{ $contract->building->building_area }}</td>
                                            <td>{{ $contract->customer->customer_full_name }}</td>
                                            <td>{{ number_format($contract->contract_amount, 0) }}</td>
                                            <td>{{ $contract->contract_note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                        <td>{{ number_format($totalContractAmount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <h2>Payments</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Date</th>
                                        <th>Building number</th>
                                        <th>Building Area</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ $payment->contract->building->building_number }}</td>
                                            <td>{{ $payment->contract->building->building_area }}</td>
                                            <td>{{ $payment->contract->customer->customer_full_name }}</td>
                                            <td>{{ number_format($payment->payment_amount, 0) }}</td>
                                            <td>{{ $payment->payment_note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                        <td>{{ number_format($totalPaymentAmount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <h2>Expenses</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Expense ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->id }}</td>
                                            <td>{{ $expense->expense_date }}</td>
                                            <td>{{ number_format($expense->expense_amount, 0) }}</td>
                                            <td>{{ $expense->expense_note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                        <td>{{ number_format($totalExpenseAmount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
