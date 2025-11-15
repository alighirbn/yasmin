<x-app-layout>
    <x-slot name="header">
        @include('contract.nav.navigation')
        @include('service.nav.navigation')

        <style>
            /* ==========================================
               THEME COLORS
            ========================================== */
            :root {
                --gold-primary: #b38a4c;
                --gold-secondary: #c99b4f;
                --beige-bg: #f8f2ea;
                --border-color: #e0d6c6;
                --dark-text: #2f2f2f;
            }

            body {
                font-family: "Cairo", sans-serif;
            }

            .bg-custom {
                background: linear-gradient(180deg, #fffdf9 0%, #f9f5ee 50%, #f3ede2 100%);
                min-height: 100vh;
            }

            /* BUTTONS */
            .btn-custom {
                display: inline-block;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                color: #fff;
                border: none;
                transition: .25s;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .1);
            }

            .btn-custom:hover {
                transform: translateY(-2px);
                opacity: .9;
            }

            .btn-custom-back {
                background: #6c757d;
            }

            .btn-custom-print {
                background: var(--gold-primary);
                color: #fff;
            }

            .btn-custom-excel {
                background: #007bff;
            }

            .btn-custom-show {
                background: #17a2b8;
            }

            .btn-custom-statement {
                background: #ffc107;
                color: #000;
            }

            .btn-custom-delete {
                background: #dc3545;
            }

            /* CARDS & FILTERS */
            .card {
                border-radius: 10px;
                border: 1px solid var(--border-color);
                box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            }

            .card-body {
                background: #fffdf9;
            }

            .form-label {
                font-weight: 600;
                color: var(--dark-text);
            }

            .form-label i {
                margin-left: 4px;
                color: var(--gold-primary);
            }

            .form-select,
            .form-control {
                border: 1px solid var(--border-color);
                border-radius: 6px;
            }

            /* TABLE */
            table.contract-table {
                border-collapse: collapse;
                width: 100%;
                background: #fff;
                border-radius: 8px;
                overflow: hidden;
            }

            table.contract-table th,
            table.contract-table td {
                border: 1px solid var(--border-color);
                text-align: center;
                padding: 8px;
                color: var(--dark-text);
            }

            table.contract-table th {
                background: var(--beige-bg);
                color: var(--dark-text);
                font-weight: 700;
                font-size: 15px;
            }

            table.contract-table th a {
                color: var(--dark-text);
                text-decoration: none;
            }

            table.contract-table th a:hover {
                text-decoration: underline;
            }

            table.contract-table tr:nth-child(even) {
                background: #fcf9f5;
            }

            table.contract-table tr:hover {
                background: #f3ede2;
            }

            .pagination {
                justify-content: center;
            }

            @page {
                size: A4 landscape;
                margin: 5mm;
            }

            @media print {
                .no-print {
                    display: none !important;
                }

                table.contract-table th {
                    font-size: 13px;
                }

                table.contract-table td {
                    font-size: 12px;
                }
            }

            .summary-card {
                border-radius: 10px;
                color: #fff;
                padding: 20px;
                text-align: center;
                margin-bottom: 15px;
                font-weight: bold;
            }

            .summary-total {
                background: var(--gold-primary);
            }

            .summary-count {
                background: #28a745;
            }
        </style>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- FLASH MESSAGES --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- HEADER BUTTONS --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                        <a href="{{ url()->previous() }}" class="btn-custom btn-custom-back"><i
                                class="fas fa-arrow-left"></i> رجوع</a>
                        <div>
                            <button onclick="exportToExcel()" class="btn-custom btn-custom-excel"><i
                                    class="fas fa-file-excel"></i> Excel</button>
                        </div>
                    </div>

                    {{-- FILTERS --}}
                    <form method="GET" action="{{ route('contract.index') }}" class="no-print mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">

                                    {{-- Contract ID --}}
                                    <div class="col-md-2">
                                        <label class="form-label"><i class="fas fa-file-contract"></i> رقم العقد</label>
                                        <input type="text" name="contract_id" class="form-control"
                                            value="{{ $contractId }}" placeholder="رقم العقد">
                                    </div>

                                    {{-- Customer Name --}}
                                    <div class="col-md-3">
                                        <label class="form-label"><i class="fas fa-user"></i> اسم الزبون</label>
                                        <input type="text" name="customer_name" class="form-control"
                                            value="{{ $customerName }}" placeholder="اسم الزبون">
                                    </div>

                                    {{-- NEW: Phone --}}
                                    <div class="col-md-2">
                                        <label class="form-label"><i class="fas fa-phone"></i> رقم الهاتف</label>
                                        <input type="text" name="customer_phone" class="form-control"
                                            value="{{ request('customer_phone') }}" placeholder="06-...">
                                    </div>

                                    {{-- Stage --}}
                                    <div class="col-md-2">
                                        <label class="form-label"><i class="fas fa-layer-group"></i> المرحلة</label>
                                        <select name="stage" class="form-select">
                                            <option value="">كل المراحل</option>
                                            <option value="temporary"
                                                {{ $selectedStage == 'temporary' ? 'selected' : '' }}>حجز أولي
                                            </option>
                                            <option value="accepted"
                                                {{ $selectedStage == 'accepted' ? 'selected' : '' }}>مقبول</option>
                                            <option
                                                value="authenticated"{{ $selectedStage == 'authenticated' ? 'selected' : '' }}>
                                                مصادق</option>
                                            <option value="terminated"
                                                {{ $selectedStage == 'terminated' ? 'selected' : '' }}>مفسوخ</option>
                                        </select>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="col-md-3 d-flex gap-2">
                                        <button type="submit" class="btn-custom btn-custom-print flex-fill"><i
                                                class="fas fa-filter"></i> تطبيق</button>
                                        <a href="{{ route('contract.index') }}"
                                            class="btn-custom btn-custom-back flex-fill"><i class="fas fa-undo"></i>
                                            مسح</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- SORT HELPER --}}
                    @php
                        function sortLink($label, $column, $sort, $direction)
                        {
                            $newDir = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';
                            $icon = $sort === $column ? ($direction === 'asc' ? ' Up Arrow' : ' Down Arrow') : '';
                            $query = array_merge(request()->all(), ['sort' => $column, 'direction' => $newDir]);
                            $url = request()->url() . '?' . http_build_query($query);
                            return "<a href='$url'>$label$icon</a>";
                        }
                    @endphp

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="contract-table print-container a4-width mx-auto">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الوظائف</th>
                                    <th>{!! sortLink('رقم العقد', 'contracts.id', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('اسم الزبون', 'customers.customer_full_name', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('رقم الهاتف', 'customers.customer_phone', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('رقم المبنى', 'buildings.building_number', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('مبلغ العقد', 'contracts.contract_amount', $sort, $direction) !!}</th>
                                    <th>طريقة الدفع</th>
                                    <th>{!! sortLink('المرحلة', 'contracts.stage', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('تاريخ العقد', 'contracts.contract_date', $sort, $direction) !!}</th>
                                    <th>آخر دفعة (تاريخ)</th>
                                    <th>آخر دفعة (مبلغ)</th>
                                    <th>اسم القسط</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contracts as $index => $contract)
                                    <tr>
                                        <td>{{ $contracts->firstItem() + $index }}</td>

                                        {{-- Buttons --}}
                                        <td>
                                            <div class="flex justify-center flex-wrap">

                                                @can('contract-show')
                                                    <a href="{{ route('contract.show', $contract->url_address) }}"
                                                        class="m-1 btn-custom btn-custom-show">
                                                        {{ __('word.view') }}
                                                    </a>
                                                @endcan

                                                @can('contract-statement')
                                                    <a href="{{ route('contract.statement', $contract->url_address) }}"
                                                        class="m-1 btn-custom btn-custom-statement">
                                                        {{ __('word.statement') }}
                                                    </a>
                                                @endcan

                                                @can('contract-delete')
                                                    <form action="{{ route('contract.destroy', $contract->url_address) }}"
                                                        method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="m-1 btn-custom btn-custom-delete">
                                                            {{ __('word.delete') }}
                                                        </button>
                                                    </form>
                                                @endcan

                                            </div>

                                        </td>

                                        <td>{{ $contract->id }}</td>
                                        <td>{{ $contract->customer_name ?? '-' }}</td>
                                        <td>{{ $contract->customer_phone ?? '-' }}</td>
                                        <td>{{ $contract->building_no ?? '-' }}</td>

                                        <td>{{ number_format($contract->contract_amount) }} IQD</td>

                                        <td>{{ $contract->payment_method_name ?? '-' }}</td>

                                        <td>{{ __('word.' . $contract->stage) }}</td>

                                        <td>{{ $contract->contract_date ? \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') : '-' }}
                                        </td>

                                        {{-- Last Payment Date --}}
                                        <td>{{ $contract->last_payment_date ? \Carbon\Carbon::parse($contract->last_payment_date)->format('Y-m-d') : '-' }}
                                        </td>

                                        {{-- Last Payment Amount --}}
                                        <td>{{ $contract->last_payment_amount ? number_format($contract->last_payment_amount) . ' IQD' : '-' }}
                                        </td>

                                        {{-- Last Installment Name --}}
                                        <td>{{ $contract->last_installment_name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">لا توجد عقود</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $contracts->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STAGE TRANSLATIONS FOR EXCEL --}}
    <script>
        const stageTranslations = {
            'temporary': @json(__('word.temporary')),
            'accepted': @json(__('word.accepted')),
            'authenticated': @json(__('word.authenticated')),
            'terminated': @json(__('word.terminated')),
        };
    </script>

    {{-- EXCEL EXPORT (now includes new columns) --}}
    <script>
        function exportToExcel() {
            const rows = @json($allContracts);
            if (!rows.length) return alert('لا توجد بيانات لتصديرها.');

            let table = `<table border='1'>
    <tr>
        <th>#</th>
        <th>رقم العقد</th>
        <th>اسم الزبون</th>
        <th>رقم الهاتف</th>
        <th>رقم المبنى</th>
        <th>مبلغ العقد</th>
        <th>طريقة الدفع</th>
        <th>المرحلة</th>
        <th>تاريخ العقد</th>
        <th>آخر دفعة (تاريخ)</th>
        <th>آخر دفعة (مبلغ)</th>
        <th>اسم القسط</th>
    </tr>`;

            rows.forEach((c, i) => {
                table += `<tr>
            <td>${i + 1}</td>
            <td>${c.id}</td>
            <td>${c.customer_name ?? '-'}</td>
            <td>${c.customer_phone ?? '-'}</td>
            <td>${c.building_no ?? '-'}</td>
            <td>${Number(c.contract_amount).toLocaleString()} IQD</td>
            <td>${c.payment_method_name ?? '-'}</td>
            <td>${c.stage}</td>
            <td>${c.contract_date ?? '-'}</td>
            <td>${c.last_payment_date ?? '-'}</td>
            <td>${c.last_payment_amount ? Number(c.last_payment_amount).toLocaleString() + ' IQD' : '-'}</td>
            <td>${c.last_installment_name ?? '-'}</td>
        </tr>`;
            });

            table += '</table>';

            const blob = new Blob([table], {
                type: 'application/vnd.ms-excel'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'contracts_' + new Date().getTime() + '.xls';
            link.click();
        }
    </script>
</x-app-layout>
