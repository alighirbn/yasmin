<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <!-- jQuery (load first if not already loaded) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- jQuery UI for sortable -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @include('contract.nav.navigation')
        <style>
            .sortable-row {
                cursor: move;
            }

            .sortable-row:hover {
                background-color: #f3f4f6;
            }

            .ui-sortable-helper {
                display: table;
                background-color: #ffffff;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .drag-handle {
                cursor: grab;
                font-size: 1.2em;
                color: #6b7280;
            }

            .drag-handle:active {
                cursor: grabbing;
            }

            .deleted-row {
                opacity: 0.5;
                background-color: #fee2e2 !important;
                text-decoration: line-through;
            }

            .installment-type-select {
                min-width: 150px;
            }

            /* ===== Modern Black Table Style ===== */
            #installments-table {
                border-collapse: collapse !important;
                width: 100%;
                color: #000 !important;
                font-size: 15px;
            }

            #installments-table th,
            #installments-table td {
                border: 1px solid #222 !important;
                color: #000 !important;
                padding: 8px 10px !important;
                vertical-align: middle;
            }

            #installments-table th {
                background: #f0f0f0 !important;
                font-weight: 700 !important;
                text-align: center;
            }

            #installments-table tbody tr:nth-child(odd) {
                background-color: #fafafa !important;
            }

            #installments-table tbody tr:hover {
                background-color: #f2f2f2 !important;
            }

            #installments-table input,
            #installments-table select {
                color: #000 !important;
                border: 1px solid #444 !important;
                background-color: #fff !important;
                border-radius: 4px;
                padding: 4px 6px;
                width: 100%;
            }

            /* Tighter footer row and bold total */
            #installments-table tfoot td {
                font-weight: bold;
                background-color: #f3f3f3 !important;
            }

            /* ======== تصحيح لون النص داخل البادجات ======== */
            .badge {
                color: #000 !important;
                /* يجعل النص أسود */
                font-weight: bold;
            }

            /* الخلفيات تبقى كما هي لكن بنص أسود */
            .badge-success {
                background-color: #a7f3d0 !important;
                /* أخضر فاتح */
                color: #000 !important;
            }

            .badge-warning {
                background-color: #fde68a !important;
                /* أصفر فاتح */
                color: #000 !important;
            }

            .badge-danger {
                background-color: #fca5a5 !important;
                /* أحمر فاتح */
                color: #000 !important;
            }

            .badge-secondary {
                background-color: #e5e7eb !important;
                /* رمادي فاتح */
                color: #000 !important;
            }

            /* Print-friendly mode */
            @media print {
                body {
                    color: #000 !important;
                    background: #fff !important;
                }

                #installments-table,
                #installments-table th,
                #installments-table td {
                    border: 1px solid #000 !important;
                    color: #000 !important;
                    background: #fff !important;
                }

                #installments-table tbody tr:nth-child(odd),
                #installments-table tbody tr:hover {
                    background: #fff !important;
                }
            }
        </style>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mx-4 mb-4">
                        تعديل أقساط العقد (متقدم)
                    </h2>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    {{-- Validation errors (from $request->validate) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="container mx-auto px-6 py-6">

                        <div class="flex flex-wrap gap-6 justify-center">

                            <!-- Contract Info Card -->
                            <div class="bg-white rounded-xl shadow-md p-6 flex-1 min-w-[350px]">
                                <h3 class="font-semibold text-lg mb-4 text-center">معلومات العقد</h3>
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <span class="text-gray-600">رقم العقد:</span>
                                        <span class="font-bold block">{{ $contract->id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">اسم الزبون:</span>
                                        <span
                                            class="font-bold block">{{ $contract->customer->customer_full_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">مبلغ العقد:</span>
                                        <span
                                            class="font-bold block">{{ number_format($contract->contract_amount, 0) }}
                                            IQD</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">طريقة الدفع:</span>
                                        <span class="font-bold block">
                                            {{ $contract->payment_method->payment_method_name ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Card -->
                            <div class="bg-blue-50 rounded-xl shadow-md p-6 flex-1 min-w-[350px]">
                                <h3 class="font-semibold text-lg mb-4 text-center">ملخص الأقساط</h3>
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <span class="text-gray-600">عدد الأقساط:</span>
                                        <span class="font-bold block">{{ $installments->count() }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">مجموع الأقساط:</span>
                                        <span class="font-bold block text-blue-700">
                                            {{ number_format($installments->sum('installment_amount'), 0) }} IQD
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">المبلغ المدفوع:</span>
                                        <span class="font-bold block text-green-600">
                                            {{ number_format($installments->sum('paid_amount'), 0) }} IQD
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">المبلغ المتبقي:</span>
                                        <span class="font-bold block text-red-600">
                                            {{ number_format($installments->sum('installment_amount') - $installments->sum('paid_amount'), 0) }}
                                            IQD
                                        </span>
                                    </div>

                                    <div class="col-span-2">
                                        <span class="text-gray-600">الفرق عن مبلغ العقد:</span>
                                        <span class="font-bold block"
                                            style="color: {{ abs($contract->contract_amount - $installments->sum('installment_amount')) > 1 ? 'red' : 'green' }}">
                                            {{ number_format($contract->contract_amount - $installments->sum('installment_amount'), 0) }}
                                            IQD
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions Card -->
                            <div class="bg-yellow-50 rounded-xl shadow-md p-6 flex-1 min-w-[350px]">
                                <h3 class="font-semibold text-lg mb-3 text-center">💡 تعليمات الاستخدام</h3>
                                <ul class="text-sm space-y-2">
                                    <li>🔄 <strong>إعادة الترتيب:</strong> اسحب أيقونة ☰ لتغيير ترتيب الأقساط</li>
                                    <li>🏷️ <strong>تغيير النوع:</strong> اختر نوع القسط من القائمة المنسدلة</li>
                                    <li>🗑️ <strong>الحذف:</strong> اضغط زر الحذف لإزالة قسط غير مدفوع</li>
                                    <li>💰 <strong>التعديل:</strong> غير المبلغ والتاريخ مباشرة في الجدول</li>
                                    <li>✓ <strong>الحفظ:</strong> يتم حفظ كل التغييرات مرة واحدة عند الضغط على "حفظ"
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <!-- Edit Form -->
                    <form method="POST"
                        action="{{ route('contract.installment.update_bulk', $contract->url_address) }}"
                        id="bulk-edit-form">
                        @csrf
                        @method('PATCH')

                        <!-- Hidden field for deleted installments -->
                        <input type="hidden" name="deleted_installments" id="deleted-installments" value="">

                        <div class="bg-white rounded-lg shadow-md p-6">

                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-lg">جدول الأقساط</h3>
                                <div class="flex gap-2">
                                    <button type="button" id="add-installment-btn" class="btn btn-custom-show">
                                        <i class="fas fa-plus"></i> إضافة قسط جديد
                                    </button>
                                    <button type="button" id="reset-order-btn" class="btn btn-sm btn-outline">
                                        إعادة تعيين الترتيب
                                    </button>
                                </div>
                            </div>

                            <table class="table" id="installments-table">
                                <thead>
                                    <tr>
                                        <th style="width: 3%">☰</th>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 18%">نوع القسط</th>
                                        <th style="width: 13%">تاريخ الاستحقاق</th>
                                        <th style="width: 13%">مبلغ القسط (IQD)</th>
                                        <th style="width: 12%">المبلغ المدفوع</th>
                                        <th style="width: 12%">المتبقي</th>
                                        <th style="width: 10%">الحالة</th>
                                        <th style="width: 8%">حذف</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-tbody">
                                    @foreach ($installments as $index => $installment)
                                        <tr class="installment-row sortable-row"
                                            data-installment-id="{{ $installment->id }}"
                                            data-paid="{{ $installment->paid_amount }}"
                                            data-original-amount="{{ $installment->installment_amount }}"
                                            data-original-sequence="{{ $installment->sequence_number }}">
                                            <td class="drag-handle">☰</td>
                                            <td class="sequence-display">{{ $installment->sequence_number }}</td>
                                            <td>
                                                <input type="hidden" name="installments[{{ $index }}][id]"
                                                    value="{{ $installment->id }}" class="installment-id-input">
                                                <input type="hidden"
                                                    name="installments[{{ $index }}][sequence_number]"
                                                    value="{{ $installment->sequence_number }}" class="sequence-input">

                                                <!-- Hidden field for installment_id (always submitted) -->
                                                <input type="hidden"
                                                    name="installments[{{ $index }}][installment_id]"
                                                    value="{{ $installment->installment_id }}"
                                                    class="installment-type-hidden">

                                                <select data-index="{{ $index }}"
                                                    class="form-control installment-type-select select2"
                                                    {{ $installment->isFullyPaid() ? 'disabled' : '' }}
                                                    onchange="updateHiddenInstallmentType(this)">
                                                    @php
                                                        $availableTypes = \App\Models\Contract\Installment::where(
                                                            'payment_method_id',
                                                            $contract->contract_payment_method_id,
                                                        )->get();
                                                    @endphp
                                                    @foreach ($availableTypes as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ $installment->installment_id == $type->id ? 'selected' : '' }}>
                                                            {{ $type->installment_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date"
                                                    name="installments[{{ $index }}][installment_date]"
                                                    class="form-control installment-date"
                                                    value="{{ $installment->installment_date->format('Y-m-d') }}"
                                                    {{ $installment->isFullyPaid() ? 'readonly' : '' }} required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01"
                                                    name="installments[{{ $index }}][installment_amount]"
                                                    class="form-control installment-amount"
                                                    value="{{ $installment->installment_amount }}"
                                                    min="{{ $installment->paid_amount }}"
                                                    {{ $installment->isFullyPaid() ? 'readonly' : '' }} required>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-success">{{ number_format($installment->paid_amount, 0) }}</span>
                                            </td>
                                            <td class="text-center remaining-cell">
                                                <span
                                                    class="badge badge-warning">{{ number_format($installment->getRemainingAmount(), 0) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($installment->isFullyPaid())
                                                    <span class="badge badge-success">مدفوع</span>
                                                @elseif ($installment->isPartiallyPaid())
                                                    <span class="badge badge-warning">جزئي</span>
                                                @elseif ($installment->isOverdue())
                                                    <span class="badge badge-danger">متأخر</span>
                                                @else
                                                    <span class="badge badge-secondary">غير مدفوع</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!$installment->isFullyPaid() && !$installment->isPartiallyPaid())
                                                    <button type="button"
                                                        class="btn btn-custom-delete delete-installment-btn"
                                                        data-installment-id="{{ $installment->id }}"
                                                        title="حذف القسط" style="padding: 5px 10px; cursor: pointer;">
                                                        <i class="fas fa-trash"></i> حذف
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-sm">محمي</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-100">
                                        <td colspan="4" class="text-right">المجموع:</td>
                                        <td id="footer-total">
                                            {{ number_format($installments->sum('installment_amount'), 0) }}
                                        </td>
                                        <td>{{ number_format($installments->sum('paid_amount'), 0) }}</td>
                                        <td id="footer-remaining">
                                            {{ number_format($installments->sum('installment_amount') - $installments->sum('paid_amount'), 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Warning Messages -->
                        <div class="mt-6 p-4 bg-yellow-50 rounded border border-yellow-200">
                            <p class="text-sm text-gray-700 mb-2">
                                <strong>⚠️ تنبيهات مهمة:</strong>
                            </p>
                            <ul class="list-disc list-inside text-sm text-gray-600">
                                <li>يمكن حذف الأقساط غير المدفوعة فقط</li>
                                <li>يمكن إعادة ترتيب جميع الأقساط بالسحب والإفلات</li>
                                <li>يمكن تغيير نوع القسط لأي قسط غير مدفوع بالكامل</li>
                                <li>لا يمكن تقليل مبلغ القسط عن المبلغ المدفوع</li>
                                <li>يجب أن يتطابق مجموع الأقساط مع مبلغ العقد (±1 IQD)</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 mt-6">
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-outline">
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-custom-show" id="submit-btn">
                                💾 حفظ جميع التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to update hidden installment_id when dropdown changes
        function updateHiddenInstallmentType(selectElement) {
            const index = $(selectElement).data('index');
            const newValue = $(selectElement).val();
            const row = $(selectElement).closest('tr');
            row.find('.installment-type-hidden').val(newValue);
            console.log('Updated installment_id for index ' + index + ' to: ' + newValue);
        }

        // Wait for complete page load
        $(document).ready(function() {
            console.log('Bulk edit script loaded');
            console.log('jQuery version:', $.fn.jquery);
            console.log('jQuery UI loaded:', typeof $.ui !== 'undefined');

            const contractAmount = {{ $contract->contract_amount }};
            const form = $('#bulk-edit-form');
            const submitBtn = $('#submit-btn');
            let deletedInstallments = [];
            let newInstallmentCounter = 0; // Counter for new installments

            // Available installment types
            const availableTypes = [
                @foreach (\App\Models\Contract\Installment::where('payment_method_id', $contract->contract_payment_method_id)->get() as $type)
                    {
                        id: {{ $type->id }},
                        name: '{{ $type->installment_name }}'
                    },
                @endforeach
            ];

            // Initialize Select2
            try {
                $('.select2').select2({
                    dir: 'rtl',
                    language: 'ar',
                    width: '100%'
                });

                // Bind change event to update hidden field
                $('.installment-type-select').on('change', function() {
                    updateHiddenInstallmentType(this);
                });

                console.log('Select2 initialized');
            } catch (e) {
                console.error('Select2 error:', e);
            }

            // Initialize sortable with better options
            try {
                $("#sortable-tbody").sortable({
                    handle: ".drag-handle",
                    axis: "y",
                    cursor: "move",
                    placeholder: "ui-state-highlight",
                    helper: function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function(index) {
                            $(this).width($originals.eq(index).width());
                        });
                        return $helper;
                    },
                    start: function(e, ui) {
                        console.log('Drag started');
                    },
                    update: function(event, ui) {
                        console.log('Row reordered');
                        updateSequenceNumbers();
                        updateTotals();
                    }
                }).disableSelection();
                console.log('Sortable initialized');

                // Test sortable
                if ($("#sortable-tbody").sortable("instance")) {
                    console.log('Sortable is working!');
                }
            } catch (e) {
                console.error('Sortable error:', e);
            }

            // Function to update sequence numbers after reordering
            function updateSequenceNumbers() {
                let sequence = 1;
                $('#sortable-tbody tr.installment-row:not(.deleted-row)').each(function() {
                    $(this).find('.sequence-display').text(sequence);
                    $(this).find('.sequence-input').val(sequence);
                    sequence++;
                });

                // Update installment count
                const activeCount = $('#sortable-tbody tr.installment-row:not(.deleted-row)').length;
                $('#installment-count').text(activeCount);
            }

            // Function to calculate and update totals
            function updateTotals() {
                let total = 0;
                let totalPaid = 0;

                $('#sortable-tbody tr.installment-row:not(.deleted-row)').each(function() {
                    const amountInput = $(this).find('.installment-amount');
                    const paid = parseFloat($(this).data('paid')) || 0;
                    const amount = parseFloat(amountInput.val()) || 0;

                    total += amount;
                    totalPaid += paid;

                    // Update remaining for this row
                    const remaining = Math.max(0, amount - paid);
                    const remainingCell = $(this).find('.remaining-cell span');
                    if (remainingCell.length) {
                        remainingCell.text(remaining.toLocaleString('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }));
                    }
                });

                const totalRemaining = total - totalPaid;
                const difference = contractAmount - total;

                // Update summary card
                $('#total-installments').text(total.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));
                $('#remaining-amount').text(totalRemaining.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));

                const differenceElement = $('#difference');
                differenceElement.text(difference.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));
                differenceElement.css('color', Math.abs(difference) > 1 ? 'red' : 'green');

                // Update footer
                $('#footer-total').text(total.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));
                $('#footer-remaining').text(totalRemaining.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));

                // Enable/disable submit button based on difference
                if (Math.abs(difference) > 1) {
                    submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    submitBtn.attr('title', 'مجموع الأقساط لا يتطابق مع مبلغ العقد');
                } else {
                    submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    submitBtn.attr('title', '');
                }
            }

            // Add event listeners to all amount inputs
            $(document).on('input', '.installment-amount', function() {
                console.log('Amount changed');
                updateTotals();
            });

            // Validate minimum amount
            $(document).on('blur', '.installment-amount', function() {
                const row = $(this).closest('.installment-row');
                const minAmount = parseFloat(row.data('paid')) || 0;
                const currentValue = parseFloat($(this).val()) || 0;

                if (currentValue < minAmount) {
                    alert(
                        `مبلغ القسط لا يمكن أن يكون أقل من المبلغ المدفوع (${minAmount.toLocaleString()} IQD)`
                    );
                    $(this).val(minAmount);
                    updateTotals();
                }
            });

            // Delete installment functionality
            $(document).on('click', '.delete-installment-btn', function(e) {
                e.preventDefault();
                console.log('Delete button clicked');

                const installmentId = $(this).data('installment-id');
                const row = $(this).closest('tr');

                console.log('Installment ID:', installmentId);

                // Check if this is the last remaining installment
                const activeRows = $('#sortable-tbody tr.installment-row:not(.deleted-row)').length;
                if (activeRows <= 1) {
                    alert('لا يمكن حذف آخر قسط في العقد. يجب أن يبقى قسط واحد على الأقل.');
                    return false;
                }

                if (confirm('هل أنت متأكد من حذف هذا القسط؟')) {
                    console.log('Deletion confirmed');

                    // Add to deleted list
                    deletedInstallments.push(installmentId);
                    $('#deleted-installments').val(JSON.stringify(deletedInstallments));
                    console.log('Deleted installments:', deletedInstallments);

                    // Mark row as deleted
                    row.addClass('deleted-row');

                    // Disable all inputs in this row
                    row.find('input, select').prop('disabled', true);

                    // Change button to restore
                    $(this).removeClass('btn-danger delete-installment-btn')
                        .addClass('btn-success restore-installment-btn')
                        .html('<i class="fas fa-undo"></i> استرجاع')
                        .attr('title', 'استرجاع القسط');

                    // Update sequence numbers and totals
                    updateSequenceNumbers();
                    updateTotals();
                }

                return false;
            });

            // Restore installment functionality
            $(document).on('click', '.restore-installment-btn', function(e) {
                e.preventDefault();
                console.log('Restore button clicked');

                const installmentId = $(this).data('installment-id');
                const row = $(this).closest('tr');

                // Remove from deleted list
                deletedInstallments = deletedInstallments.filter(id => id !== installmentId);
                $('#deleted-installments').val(JSON.stringify(deletedInstallments));
                console.log('Deleted installments after restore:', deletedInstallments);

                // Remove deleted styling
                row.removeClass('deleted-row');

                // Enable all inputs in this row (except readonly/disabled by default)
                row.find('input:not([readonly])').prop('disabled', false);
                row.find('select').not('.installment-type-select[disabled]').prop('disabled', false);

                // Re-initialize select2 for this row if not permanently disabled
                if (!row.find('.installment-type-select').attr('disabled')) {
                    row.find('.installment-type-select').select2({
                        dir: 'rtl',
                        language: 'ar',
                        width: '100%'
                    });
                }

                // Change button back to delete
                $(this).removeClass('btn-success restore-installment-btn')
                    .addClass('btn-danger delete-installment-btn')
                    .html('<i class="fas fa-trash"></i> حذف')
                    .attr('title', 'حذف القسط');

                // Update sequence numbers and totals
                updateSequenceNumbers();
                updateTotals();

                return false;
            });

            // Reset order button
            $('#reset-order-btn').on('click', function(e) {
                e.preventDefault();
                if (confirm('هل تريد إعادة تعيين الترتيب الأصلي للأقساط؟')) {
                    const rows = $('#sortable-tbody tr.installment-row').get();

                    rows.sort(function(a, b) {
                        const seqA = parseInt($(a).data('original-sequence'));
                        const seqB = parseInt($(b).data('original-sequence'));
                        return seqA - seqB;
                    });

                    $.each(rows, function(index, row) {
                        $('#sortable-tbody').append(row);
                    });

                    updateSequenceNumbers();
                    updateTotals();
                }
                return false;
            });

            // Add new installment button
            $('#add-installment-btn').on('click', function(e) {
                e.preventDefault();
                console.log('Add installment button clicked');

                // Get current number of rows
                const currentRows = $('#sortable-tbody tr.installment-row').length;
                const nextSequence = currentRows + 1;

                // Generate unique ID for new row
                const newId = 'new_' + Date.now() + '_' + newInstallmentCounter++;

                // Get tomorrow's date as default
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const defaultDate = tomorrow.toISOString().split('T')[0];

                // Build options for select
                let optionsHtml = '';
                availableTypes.forEach(function(type) {
                    optionsHtml += `<option value="${type.id}">${type.name}</option>`;
                });

                // Create new row HTML
                const newRowHtml = `
                        <tr class="installment-row sortable-row new-installment-row" 
                            data-installment-id="${newId}"
                            data-paid="0"
                            data-original-amount="0"
                            data-original-sequence="${nextSequence}"
                            data-is-new="true">
                            <td class="drag-handle">☰</td>
                            <td class="sequence-display">${nextSequence}</td>
                            <td>
                                <input type="hidden" name="new_installments[${newInstallmentCounter}][temp_id]" value="${newId}">
                                <input type="hidden" name="new_installments[${newInstallmentCounter}][sequence_number]" value="${nextSequence}" class="sequence-input">
                                <input type="hidden" name="new_installments[${newInstallmentCounter}][installment_id]" value="${availableTypes[0].id}" class="installment-type-hidden">
                                
                                <select data-index="${newInstallmentCounter}"
                                    class="form-control installment-type-select select2-new"
                                    onchange="updateHiddenInstallmentType(this)">
                                    ${optionsHtml}
                                </select>
                            </td>
                            <td>
                                <input type="date" name="new_installments[${newInstallmentCounter}][installment_date]"
                                    class="form-control installment-date"
                                    value="${defaultDate}"
                                    required>
                            </td>
                            <td>
                                <input type="number" step="0.01"
                                    name="new_installments[${newInstallmentCounter}][installment_amount]"
                                    class="form-control installment-amount"
                                    value="0"
                                    min="0"
                                    required>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">0</span>
                            </td>
                            <td class="text-center remaining-cell">
                                <span class="badge badge-warning">0</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">جديد</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger delete-new-installment-btn"
                                    data-temp-id="${newId}"
                                    title="حذف القسط الجديد"
                                    style="padding: 5px 10px; cursor: pointer;">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </td>
                        </tr>
                    `;

                // Append new row to tbody
                $('#sortable-tbody').append(newRowHtml);

                // Initialize Select2 for the new row
                $('.select2-new').last().select2({
                    dir: 'rtl',
                    language: 'ar',
                    width: '100%'
                });

                // Remove the select2-new class after initialization
                $('.select2-new').last().removeClass('select2-new');

                // Update sequence numbers and totals
                updateSequenceNumbers();
                updateTotals();

                // Scroll to the new row
                $('html, body').animate({
                    scrollTop: $('#sortable-tbody tr:last').offset().top - 100
                }, 500);

                console.log('New installment added with ID:', newId);

                // Show notification
                alert('تمت إضافة قسط جديد. لا تنسى تعديل المبلغ والتاريخ حسب الحاجة.');
            });

            // Delete new installment button
            $(document).on('click', '.delete-new-installment-btn', function(e) {
                e.preventDefault();
                console.log('Delete new installment clicked');

                const row = $(this).closest('tr');
                const tempId = $(this).data('temp-id');

                if (confirm('هل أنت متأكد من حذف هذا القسط الجديد؟')) {
                    console.log('Deleting new installment:', tempId);

                    // Simply remove the row (it's not in database yet)
                    row.remove();

                    // Update sequence numbers and totals
                    updateSequenceNumbers();
                    updateTotals();
                }

                return false;
            });

            // Form submission validation and reindexing
            form.on('submit', function(e) {
                const activeRows = $('#sortable-tbody tr.installment-row:not(.deleted-row)').length;

                if (activeRows === 0) {
                    e.preventDefault();
                    alert('لا يمكن حفظ عقد بدون أقساط. يجب أن يكون هناك قسط واحد على الأقل.');
                    return false;
                }

                // Re-index the form inputs for active rows only
                let index = 0;
                $('#sortable-tbody tr.installment-row:not(.deleted-row)').each(function() {
                    // Update all field names with new index
                    $(this).find('[name^="installments"]').each(function() {
                        const name = $(this).attr('name');
                        const fieldName = name.match(/\[([^\]]+)\]$/)[
                            1]; // Extract field name (id, amount, etc.)
                        const newName = `installments[${index}][${fieldName}]`;
                        $(this).attr('name', newName);
                    });

                    // Also update data-index for selects
                    $(this).find('.installment-type-select').data('index', index);

                    index++;
                });

                console.log('Form reindexed. Total active rows:', index);

                const difference = Math.abs(contractAmount - parseFloat($('#total-installments').text()
                    .replace(/,/g, '')));

                if (difference > 1) {
                    e.preventDefault();
                    alert('مجموع الأقساط يجب أن يتطابق مع مبلغ العقد. الفرق الحالي: ' + difference
                        .toLocaleString() + ' IQD');
                    return false;
                }

                return confirm('هل أنت متأكد من حفظ جميع التعديلات؟\n\n' +
                    'التعديلات تشمل:\n' +
                    '- عدد الأقساط: ' + activeRows + '\n' +
                    '- الأقساط المحذوفة: ' + deletedInstallments.length);
            });

            // Initial calculation
            updateTotals();

            console.log('All event handlers attached');

            // Test delete buttons
            console.log('Delete buttons found:', $('.delete-installment-btn').length);
        });
    </script>

</x-app-layout>
