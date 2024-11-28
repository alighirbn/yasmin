<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
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
                    <div class="a4-width mx-auto p-4">
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('report.general_report') }}">
                            <!-- Customer Filter -->
                            <label for="contract_customer_id"> {{ __('word.name') }} :</label>
                            <select name="contract_customer_id" class="js-example-basic-single">
                                <option value="">{{ __('word.show_all') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        @if (request('contract_customer_id') == $customer->id) selected @endif>
                                        {{ $customer->customer_full_name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Date Filters -->
                            <label for="start_date"> {{ __('word.date_from') }} :</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}">

                            <label for="end_date"> {{ __('word.date_to') }} :</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}">

                            <button type="submit" class="btn btn-custom-show "> {{ __('word.filter') }}</button>
                        </form>
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

                        @if (request('contract_customer_id'))
                            <p>
                                {{ $customers->find(request('contract_customer_id'))->customer_full_name }}
                            </p>
                        @endif

                        @if (isset($contracts) && isset($payments))
                            <h2> {{ __('word.contract') }}</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> {{ __('word.contract_id') }}</th>
                                        <th> {{ __('word.contract_date') }}</th>
                                        <th> {{ __('word.building_number') }}</th>
                                        <th> {{ __('word.building_area') }}</th>
                                        <th> {{ __('word.customer_full_name') }}</th>
                                        <th> {{ __('word.contract_amount') }}</th>
                                        <th> {{ __('word.stage') }}</th>
                                        <th> {{ __('word.contract_note') }}</th>
                                        <th> {{ __('word.user_create') }}</th>
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
                                            <td>{{ __('word.' . $contract->stage) }}</td>
                                            <td>{{ $contract->contract_note }}</td>
                                            <td>{{ $contract->updated_at ? $contract->updated_at : $contract->created_at }}
                                                {{ $contract->user_update ? $contract->user_update->name : $contract->user_create->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right"><strong> {{ __('word.total') }}
                                                :</strong></td>
                                        <td>{{ number_format($totalContractAmount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <h2> {{ __('word.payment') }}</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> {{ __('word.payment_id') }}</th>
                                        <th> {{ __('word.payment_date') }}</th>
                                        <th> {{ __('word.building_number') }} </th>
                                        <th> {{ __('word.building_area') }}</th>
                                        <th> {{ __('word.customer_full_name') }} </th>
                                        <th> {{ __('word.payment_amount') }}</th>
                                        <th> {{ __('word.payment_note') }}</th>
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
                                        <td colspan="5" class="text-right"><strong> {{ __('word.total') }}
                                                :</strong></td>
                                        <td>{{ number_format($totalPaymentAmount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        @endif

                        <!-- Only display expenses if contract_customer_id is not set -->
                        @if (!request('contract_customer_id') && isset($expenses))
                            <h2> {{ __('word.expense') }}</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th> {{ __('word.expense_id') }}</th>
                                        <th> {{ __('word.expense_date') }}</th>
                                        <th> {{ __('word.expense_type_id') }}</th>
                                        <th> {{ __('word.expense_amount') }}</th>
                                        <th> {{ __('word.expense_note') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->id }}</td>
                                            <td>{{ $expense->expense_date }}</td>
                                            <td>{{ $expense->expense_type->expense_type }}</td>
                                            <td>{{ number_format($expense->expense_amount, 0) }}</td>
                                            <td>{{ $expense->expense_note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong> {{ __('word.total') }}
                                                :</strong></td>
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

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
</x-app-layout>
