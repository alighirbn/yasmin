<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')

        {{-- ===== TABLE + COLORS ===== --}}
        <style>
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

            .table-installments {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 8px;
                overflow: hidden;
                background-color: #fff;
                border: 1px solid #e0d6c6;
            }

            .table-installments thead th {
                background-color: #f8f2ea;
                color: #4e3e28;
                font-weight: 600;
                text-align: center;
                padding: 10px;
                border-bottom: 2px solid #e0d6c6;
            }

            .table-installments td {
                text-align: center;
                vertical-align: middle;
                padding: 10px;
                border-top: 1px solid #f0e8dc;
                color: #2f2f2f;
            }

            .table-installments tr:nth-child(even) {
                background-color: #fdfaf6;
            }

            .table-installments tr:hover {
                background-color: #f7f3ee;
                transition: background 0.2s ease-in-out;
            }

            .status-badge {
                padding: 4px 10px;
                border-radius: 6px;
                color: #fff;
                font-weight: 600;
                font-size: 13px;
            }

            .badge-paid {
                background: #43a047;
            }

            .badge-partial {
                background: #fb8c00;
            }

            .badge-due {
                background: #e53935;
            }

            .badge-upcoming {
                background: #6b7280;
            }

            .filter-box {
                background: #fffdf9;
                border: 1px solid #e0d6c6;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 20px;
            }
        </style>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ====== PAGE TITLE ====== --}}
                    <h2 class="text-xl font-bold mb-3">📌 الدفعات المستحقة</h2>

                    {{-- ====== FILTER BOX ====== --}}
                    <form method="GET" class="filter-box no-print">
                        <div class="row">
                            <div class="col-md-3">
                                <label>اسم الزبون</label>
                                <input type="text" name="customer" value="{{ request('customer') }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label>أيام قبل الاستحقاق</label>
                                <input type="number" name="days" value="{{ request('days', 0) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-custom-print w-100">تطبيق</button>
                            </div>
                        </div>
                    </form>

                    {{-- ====== BUTTONS ====== --}}
                    <div class="mb-3 no-print">
                        <button onclick="window.print()" class="btn btn-custom-print">🖨️ طباعة</button>
                        <button onclick="exportToExcel()" class="btn btn-custom-excel">📥 Excel</button>
                    </div>
                    <div class="print-container  mx-auto bg-white">
                        {{-- ====== TABLE ====== --}}
                        <div class="table-responsive">
                            <table class="table-installments" id="due-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الزبون</th>
                                        <th>الهاتف</th>
                                        <th>العقد ⬇️</th>
                                        <th>المبنى</th>
                                        <th>اسم القسط</th>
                                        <th>المبلغ الكلي</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>الأيام</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $i = 1; @endphp

                                    @foreach ($installments as $ins)
                                        @php
                                            $total = $ins->installment_amount;
                                            $paid = $ins->paid_amount ?? 0;
                                            $remain = $total - $paid;

                                            $dueDays = \Carbon\Carbon::parse($ins->installment_date)->diffInDays(
                                                now(),
                                                false,
                                            );

                                            $status = 'badge-upcoming';
                                            $statusText = 'غير مستحق';

                                            if ($paid == 0 && $dueDays > 0) {
                                                $status = 'badge-due';
                                                $statusText = 'مستحق';
                                            } elseif ($paid > 0 && $paid < $total) {
                                                $status = 'badge-partial';
                                                $statusText = 'مدفوع جزئياً';
                                            } elseif ($paid >= $total) {
                                                $status = 'badge-paid';
                                                $statusText = 'مدفوع';
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $ins->contract->customer->customer_full_name }}</td>
                                            <td>{{ $ins->contract->customer->customer_phone }}</td>
                                            <td>{{ $ins->contract->id }}</td>
                                            <td>{{ $ins->contract->building->building_number }}</td>
                                            <td>{{ $ins->installment->installment_name }}</td>

                                            <td>{{ number_format($total, 0) }}</td>
                                            <td>{{ number_format($paid, 0) }}</td>
                                            <td>{{ number_format($remain, 0) }}</td>

                                            <td>{{ $ins->installment_date }}</td>

                                            <td>
                                                @if ($dueDays < 0)
                                                    بعد {{ abs($dueDays) }} يوم
                                                @elseif ($dueDays > 0)
                                                    متأخر بـ {{ $dueDays }} يوم
                                                @else
                                                    اليوم
                                                @endif
                                            </td>

                                            <td>
                                                <span
                                                    class="status-badge {{ $status }}">{{ $statusText }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== EXPORT EXCEL SCRIPT ===== --}}
    <script>
        function exportToExcel() {
            let table = document.getElementById("due-table").outerHTML;
            let blob = new Blob([table], {
                type: "application/vnd.ms-excel"
            });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "due_installments_" + Date.now() + ".xls";
            link.click();
        }
    </script>

</x-app-layout>
