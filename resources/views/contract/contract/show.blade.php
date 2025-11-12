<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')

        <style>
            .workflow-status-compact {
                background: linear-gradient(135deg, #ecccb2 0%, #9f8b73 100%);
                color: white;
                padding: 12px 20px;
                border-radius: 6px;
                margin-bottom: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .workflow-status-compact .status-info {
                display: flex;
                gap: 20px;
                align-items: center;
                flex-wrap: wrap;
            }

            .workflow-status-compact .status-item {
                font-size: 13px;
            }

            .workflow-status-compact .next-action {
                background: #ffc107;
                padding: 6px 12px;
                border-radius: 4px;
                color: #000;
                font-weight: bold;
                font-size: 12px;
            }

            .quick-actions-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                padding: 8px 0;
                margin-bottom: 10px;
                align-items: center;
            }

            .btn-compact {
                padding: 6px 12px !important;
                font-size: 13px !important;
                position: relative;
                white-space: nowrap;
            }

            .btn-compact:hover::after {
                content: attr(data-hint);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: #1f2937;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                white-space: nowrap;
                font-size: 11px;
                z-index: 1000;
                margin-bottom: 4px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .show-all-btn {
                background: #6b7280;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                white-space: nowrap;
            }

            .show-all-btn:hover {
                background: #4b5563;
            }

            .all-actions-horizontal {
                display: none;
                gap: 10px;
                padding: 10px;
                background: #f9fafb;
                border-radius: 6px;
                margin-bottom: 15px;
                flex-wrap: wrap;
            }

            .all-actions-horizontal.active {
                display: flex;
            }

            .action-card {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 10px;
                min-width: 180px;
                flex: 1 1 auto;
            }

            .action-card-title {
                font-size: 12px;
                font-weight: bold;
                color: #374151;
                margin-bottom: 8px;
                padding-bottom: 6px;
                border-bottom: 2px solid #3b82f6;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .action-card-buttons {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .action-card-buttons .btn-compact {
                width: 100%;
                text-align: center;
            }

            .divider {
                height: 30px;
                width: 1px;
                background: #d1d5db;
                margin: 0 4px;
            }

            /* ================================
       WORKFLOW STATUS BAR
    ================================ */
            .workflow-status-compact {
                background: linear-gradient(135deg, #b38a4c 0%, #7b5a2a 100%);
                color: #ffffff;
                padding: 14px 22px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            }

            .workflow-status-compact .status-info {
                display: flex;
                gap: 25px;
                align-items: center;
                flex-wrap: wrap;
            }

            .workflow-status-compact .status-item {
                font-size: 14px;
                background: rgba(255, 255, 255, 0.15);
                padding: 6px 10px;
                border-radius: 6px;
            }

            .workflow-status-compact .next-action {
                background: #f1d142;
                padding: 8px 14px;
                border-radius: 6px;
                color: #2f2f2f;
                font-weight: bold;
                font-size: 13px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* ================================
       QUICK ACTIONS BAR
    ================================ */
            .quick-actions-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px 0;
                margin-bottom: 15px;
                align-items: center;
                border-bottom: 1px solid #e0d6c6;
                padding-bottom: 12px;
            }

            .btn-compact {
                padding: 6px 12px !important;
                font-size: 13px !important;
                position: relative;
                white-space: nowrap;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease-in-out;
            }

            .btn-compact:hover {
                transform: translateY(-2px);
            }

            .btn-compact:hover::after {
                content: attr(data-hint);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: #1f2937;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                white-space: nowrap;
                font-size: 11px;
                z-index: 1000;
                margin-bottom: 4px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .show-all-btn {
                background: #9f7a3e;
                color: #ffffff;
                border: none;
                padding: 6px 14px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 13px;
                white-space: nowrap;
                transition: all 0.2s ease-in-out;
            }

            .show-all-btn:hover {
                background: #7a5c2d;
            }

            .divider {
                height: 28px;
                width: 1px;
                background: #d1c5b4;
                margin: 0 6px;
            }

            /* ================================
       ALL ACTIONS SECTION
    ================================ */
            .all-actions-horizontal {
                display: none;
                gap: 12px;
                padding: 12px;
                background: #fdfbf8;
                border: 1px solid #e0d6c6;
                border-radius: 10px;
                margin-bottom: 20px;
                flex-wrap: wrap;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            }

            .all-actions-horizontal.active {
                display: flex;
            }

            .action-card {
                background: #ffffff;
                border: 1px solid #e0d6c6;
                border-radius: 8px;
                padding: 12px;
                min-width: 200px;
                flex: 1 1 220px;
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
                transition: transform 0.2s;
            }

            .action-card:hover {
                transform: translateY(-2px);
            }

            .action-card-title {
                font-size: 13px;
                font-weight: bold;
                color: #6b4b1f;
                margin-bottom: 10px;
                padding-bottom: 6px;
                border-bottom: 2px solid #c99b4f;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .action-card-buttons {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            /* ================================
       PROGRESS BAR
    ================================ */
            .progress-bar-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: relative;
                padding: 24px 0;
                font-family: Arial, sans-serif;
                direction: rtl;
            }

            .progress-line-background {
                position: absolute;
                top: 24px;
                left: 0;
                right: 0;
                height: 4px;
                background-color: #e0d6c6;
                border-radius: 2px;
                z-index: 0;
            }

            .progress-line-foreground {
                position: absolute;
                top: 24px;
                right: 0;
                height: 4px;
                background-color: #b38a4c;
                border-radius: 2px;
                z-index: 1;
                transition: width 0.3s ease;
            }

            .circle {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background-color: #ffffff;
                border: 2px solid #d4b184;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #b38a4c;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 8px;
                transition: background-color 0.3s, color 0.3s;
            }

            .circle.completed {
                background-color: #b38a4c;
                color: white;
                border-color: #b38a4c;
            }

            .stage-label {
                font-size: 12px;
                color: #7a6a55;
                transition: color 0.3s;
            }

            .stage-label.completed-text {
                color: #b38a4c;
            }

            /* ================================
       TABLE STYLING
    ================================ */
            table.table {
                background-color: #ffffff;
                border: 1px solid #e0d6c6;
                border-radius: 8px;
                overflow: hidden;
                width: 100%;
            }

            table.table th {
                background-color: #f8f2ea;
                color: #4e3e28;
                font-weight: 600;
                text-align: center;
                padding: 10px;
            }

            table.table td {
                text-align: center;
                vertical-align: middle;
                padding: 10px;
                border-top: 1px solid #f0e8dc;
            }

            table.table tr:nth-child(even) {
                background-color: #fdfaf6;
            }

            table.table tr:hover {
                background-color: #f4efe8;
            }

            /* ================================
       RESPONSIVE
    ================================ */
            @media (max-width: 992px) {

                .workflow-status-compact,
                .quick-actions-bar {
                    flex-direction: column;
                    align-items: stretch;
                }

                .divider {
                    display: none;
                }
            }
        </style>
        <style>
            /* ================================
   INFO SECTION STYLES
================================ */
            .info-section {
                margin-top: 20px;
                background: #ffffff;
                border: 1px solid #e0d6c6;
                border-radius: 10px;
                padding: 18px 22px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .info-section h2 {
                font-size: 16px;
                font-weight: 700;
                color: #6b4b1f;
                margin-bottom: 12px;
                border-bottom: 2px solid #c99b4f;
                padding-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 16px;
            }

            .info-item {
                background: #fcf8f3;
                border: 1px solid #e6ddcc;
                border-radius: 8px;
                padding: 10px 14px;
                transition: all 0.2s ease;
            }

            .info-item:hover {
                background: #f7f2ea;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            }

            .info-label {
                font-size: 13px;
                color: #7b6b58;
                margin-bottom: 4px;
                display: block;
            }

            .info-value {
                font-size: 14px;
                font-weight: 600;
                color: #2f2f2f;
                word-wrap: break-word;
            }

            .info-note {
                background: #fff9e6;
                border: 1px dashed #e6c85e;
                border-radius: 8px;
                padding: 10px 14px;
                font-size: 14px;
                color: #6b571d;
                margin-top: 10px;
            }

            @media (max-width: 768px) {
                .info-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <style>
            .installment-section {
                margin-top: 25px;
                background: #ffffff;
                border: 1px solid #e0d6c6;
                border-radius: 10px;
                padding: 18px 22px;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            }

            .installment-section h2 {
                font-size: 16px;
                font-weight: 700;
                color: #6b4b1f;
                margin-bottom: 14px;
                border-bottom: 2px solid #c99b4f;
                padding-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            /* Table styling */
            .table-installments {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                border-radius: 8px;
                overflow: hidden;
                background-color: #fff;
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
            }

            .table-installments tr:nth-child(even) {
                background-color: #fdfaf6;
            }

            .table-installments tr:hover {
                background-color: #f7f3ee;
                transition: background 0.2s ease-in-out;
            }

            /* Status tags */
            .status-badge {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                color: #fff;
            }

            .status-due {
                background-color: #e53935;
            }

            .status-pending {
                background-color: #fb8c00;
            }

            .status-paid {
                background-color: #43a047;
            }

            .status-upcoming {
                background-color: #6b7280;
            }

            /* Payment progress bar */
            .progress-bar-wrapper {
                margin-top: 4px;
                background-color: #e9e5de;
                border-radius: 6px;
                height: 8px;
                overflow: hidden;
            }

            .progress-bar-fill {
                background-color: #43a047;
                height: 8px;
                border-radius: 6px;
                transition: width 0.3s ease;
            }

            /* Buttons inside table */
            .table-action {
                display: flex;
                justify-content: center;
                gap: 6px;
                flex-wrap: wrap;
            }

            .table-action .btn {
                padding: 4px 10px !important;
                font-size: 13px !important;
                border-radius: 6px;
            }
        </style>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Success & Error Messages --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Compact Workflow Status --}}
                    <div class="workflow-status-compact">
                        <div class="status-info">
                            <div class="status-item">
                                <strong>📋 {{ __('word.' . $contract->stage) }}</strong>
                            </div>
                            <div class="status-item">
                                💳 {{ $contract->payment_method->method_name }}
                            </div>
                            <div class="status-item">
                                💰 {{ number_format($contract->contract_amount, 0) }} د
                            </div>
                            @if ($due_installments_count > 0)
                                <div class="status-item"
                                    style="background: #dc3545; padding: 4px 8px; border-radius: 4px;">
                                    ⚠️ {{ $due_installments_count }} قسط مستحق
                                </div>
                            @endif
                        </div>
                        <div>
                            @if ($contract->stage == 'temporary')
                                <span class="next-action">⏭️ قبول العقد</span>
                            @elseif ($contract->stage == 'accepted')
                                @php
                                    // شرط دفع أول دفعة أو المقدمة
                                    $downPaymentPaid = $contract->payments()->exists();
                                @endphp

                                @if ($downPaymentPaid)
                                    <span class="next-action">⏭️ أرشفة ثم مصادقة</span>
                                @else
                                    <span class="next-action" style="background: #dc3545;">⛔ تسديد المقدمة أولاً</span>
                                @endif
                            @elseif ($contract->stage == 'authenticated')
                                <span class="next-action" style="background: #10b981;">✅ نهائي</span>
                            @elseif ($contract->stage == 'terminated')
                                <span class="next-action" style="background: #dc3545; color: white;">❌ مفسوخ</span>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Actions - Always Visible in One Row --}}
                    <div class="quick-actions-bar">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back btn-compact" data-hint="رجوع">
                            ← رجوع
                        </a>

                        <div class="divider"></div>

                        @can('contract-statement')
                            <a href="{{ route('contract.statement', $contract->url_address) }}"
                                class="btn btn-custom-statement btn-compact" data-hint="كشف الحساب">
                                📊 كشف الحساب
                            </a>
                        @endcan

                        @if ($contract->stage !== 'terminated')
                            {{-- Most Common Actions Based on Stage --}}
                            @if ($contract->stage == 'temporary')
                                @can('contract-accept')
                                    <a href="{{ route('contract.accept', $contract->url_address) }}"
                                        class="btn btn-custom-approve btn-compact" data-hint="قبول العقد">
                                        ✅ قبول
                                    </a>
                                @endcan
                            @elseif ($contract->stage == 'accepted')
                                @can('contract-archive')
                                    @if ($contract->payments->count() >= 1)
                                        <a href="{{ route('contract.archivecreate', $contract->url_address) }}"
                                            class="btn btn-custom-archive btn-compact" data-hint="أرشفة">
                                            📁 أرشفة
                                        </a>
                                    @endif
                                @endcan
                                @can('contract-authenticat')
                                    @if (count($contract->images) >= 1 && $contract->payments->count() >= 1)
                                        <a href="{{ route('contract.authenticat', $contract->url_address) }}"
                                            class="btn btn-custom-approve btn-compact" data-hint="مصادقة">
                                            ✅ مصادقة
                                        </a>
                                    @endif
                                @endcan
                            @endif

                            <div class="divider"></div>

                            @can('payment-show')
                                <a href="{{ route('payment.index', ['contract_id' => $contract->id]) }}"
                                    class="btn btn-custom-show btn-compact" data-hint="الدفعات">
                                    💰 الدفعات
                                </a>
                            @endcan

                            @can('contract-due')
                                @if ($due_installments_count > 0)
                                    <a href="{{ route('contract.due', ['contract_id' => $contract->id]) }}"
                                        class="btn btn-custom-due btn-compact" data-hint="الأقساط المستحقة">
                                        ⚠️ مستحق ({{ $due_installments_count }})
                                    </a>
                                @endif
                            @endcan

                            <div class="divider"></div>

                            @can('contract-print')
                                @if ($contract->stage == 'authenticated' && count($contract->images) >= 1)
                                    <a href="{{ route('contract.print', $contract->url_address) }}"
                                        class="btn btn-custom-print btn-compact" data-hint="طباعة عقد نهائي">
                                        🖨️ طباعة عقد نهائي

                                    </a>
                                @endif
                            @endcan
                        @endif

                        {{-- Show All Button --}}
                        <button onclick="toggleAllActions()" class="show-all-btn">
                            <span id="toggleIcon">▼</span> المزيد
                        </button>
                    </div>

                    {{-- All Actions - Horizontal Cards --}}
                    <div id="allActionsHorizontal" class="all-actions-horizontal">

                        @if ($contract->stage !== 'terminated')
                            {{-- Contract Actions Card --}}
                            <div class="action-card">
                                <div class="action-card-title">⚙️ إجراءات العقد</div>
                                <div class="action-card-buttons">
                                    @can('contract-update')
                                        @if ($contract->payments->count() >= 0 && $contract->stage == 'temporary')
                                            <a href="{{ route('contract.edit', $contract->url_address) }}"
                                                class="btn btn-custom-edit btn-compact">
                                                تعديل
                                            </a>
                                            <!-- Bulk edit all installments -->
                                            <a href="{{ route('contract.installment.edit_bulk', $contract->url_address) }}"
                                                class="btn btn-primary">
                                                تعديل جميع الأقساط
                                            </a>
                                        @endif
                                    @endcan

                                    @can('contract-accept')
                                        @if ($contract->stage == 'temporary')
                                            <a href="{{ route('contract.accept', $contract->url_address) }}"
                                                class="btn btn-custom-approve btn-compact">
                                                قبول
                                            </a>
                                        @endif
                                    @endcan

                                    @can('contract-authenticat')
                                        @if ($contract->stage == 'accepted' && count($contract->images) >= 1 && $contract->payments->count() >= 1)
                                            <a href="{{ route('contract.authenticat', $contract->url_address) }}"
                                                class="btn btn-custom-approve btn-compact">
                                                مصادقة
                                            </a>
                                        @endif
                                    @endcan

                                    @can('contract-temporary')
                                        @if ($contract->stage == 'authenticated')
                                            <a href="{{ route('contract.temporary', $contract->url_address) }}"
                                                class="btn btn-custom-approve btn-compact">
                                                إرجاع
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                            </div>

                            {{-- Payments Card --}}
                            <div class="action-card">
                                <div class="action-card-title">💰 الدفعات والأقساط</div>
                                <div class="action-card-buttons">
                                    @can('payment-show')
                                        <a href="{{ route('payment.index', ['contract_id' => $contract->id]) }}"
                                            class="btn btn-custom-show btn-compact">
                                            الدفعات
                                        </a>
                                        <a href="{{ route('payment.pending', $contract->url_address) }}"
                                            class="btn btn-custom-due btn-compact">
                                            معلقة ({{ $pending_payments_count }})
                                        </a>
                                    @endcan
                                    @can('contract-due')
                                        <a href="{{ route('contract.due', ['contract_id' => $contract->id]) }}"
                                            class="btn btn-custom-due btn-compact">
                                            مستحقة ({{ $due_installments_count }})
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            {{-- Archive Card --}}
                            <div class="action-card">
                                <div class="action-card-title">📁 الأرشفة والمسح</div>
                                <div class="action-card-buttons">
                                    @can('contract-archive')
                                        @if ($contract->payments->count() >= 1)
                                            <a href="{{ route('contract.archivecreate', $contract->url_address) }}"
                                                class="btn btn-custom-archive btn-compact">
                                                أرشفة
                                            </a>
                                            <a href="{{ route('contract.scancreate', $contract->url_address) }}"
                                                class="btn btn-custom-archive btn-compact">
                                                مسح ضوئي
                                            </a>
                                        @endif
                                    @endcan
                                    @can('contract-archiveshow')
                                        @if ($contract->payments->count() >= 1 && count($contract->images) >= 1)
                                            <a href="{{ route('contract.archiveshow', $contract->url_address) }}"
                                                class="btn btn-custom-archive btn-compact">
                                                عرض الأرشيف
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                            </div>

                            {{-- Printing Card --}}
                            <div class="action-card">
                                <div class="action-card-title">🖨️ الطباعة</div>
                                <div class="action-card-buttons">
                                    <a href="{{ route('contract.temp', $contract->url_address) }}"
                                        class="btn btn-custom-print btn-compact">
                                        حجز اولي
                                    </a>
                                    <a href="{{ route('contract.reserve', $contract->url_address) }}"
                                        class="btn btn-custom-print btn-compact">
                                        استمارة حجز
                                    </a>

                                    @can('contract-print')
                                        @if ($contract->stage == 'authenticated' && count($contract->images) >= 1)
                                            <a href="{{ route('contract.print', $contract->url_address) }}"
                                                class="btn btn-custom-print btn-compact">
                                                عقد نهائي
                                            </a>
                                            <a href="{{ route('contract.onmap', $contract->url_address) }}"
                                                class="btn btn-custom-print btn-compact">
                                                على الخريطة
                                            </a>
                                            <a href="{{ route('contract.appendix', $contract->url_address) }}"
                                                class="btn btn-custom-print btn-compact">
                                                ملحق
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                            </div>

                            {{-- Transfers Card --}}
                            @can('transfer-create')
                                <div class="action-card">
                                    <div class="action-card-title">🔄 التناقلات</div>
                                    <div class="action-card-buttons">
                                        <a href="{{ route('transfer.create', ['contract_id' => $contract->id]) }}"
                                            class="btn btn-custom-transfer btn-compact">
                                            تناقل جديد
                                        </a>
                                        @can('transfer-show')
                                            <a href="{{ route('transfer.contract', $contract->url_address) }}"
                                                class="btn btn-custom-show btn-compact">
                                                عرض التناقلات
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endcan
                        @endif

                        {{-- Danger Zone Card --}}
                        @can('contract-terminate')
                            @if ($contract->stage !== 'terminated')
                                <div class="action-card" style="border-color: #dc3545;">
                                    <div class="action-card-title" style="color: #dc3545; border-bottom-color: #dc3545;">
                                        ⚠️ خطر</div>
                                    <div class="action-card-buttons">
                                        <a href="{{ route('contract.terminate', $contract->url_address) }}"
                                            class="btn btn-danger btn-compact"
                                            onclick="return confirm('هل أنت متأكد من فسخ العقد؟')">
                                            فسخ العقد
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endcan
                    </div>

                    <div>

                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.contract_info') }}
                        </h1>

                        <div class="progress-bar-container">
                            <!-- Background Line -->
                            <div class="progress-line-background"></div>
                            <!-- Foreground Line for Progress -->
                            <div class="progress-line-foreground"
                                style="width: {{ (($contract->getCurrentStageIndex() + 1) / count($contract::STAGES)) * 100 }}%;">
                            </div>

                            @foreach ($contract::STAGES as $index => $stage)
                                <div class="stage">
                                    <div
                                        class="circle {{ $index <= $contract->getCurrentStageIndex() ? 'completed' : '' }}">
                                        @if ($index <= $contract->getCurrentStageIndex())
                                            &#10003;
                                        @endif
                                    </div>
                                    <span
                                        class="stage-label {{ $index <= $contract->getCurrentStageIndex() ? 'completed-text' : '' }}">
                                        {{ __('word.' . $stage) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- ✅ CONTRACT INFO -->
                        <div class="info-section">
                            <h2>📋 {{ __('word.contract_info') }}</h2>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">{{ __('word.id') }}</span>
                                    <span class="info-value">{{ $contract->id }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.contract_date') }}</span>
                                    <span class="info-value">{{ $contract->contract_date }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.stage') }}</span>
                                    <span class="info-value">{{ __('word.' . $contract->stage) }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.stage_date') }}</span>
                                    <span class="info-value">
                                        @if ($contract->stage == 'temporary')
                                            {{ $contract->temporary_at }}
                                        @elseif ($contract->stage == 'accepted')
                                            {{ $contract->accepted_at }}
                                        @elseif ($contract->stage == 'authenticated')
                                            {{ $contract->authenticated_at }}
                                        @endif
                                    </span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.method_name') }}</span>
                                    <span class="info-value">{{ $contract->payment_method->method_name }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.contract_amount') }}</span>
                                    <span class="info-value">{{ number_format($contract->contract_amount, 0) }}
                                        دينار</span>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ CUSTOMER INFO -->
                        <div class="info-section">
                            <h2>👤 {{ __('word.customer_info') }}</h2>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">{{ __('word.contract_customer_id') }}</span>
                                    <span class="info-value">{{ $contract->customer->customer_full_name }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.customer_card_number') }}</span>
                                    <span class="info-value">{{ $contract->customer->customer_card_number }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.customer_phone') }}</span>
                                    <span class="info-value">{{ $contract->customer->customer_phone }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ BUILDING INFO -->
                        <div class="info-section">
                            <h2>🏠 {{ __('word.building_info') }}</h2>

                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">{{ __('word.building_type_id') }}</span>
                                    <span
                                        class="info-value">{{ $contract->building->building_type->type_name }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.building_category_id') }}</span>
                                    <span
                                        class="info-value">{{ $contract->building->building_category->category_name }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.contract_building_id') }}</span>
                                    <span class="info-value">{{ $contract->building->building_number }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.block_number') }}</span>
                                    <span class="info-value">{{ $contract->building->block_number }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.house_number') }}</span>
                                    <span class="info-value">{{ $contract->building->house_number }}</span>
                                </div>

                                <div class="info-item">
                                    <span class="info-label">{{ __('word.building_area') }}</span>
                                    <span class="info-value">{{ $contract->building->building_area }}</span>
                                </div>
                            </div>

                            @if ($contract->contract_note)
                                <div class="info-note">
                                    <strong>📝 {{ __('word.contract_note') }}:</strong>
                                    {{ $contract->contract_note }}
                                </div>
                            @endif
                        </div>

                        <!-- ✅ Installment Info Section -->
                        @if ($contract->stage !== 'terminated')
                            <div class="installment-section">
                                <h2>💰 {{ __('word.installment_info') }}</h2>

                                <div class="table-responsive">
                                    <table class="table-installments">
                                        <thead>
                                            <tr>
                                                <th>{{ __('word.installment_number') }}</th>
                                                <th>{{ __('word.installment_name') }}</th>
                                                <th>{{ __('word.installment_percent') }}</th>
                                                <th>{{ __('word.installment_amount') }}</th>
                                                <th>{{ __('word.installment_date') }}</th>
                                                <th>{{ __('word.installment_payment') }}</th>
                                                <th>{{ __('word.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $hide = 0; @endphp
                                            @foreach ($contract_installments as $installment)
                                                @php
                                                    $total = $installment->installment_amount;
                                                    $paid = $installment->paid_amount ?? 0;
                                                    $remain = $installment->getRemainingAmount();
                                                    $progress = $installment->getPaymentProgress();
                                                    $isDue = $installment->isDue($installment->installment_date);
                                                @endphp

                                                <tr>
                                                    <td>{{ $installment->sequence_number ?? $installment->installment->installment_number }}
                                                    </td>
                                                    <td>{{ $installment->installment->installment_name }}</td>
                                                    <td>{{ number_format(($installment->installment_amount / $contract->contract_amount) * 100, 2) }}%
                                                    </td>
                                                    <td>{{ number_format($installment->installment_amount, 0) }} دينار
                                                    </td>
                                                    <td>{{ $installment->installment_date }}</td>

                                                    <td>
                                                        {{-- Payment Status --}}
                                                        @if ($paid == 0)
                                                            @if ($isDue)
                                                                <span class="status-badge status-due">مستحق</span>
                                                            @else
                                                                <span class="status-badge status-upcoming">غير
                                                                    مستحق</span>
                                                            @endif
                                                        @else
                                                            <span
                                                                class="status-badge {{ $installment->payment && !$installment->payment->approved ? 'status-pending' : 'status-paid' }}">
                                                                {{ $installment->payment && !$installment->payment->approved ? 'بانتظار الموافقة' : 'مدفوع' }}
                                                            </span>
                                                            <div class="progress-bar-wrapper mt-1">
                                                                <div class="progress-bar-fill"
                                                                    style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <small class="text-muted">
                                                                <strong>المدفوع:</strong> {{ number_format($paid, 0) }}
                                                                /
                                                                <strong>المتبقي:</strong>
                                                                {{ number_format($remain, 0) }}
                                                            </small><br>
                                                            @if ($installment->payment && $installment->payment->approved)
                                                                <small class="text-success">تم الدفع في
                                                                    {{ $installment->payment->payment_date }}</small>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="table-action">
                                                            {{-- View Existing Payment --}}
                                                            @if ($contract->stage != 'temporary')
                                                                @if ($installment->payment)
                                                                    @can('payment-show')
                                                                        <a href="{{ route('payment.show', $installment->payment->url_address) }}"
                                                                            class="btn btn-custom-show">{{ __('word.view') }}</a>
                                                                    @endcan
                                                                @else
                                                                    {{-- Add Payment and Send SMS (first unpaid installment) --}}
                                                                    @if ($hide == 0)
                                                                        @can('payment-create')
                                                                            <a href="{{ route('contract.add', $installment->url_address) }}"
                                                                                class="add_payment btn btn-custom-edit">{{ __('word.add_payment') }}</a>
                                                                            @can('contract-sms')
                                                                                <form
                                                                                    action="{{ route('contract.sendSms') }}"
                                                                                    method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    <input type="hidden" name="phone_number"
                                                                                        value="{{ $contract->customer->customer_phone }}">
                                                                                    <input type="hidden" name="name"
                                                                                        value="{{ $contract->customer->customer_full_name }}">
                                                                                    <input type="hidden" name="amount"
                                                                                        value="{{ number_format($installment->installment_amount, 0) }} دينار">
                                                                                    <input type="hidden" name="due_date"
                                                                                        value="{{ $installment->installment_date }} الدفعة {{ $installment->installment->installment_name }} عن العقار المرقم {{ $contract->building->building_number }}">
                                                                                    <input type="hidden" name="contract_url"
                                                                                        value="{{ $contract->url_address }}">
                                                                                    <button type="submit"
                                                                                        class="btn btn-custom-print">SMS</button>
                                                                                </form>
                                                                            @endcan
                                                                        @endcan
                                                                        @php $hide = 1; @endphp
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="flex">
                            @if (isset($contract->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $contract->user_create->name }}
                                    {{ $contract->created_at }}
                                </div>
                            @endif

                            @if (isset($contract->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $contract->user_update->name }}
                                    {{ $contract->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAllActions() {
            const horizontal = document.getElementById('allActionsHorizontal');
            const icon = document.getElementById('toggleIcon');
            horizontal.classList.toggle('active');
            icon.textContent = horizontal.classList.contains('active') ? '▲' : '▼';
        }

        $(document).ready(function() {
            $('.add_payment').on('click', function(event) {
                if ($(this).data('clicked')) {
                    event.preventDefault();
                    return false;
                }
                $(this).data('clicked', true);
                $(this).text('جاري الاضافة');
            });
        });
    </script>

</x-app-layout>
