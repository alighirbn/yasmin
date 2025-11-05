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
        </style>

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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
                                    $downPaymentPaid = $contract->payments()->where('approved', true)->exists();
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
                        {{-- Navigation Card --}}
                        <div class="action-card">
                            <div class="action-card-title">🧭 التنقل</div>
                            <div class="action-card-buttons">
                                @can('contract-statement')
                                    <a href="{{ route('contract.statement', $contract->url_address) }}"
                                        class="btn btn-custom-statement btn-compact">
                                        كشف الحساب
                                    </a>
                                @endcan
                            </div>
                        </div>

                        @if ($contract->stage !== 'terminated')
                            {{-- Contract Actions Card --}}
                            <div class="action-card">
                                <div class="action-card-title">⚙️ إجراءات العقد</div>
                                <div class="action-card-buttons">
                                    @can('contract-update')
                                        @if ($contract->payments->count() > 0 && $contract->stage == 'temporary')
                                            <a href="#passwordModal" class="btn btn-custom-edit btn-compact"
                                                data-bs-toggle="modal">
                                                تعديل (مؤمن)
                                            </a>
                                        @else
                                            <a href="{{ route('contract.edit', $contract->url_address) }}"
                                                class="btn btn-custom-edit btn-compact">
                                                تعديل
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
                                        طباعة عقد
                                    </a>
                                    <a href="{{ route('contract.reserve', $contract->url_address) }}"
                                        class="btn btn-custom-print btn-compact">
                                        إيصال حجز
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
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
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

                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="id" class="w-full mb-1" :value="__('word.id')" />
                                <p id="id" class="w-full h-9 block mt-1 " type="text" name="id">
                                    {{ $contract->id }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_date">
                                    {{ $contract->contract_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="stage" class="w-full mb-1" :value="__('word.stage')" />
                                <p id="stage" class="w-full h-9 block mt-1 " type="text" name="stage">
                                    {{ __('word.' . $contract->stage) }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="stage_date" class="w-full mb-1" :value="__('word.stage_date')" />
                                <p id="stage_date" class="w-full h-9 block mt-1 " type="text" name="stage_date">
                                    @if ($contract->stage == 'temporary')
                                        {{ $contract->temporary_at }}
                                    @elseif ($contract->stage == 'accepted')
                                        {{ $contract->accepted_at }}
                                    @elseif ($contract->stage == 'authenticated')
                                        {{ $contract->authenticated_at }}
                                    @endif
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="method_name" class="w-full mb-1" :value="__('word.method_name')" />
                                <p id="method_name" class="w-full h-9 block mt-1 " type="text"
                                    name="method_name">
                                    {{ $contract->payment_method->method_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_amount" class="w-full mb-1" :value="__('word.contract_amount')" />
                                <p id="contract_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_amount">
                                    {{ number_format($contract->contract_amount, 0) }} دينار
                            </div>

                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.customer_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                                <p id="contract_customer_id" class="w-full h-9 block mt-1" type="text"
                                    name="contract_customer_id">
                                    {{ $contract->customer->customer_full_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1" type="text"
                                    name="customer_card_number">
                                    {{ $contract->customer->customer_card_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                <p id="customer_phone" class="w-full h-9 block mt-1" type="text"
                                    name="customer_phone">
                                    {{ $contract->customer->customer_phone }}
                            </div>
                        </div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.building_info') }}
                        </h1>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_type_id" class="w-full mb-1" :value="__('word.building_type_id')" />
                                <p id="building_type_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_type_id">
                                    {{ $contract->building->building_type->type_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1 " type="text"
                                    name="building_category_id">

                                    {{ $contract->building->building_category->category_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                                <p id="contract_building_id" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_building_id">
                                    {{ $contract->building->building_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1 " type="text"
                                    name="block_number">
                                    {{ $contract->building->block_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text"
                                    name="house_number">
                                    {{ $contract->building->house_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1 " type="text"
                                    name="building_area">
                                    {{ $contract->building->building_area }}
                            </div>

                        </div>

                        <div class="flex">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                                <p id="contract_note" class="w-full h-9 block mt-1 " type="text"
                                    name="contract_note">
                                    {{ $contract->contract_note }}
                            </div>
                        </div>
                        @if ($contract->stage !== 'terminated')
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.installment_info') }}
                            </h1>
                            <div class="container mt-4">

                                <table class="table table-striped">
                                    <thead>
                                        <th scope="col" width="14%">{{ __('word.installment_number') }}</th>
                                        <th scope="col" width="14%">{{ __('word.installment_name') }}</th>
                                        <th scope="col" width="14%">{{ __('word.installment_percent') }}</th>
                                        <th scope="col" width="14%">{{ __('word.installment_amount') }}</th>
                                        <th scope="col" width="14%">{{ __('word.installment_date') }}</th>
                                        <th scope="col" width="14%">{{ __('word.installment_payment') }}</th>
                                        <th scope="col" width="14%">{{ __('word.action') }}</th>
                                    </thead>
                                    @php
                                        $hide = 0;
                                    @endphp
                                    @foreach ($contract_installments as $installment)
                                        <tr>
                                            <td>{{ $installment->sequence_number ?? $installment->installment->installment_number }}
                                            </td>
                                            <td>{{ $installment->installment->installment_name }}</td>
                                            <td>{{ number_format(($installment->installment_amount / $contract->contract_amount) * 100, 2) }}
                                                %</td>
                                            <td>{{ number_format($installment->installment_amount, 0) }} دينار</td>
                                            <td>{{ $installment->installment_date }}</td>
                                            <td>
                                                @php
                                                    $total = $installment->installment_amount;
                                                    $paid = $installment->paid_amount ?? 0;
                                                    $remain = $installment->getRemainingAmount();
                                                    $progress = $installment->getPaymentProgress();
                                                @endphp

                                                @if ($paid == 0)
                                                    @if ($installment->isDue($installment->installment_date))
                                                        <span style="color: red;">لم تسدد - مستحقة</span>
                                                    @else
                                                        <span>لم تسدد - غير مستحقة</span>
                                                    @endif
                                                @else
                                                    <div>
                                                        <strong>المدفوع:</strong> {{ number_format($paid, 0) }}
                                                        دينار<br>
                                                        <strong>المتبقي:</strong> {{ number_format($remain, 0) }}
                                                        دينار<br>

                                                    </div>

                                                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-green-500 h-2 rounded-full"
                                                            style="width: {{ $progress }}%"></div>
                                                    </div>

                                                    @if ($installment->payment && $installment->payment->approved)
                                                        <small class="text-success">تم الدفع في
                                                            {{ $installment->payment->payment_date }}</small>
                                                    @elseif ($installment->payment)
                                                        <small class="text-warning">بانتظار الموافقة</small>
                                                    @endif
                                                @endif
                                            </td>

                                            <td>
                                                @if ($contract->stage != 'temporary')
                                                    @if ($installment->payment != null)
                                                        @can('payment-show')
                                                            <a href="{{ route('payment.show', $installment->payment->url_address) }}"
                                                                class="btn btn-custom-show">
                                                                {{ __('word.view') }}
                                                            </a>
                                                        @endcan
                                                    @else
                                                        @if ($hide == 0)
                                                            @can('payment-create')
                                                                <div class="flex">

                                                                    <div class=" mx-2 my-2 w-full ">
                                                                        <a href="{{ route('contract.add', $installment->url_address) }}"
                                                                            class="add_payment btn btn-custom-edit">
                                                                            {{ __('word.add_payment') }}
                                                                        </a>

                                                                    </div>
                                                                    @can('contract-sms')
                                                                        <div class=" mx-2 my-2 w-full ">
                                                                            <form action="{{ route('contract.sendSms') }}"
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
                                                                                    class="btn btn-custom-print">
                                                                                    sms
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    @endcan
                                                                </div>
                                                                @php
                                                                    $hide = 1;
                                                                @endphp
                                                            @endcan
                                                        @else
                                                        @endif
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
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

    <div id="passwordModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content  text-gray-900">
                <form action="{{ route('contract.edit', $contract->url_address) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">تأكيد كلمة المرور</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>يرجى إدخال كلمة المرور الخاصة بك لتأكيد التعديل.</p>
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-custom-statement">تأكيد</button>
                        <button type="button" class="btn btn-custom-transfer" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
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
