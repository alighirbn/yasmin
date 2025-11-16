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

            .whatsapp-btn {
                background: #25D366;
                color: white;
                padding: 6px 12px;
                border-radius: 6px;
                text-decoration: none;
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                transition: background 0.3s;
            }

            .whatsapp-btn:hover {
                background: #1ea952;
                color: white;
            }

            .whatsapp-bulk-btn {
                background: #128C7E;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                border: none;
                font-size: 15px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: background 0.3s;
            }

            .whatsapp-bulk-btn:hover {
                background: #0d6b5f;
            }

            .checkbox-select {
                width: 18px;
                height: 18px;
                cursor: pointer;
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
                    <div class="mb-3 no-print d-flex justify-content-between align-items-center">
                        <div>
                            <button onclick="window.print()" class="btn btn-custom-print">🖨️ طباعة</button>
                            <button onclick="exportToExcel()" class="btn btn-custom-excel">📥 Excel</button>
                        </div>
                        <div>
                            <button onclick="selectAll()" class="btn btn-secondary">✅ تحديد الكل</button>
                            <button onclick="deselectAll()" class="btn btn-secondary">❌ إلغاء التحديد</button>
                            <button onclick="sendBulkWhatsApp()" class="whatsapp-bulk-btn">
                                📱 إرسال واتساب جماعي (<span id="selected-count">0</span>)
                            </button>
                        </div>
                    </div>

                    <div class="print-container mx-auto bg-white">
                        {{-- ====== TABLE ====== --}}
                        <div class="table-responsive">
                            <table class="table-installments" id="due-table">
                                <thead>
                                    <tr>
                                        <th class="no-print">
                                            <input type="checkbox" id="select-all-checkbox"
                                                onchange="toggleAllCheckboxes(this)" class="checkbox-select">
                                        </th>
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
                                        <th class="no-print">واتساب</th>
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

                                            // Clean phone number for WhatsApp
                                            $phone = $ins->contract->customer->customer_phone;
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

                                            // Add Iraqi country code if not present
                                            if (!str_starts_with($cleanPhone, '964')) {
                                                // Remove leading zero if exists
                                                $cleanPhone = ltrim($cleanPhone, '0');
                                                $cleanPhone = '964' . $cleanPhone;
                                            }

                                            // WhatsApp message template
                                            $customerName = $ins->contract->customer->customer_full_name;
                                            $buildingNumber = $ins->contract->building->building_number;
                                            $installmentName = $ins->installment->installment_name;
                                            $dueDate = $ins->installment_date;
                                            $remainingAmount = number_format($remain, 0);

                                            $message = "السلام عليكم ورحمة الله وبركاته\n";
                                            $message .= "السيد/السيدة {$customerName} المحترم،\n";
                                            $message .= "تحية طيبة وبعد،\n\n";
                                            $message .=
                                                "نودّ إحاطتكم علماً بوجود دفعة مستحقة على جنابكم وفق التفاصيل التالية:\n\n";
                                            $message .= "🏢 المبنى: {$buildingNumber}\n";
                                            $message .= "📋 اسم الدفعة: {$installmentName}\n";
                                            $message .= "💰 المبلغ : {$remainingAmount} دينار\n";
                                            $message .= "📅 تاريخ الاستحقاق: {$dueDate}\n";

                                            if ($dueDays > 0) {
                                                $message .= "⏰ مدة التأخير: {$dueDays} يوماً\n\n";

                                                // من 1 إلى 10 أيام: بدون ذكر الغرامة
                                                if ($dueDays <= 10) {
                                                    $message .=
                                                        "نرجو التفضل بمراجعة الإدارة لدفع المبلغ المستحق في أقرب وقت ممكن، وذلك حرصاً على سير الإجراءات المالية وعدم تراكم المبالغ.\n\n";
                                                } else {
                                                    // أكثر من 10 أيام: مع ذكر الغرامة
                                                    $message .=
                                                        "نرجو التفضل بمراجعة الإدارة لدفع المبلغ المستحق والغرامة المترتبة على مدة التأخير، وذلك حرصاً على سير الإجراءات المالية والقانونية وعدم تراكم المبالغ.\n\n";
                                                }
                                            } elseif ($dueDays < 0) {
                                                // لم يصل تاريخ الاستحقاق بعد
                                                $message .= '⏰ المتبقي على الاستحقاق: ' . abs($dueDays) . " يوماً\n\n";
                                                $message .=
                                                    "نرجو التفضل بتسديد المبلغ  والالتزام بموعد الدفع المحدد.\n\n";
                                            } else {
                                                // مستحق اليوم
                                                $message .= "⏰ القسط مستحق اليوم\n\n";
                                                $message .=
                                                    "نرجو التفضل بمراجعة الإدارة لدفع المبلغ المستحق في أقرب وقت ممكن.\n\n";
                                            }

                                            $message .= "لأي استفسار أو مساعدة، يُسعدنا تواصلكم في أي وقت.\n\n";
                                            $message .= "مع فائق الاحترام والتقدير،\n";
                                            $message .= 'قسم الحسابات – إدارة المشروع';

                                            // Use web.whatsapp.com to skip the choice page
                                            $whatsappUrl =
                                                "https://web.whatsapp.com/send?phone={$cleanPhone}&text=" .
                                                urlencode($message);
                                        @endphp

                                        <tr>
                                            <td class="no-print">
                                                <input type="checkbox" class="installment-checkbox checkbox-select"
                                                    data-phone="{{ $cleanPhone }}" data-name="{{ $customerName }}"
                                                    data-building="{{ $buildingNumber }}"
                                                    data-installment="{{ $installmentName }}"
                                                    data-remaining="{{ $remain }}" data-date="{{ $dueDate }}"
                                                    data-days="{{ $dueDays }}" onchange="updateSelectedCount()">
                                            </td>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $customerName }}</td>
                                            <td>{{ $phone }}</td>
                                            <td>{{ $ins->contract->id }}</td>
                                            <td>{{ $buildingNumber }}</td>
                                            <td>{{ $installmentName }}</td>

                                            <td>{{ number_format($total, 0) }}</td>
                                            <td>{{ number_format($paid, 0) }}</td>
                                            <td>{{ number_format($remain, 0) }}</td>

                                            <td>{{ $dueDate }}</td>

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

                                            <td class="no-print">
                                                <a href="{{ $whatsappUrl }}" target="_blank" class="whatsapp-btn"
                                                    title="إرسال رسالة واتساب">
                                                    <svg width="16" height="16" fill="currentColor"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                                                    </svg>
                                                    إرسال
                                                </a>
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

    {{-- ===== SCRIPTS ===== --}}
    <script>
        // Export to Excel
        function exportToExcel() {
            let table = document.getElementById("due-table").cloneNode(true);

            // Remove checkbox column and WhatsApp column
            table.querySelectorAll('th:first-child, td:first-child, th:last-child, td:last-child').forEach(el => el
                .remove());

            let html = table.outerHTML;
            let blob = new Blob([html], {
                type: "application/vnd.ms-excel"
            });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "due_installments_" + Date.now() + ".xls";
            link.click();
        }

        // Update selected count
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.installment-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
        }

        // Toggle all checkboxes
        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.installment-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateSelectedCount();
        }

        // Select all
        function selectAll() {
            const checkboxes = document.querySelectorAll('.installment-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('select-all-checkbox').checked = true;
            updateSelectedCount();
        }

        // Deselect all
        function deselectAll() {
            const checkboxes = document.querySelectorAll('.installment-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('select-all-checkbox').checked = false;
            updateSelectedCount();
        }

        // Send bulk WhatsApp messages
        function sendBulkWhatsApp() {
            const selected = document.querySelectorAll('.installment-checkbox:checked');

            if (selected.length === 0) {
                alert('الرجاء تحديد زبون واحد على الأقل');
                return;
            }

            if (!confirm(`هل أنت متأكد من إرسال ${selected.length} رسالة واتساب؟`)) {
                return;
            }

            let delay = 0;
            selected.forEach((checkbox, index) => {
                let phone = checkbox.dataset.phone;

                // Ensure phone has country code
                if (!phone.startsWith('964')) {
                    phone = phone.replace(/^0+/, ''); // Remove leading zeros
                    phone = '964' + phone;
                }

                const name = checkbox.dataset.name;
                const building = checkbox.dataset.building;
                const installment = checkbox.dataset.installment;
                const remaining = parseFloat(checkbox.dataset.remaining);
                const date = checkbox.dataset.date;
                const days = parseInt(checkbox.dataset.days);

                // Build formal message
                let message = `السلام عليكم ورحمة الله وبركاته\n`;
                message += `السيد/السيدة ${name} المحترم،\n`;
                message += `تحية طيبة وبعد،\n\n`;
                message += `نودّ إحاطتكم علماً بوجود مبلغ مستحق على جنابكم وفق التفاصيل التالية:\n\n`;
                message += `🏢 المبنى: ${building}\n`;
                message += `📋 اسم الدفعة: ${installment}\n`;
                message += `💰 المبلغ المتبقي: ${remaining.toLocaleString()} دينار\n`;
                message += `📅 تاريخ الاستحقاق: ${date}\n`;

                if (days > 0) {
                    message += `⏰ مدة التأخير: ${days} يوماً\n\n`;
                    message +=
                        `نرجو التفضل بمراجعة الإدارة لدفع المبلغ المستحق والغرامة المترتبة على مدة التأخير، وذلك حرصاً على سير الإجراءات المالية والقانونية وعدم تراكم المبالغ.\n\n`;
                } else if (days < 0) {
                    message += `⏰ المتبقي على الاستحقاق: ${Math.abs(days)} يوماً\n\n`;
                    message += `نرجو التفضل بتجهيز المبلغ المستحق والالتزام بموعد الدفع المحدد.\n\n`;
                } else {
                    message += `⏰ القسط مستحق اليوم\n\n`;
                    message += `نرجو التفضل بمراجعة الإدارة لدفع القسط المستحق في أقرب وقت ممكن.\n\n`;
                }

                message += `لأي استفسار أو مساعدة، يُسعدنا تواصلكم في أي وقت.\n\n`;
                message += `مع فائق الاحترام والتقدير،\n`;
                message += `قسم الحسابات – إدارة المشروع`;

                const whatsappUrl =
                    `https://web.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(message)}`;

                // Open with delay to avoid browser blocking
                setTimeout(() => {
                    window.open(whatsappUrl, '_blank');
                }, delay);

                delay += 2000; // 2 seconds delay between each message
            });

            alert(`سيتم فتح ${selected.length} نافذة واتساب بشكل تدريجي. الرجاء السماح للمتصفح بفتح النوافذ المنبثقة.`);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });
    </script>

</x-app-layout>
