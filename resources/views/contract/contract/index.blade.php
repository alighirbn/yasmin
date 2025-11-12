<x-app-layout>
    <x-slot name="header">
        @include('contract.nav.navigation')
        @include('service.nav.navigation')

        <style>
            table.contract-table {
                border-collapse: collapse;
                width: 100%;
                color: black;
            }

            table.contract-table th,
            table.contract-table td {
                border: 2px solid #444;
                text-align: center;
                padding: 6px;
                font-size: 15px;
            }

            table.contract-table th {
                background-color: #f4f4f4;
                font-weight: bold;
                font-size: 16px;
                user-select: none;
            }

            table.contract-table th a {
                color: black;
                text-decoration: none;
            }

            table.contract-table th a:hover {
                text-decoration: underline;
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
                    font-size: 14px;
                }

                table.contract-table td {
                    font-size: 12px;
                }
            }

            .btn-custom {
                display: inline-block;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 14px;
                font-weight: bold;
                text-decoration: none;
                color: white;
                transition: 0.2s;
            }

            .btn-custom-back {
                background-color: #6c757d;
            }

            .btn-custom-print {
                background-color: #4CAF50;
            }

            .btn-custom-excel {
                background-color: #007bff;
            }

            .btn-custom-show {
                background-color: #17a2b8;
            }

            .btn-custom-statement {
                background-color: #ffc107;
                color: black;
            }

            .btn-custom-delete {
                background-color: #dc3545;
            }

            .btn-custom:hover {
                opacity: 0.85;
            }

            .card {
                border-radius: 10px;
            }

            .card-body {
                background-color: #f9f9fb;
            }

            .form-label i {
                margin-left: 4px;
            }

            .summary-card {
                border-radius: 10px;
                color: white;
                padding: 20px;
                text-align: center;
                margin-bottom: 15px;
            }

            .summary-total {
                background-color: #007bff;
            }

            .summary-count {
                background-color: #28a745;
            }
        </style>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ✅ Flash messages --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- ✅ Header buttons --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                        <a href="{{ url()->previous() }}" class="btn-custom btn-custom-back">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                        <div>
                            <button onclick="window.print()" class="btn-custom btn-custom-print">
                                <i class="fas fa-print"></i> طباعة
                            </button>
                            <button onclick="exportToExcel()" class="btn-custom btn-custom-excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>

                    {{-- ✅ Filters --}}
                    <form method="GET" action="{{ route('contract.index') }}" class="no-print mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">

                                    {{-- 🔹 Contract ID --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-file-contract text-primary"></i> رقم العقد
                                        </label>
                                        <input type="text" name="contract_id" class="form-control"
                                            value="{{ $contractId }}" placeholder="اكتب رقم العقد">
                                    </div>

                                    {{-- 🔹 Customer Name --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user text-primary"></i> اسم الزبون
                                        </label>
                                        <input type="text" name="customer_name" class="form-control"
                                            value="{{ $customerName }}" placeholder="اكتب اسم الزبون أو جزء منه">
                                    </div>

                                    {{-- 🔹 Stage --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-layer-group text-primary"></i> المرحلة
                                        </label>
                                        <select name="stage" class="form-select">
                                            <option value="">كل المراحل</option>
                                            <option value="temporary"
                                                {{ $selectedStage == 'temporary' ? 'selected' : '' }}>حجز اولي</option>
                                            <option value="accepted"
                                                {{ $selectedStage == 'accepted' ? 'selected' : '' }}>مقبول</option>
                                            <option value="authenticated"
                                                {{ $selectedStage == 'authenticated' ? 'selected' : '' }}>مصادق</option>
                                            <option value="terminated"
                                                {{ $selectedStage == 'terminated' ? 'selected' : '' }}>فسخ</option>
                                        </select>
                                    </div>

                                    {{-- 🔹 Buttons --}}
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="submit" class="btn-custom btn-custom-excel flex-fill">
                                            <i class="fas fa-filter"></i> تطبيق
                                        </button>
                                        <a href="{{ route('contract.index') }}"
                                            class="btn-custom btn-custom-back flex-fill">
                                            <i class="fas fa-undo"></i> مسح
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- ✅ Table --}}
                    @php
                        function sortLink($label, $column, $sort, $direction)
                        {
                            $newDir = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';
                            $icon = '';
                            if ($sort === $column) {
                                $icon = $direction === 'asc' ? ' ▲' : ' ▼';
                            }
                            $query = array_merge(request()->all(), ['sort' => $column, 'direction' => $newDir]);
                            $url = request()->url() . '?' . http_build_query($query);
                            return "<a href='$url'>$label$icon</a>";
                        }
                    @endphp

                    <div class="table-responsive">
                        <table class="contract-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الوظائف</th>
                                    <th>{!! sortLink('رقم العقد', 'contracts.id', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('اسم الزبون', 'customers.customer_full_name', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('رقم المبنى', 'buildings.building_number', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('مبلغ العقد', 'contracts.contract_amount', $sort, $direction) !!}</th>
                                    <th>طريقة الدفع</th>
                                    <th>{!! sortLink('المرحلة', 'contracts.stage', $sort, $direction) !!}</th>
                                    <th>{!! sortLink('تاريخ العقد', 'contracts.contract_date', $sort, $direction) !!}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contracts as $index => $contract)
                                    <tr>
                                        <td>{{ $contracts->firstItem() + $index }}</td>
                                        <td>
                                            <div class="flex justify-center">
                                                @can('contract-show')
                                                    <a href="{{ route('contract.show', $contract->url_address) }}"
                                                        class="my-1 mx-1 btn btn-custom btn-custom-show">
                                                        {{ __('word.view') }}
                                                    </a>
                                                @endcan

                                                @can('contract-statement')
                                                    <a href="{{ route('contract.statement', $contract->url_address) }}"
                                                        class="my-1 mx-1 btn btn-custom btn-custom-statement">
                                                        {{ __('word.statement') }}
                                                    </a>
                                                @endcan

                                                @can('contract-delete')
                                                    <form action="{{ route('contract.destroy', $contract->url_address) }}"
                                                        method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="my-1 mx-1 btn btn-custom btn-custom-delete">
                                                            {{ __('word.delete') }}
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                        <td>{{ $contract->id }}</td>
                                        <td>{{ $contract->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $contract->building_no ?? 'N/A' }}</td>
                                        <td>{{ number_format($contract->contract_amount, 0) }} IQD</td>
                                        <td>{{ $contract->payment_method_name ?? 'N/A' }}</td>
                                        <td>{{ __('word.' . $contract->stage) }}</td>
                                        <td>{{ $contract->contract_date ? \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') : '-' }}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">لا توجد عقود مطابقة للبحث</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $contracts->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ✅ Excel Export --}}
    <script>
        function exportToExcel() {
            const table = document.querySelector('.contract-table');
            const html = table.outerHTML;
            const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'contracts_' + new Date().getTime() + '.xls';
            link.click();
        }
    </script>
</x-app-layout>
