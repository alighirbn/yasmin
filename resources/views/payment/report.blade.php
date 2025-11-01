<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
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
                    <div class="print-container  mx-auto bg-white">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">تقرير الدفعات</h3>
                                    </div>

                                    <!-- Filter Form -->
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('payment.report') }}" class="row g-3">
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">من تاريخ</label>
                                                <input type="date" class="form-control" id="start_date"
                                                    name="start_date" value="{{ $startDate }}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                                <input type="date" class="form-control" id="end_date"
                                                    name="end_date" value="{{ $endDate }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="cash_account_id" class="form-label">الحساب النقدي</label>
                                                <select class="form-select" id="cash_account_id" name="cash_account_id">
                                                    <option value="">جميع الحسابات</option>
                                                    @foreach ($cash_accounts as $account)
                                                        <option value="{{ $account->id }}"
                                                            {{ $cashAccountId == $account->id ? 'selected' : '' }}>
                                                            {{ $account->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-filter"></i> فلترة
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">إجمالي المبلغ</h5>
                                        <h2 class="mb-0">{{ number_format($totalPayments, 0) }} IQD</h2>
                                        <small>من {{ $paymentsCount }} دفعة</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">عدد الدفعات</h5>
                                        <h2 class="mb-0">{{ $paymentsCount }}</h2>
                                        <small>دفعة موافق عليها</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary by Cash Account -->
                        @if ($paymentsByCashAccount->count() > 1)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">الملخص حسب الحساب النقدي</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>الحساب النقدي</th>
                                                            <th>عدد الدفعات</th>
                                                            <th>إجمالي المبلغ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($paymentsByCashAccount as $summary)
                                                            <tr>
                                                                <td>{{ $summary['cash_account']->account_name ?? 'غير محدد' }}
                                                                </td>
                                                                <td>{{ $summary['count'] }}</td>
                                                                <td>{{ number_format($summary['total'], 0) }} IQD</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="table-active">
                                                            <th>الإجمالي</th>
                                                            <th>{{ $paymentsCount }}</th>
                                                            <th>{{ number_format($totalPayments, 0) }} IQD</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- User Details and Payments Table -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">تفاصيل الدفعات</h4>
                                        <div>
                                            <button onclick="window.print()" class="btn btn-secondary">
                                                <i class="fas fa-print"></i> طباعة
                                            </button>
                                            <button onclick="exportToExcel()" class="btn btn-success">
                                                <i class="fas fa-file-excel"></i> تصدير Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if ($payments->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="paymentsTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>التاريخ</th>
                                                            <th>العقد</th>
                                                            <th>الزبون</th>
                                                            <th>المبنى</th>
                                                            <th>القسط</th>
                                                            <th>المبلغ</th>
                                                            <th>الحساب النقدي</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($payments as $index => $payment)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                                                <td>
                                                                    <a
                                                                        href="{{ route('contract.show', $payment->contract->url_address) }}">
                                                                        {{ $payment->contract->id ?? 'N/A' }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ $payment->contract->customer->customer_full_name ?? 'N/A' }}
                                                                </td>
                                                                <td>
                                                                    {{ $payment->contract->building->building_number ?? 'N/A' }}

                                                                </td>
                                                                <td>
                                                                    @if ($payment->contract_installment)
                                                                        {{ $payment->contract_installment->installment->installment_name ?? 'N/A' }}
                                                                    @else
                                                                        دفعة
                                                                    @endif
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($payment->payment_amount, 0) }}
                                                                    IQD
                                                                </td>
                                                                <td>{{ $payment->cash_account->account_name ?? 'غير محدد' }}
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <th colspan="6" class="text-end">الإجمالي:</th>
                                                            <th class="text-end">
                                                                {{ number_format($totalPayments, 0) }} IQD</th>
                                                            <th colspan="2"></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <div class="alert alert-info text-center">
                                                <i class="fas fa-info-circle"></i>
                                                لا توجد دفعات تطابق معايير البحث
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        function exportToExcel() {
            // Get table
            var table = document.getElementById('paymentsTable');
            var html = table.outerHTML;

            // Create download link
            var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var link = document.createElement('a');
            link.href = url;
            link.download = 'payment_report_' + new Date().getTime() + '.xls';
            link.click();
        }
    </script>

</x-app-layout>
