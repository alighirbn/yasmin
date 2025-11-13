<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">

        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="header-buttons no-print">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">{{ __('word.back') }}</a>

                        @can('contract-show')
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        <button onclick="window.print();" class="btn btn-custom-print">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="p-4" style="font-family:'Arial', sans-serif;">

                            {{-- Header --}}
                            <div style="display:flex; justify-content:space-between;">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div style="text-align:right;">
                                    {!! QrCode::size(90)->generate($contract->id) !!}
                                    <p><strong>العدد:</strong> {{ $contract->id }}</p>
                                    <p><strong>التاريخ:</strong> {{ now()->format('Y/m/d') }}</p>
                                </div>
                            </div>

                            {{-- Title --}}
                            <div style="text-align:center; font-size:1rem; font-weight:bold; margin-top:10px;">
                                <p>ملحق عقد رقم ({{ $contract->id }})</p>
                                <p>ملحق تعديل آلية التسديد ومدة الإنجاز</p>
                            </div>

                            {{-- Intro --}}
                            <div style="text-align:right; font-size:0.88rem; font-weight:bold; margin-top:15px;">
                                <p>
                                    استناداً إلى عقد بيع رقم
                                    ({{ $contract->id ?? 'غير محدد' }})
                                    الخاص بالوحدة السكنية رقم
                                    ({{ $contract->building->building_number ?? 'غير محدد' }})
                                    والمسجلة بتاريخ
                                    {{ \Carbon\Carbon::parse($contract->contract_date)->format('Y/m/d') }}
                                    بين الطرفين:
                                </p>

                                <br>
                                <p>الطرف الأول: شركة بوابة العلم للمقاولات والتجارة العامة والاستثمارات العقارية
                                    المحدودة المسؤولية / النجف الأشرف ويمثلها المدير المفوض إضافةً لوظيفته.</p>

                                <p>الطرف الثاني: ({{ $contract->customer->customer_full_name }})،
                                    رقم الهوية: {{ $contract->customer->customer_card_number }}،
                                    العنوان: {{ $contract->customer->full_address }}،
                                    رقم الهاتف: {{ $contract->customer->customer_phone }}</p>

                                <p>ولرغبة الطرف الثاني بتعديل آلية التسديد وموافقة الطرف الأول، اتفق الطرفان على ما يلي:
                                </p>
                            </div>

                            {{-- Payment Terms --}}
                            <div style="margin-top:12px; font-size:0.88rem; font-weight:bold; text-align:right;">
                                <p>أولاً: تعديل جدول التسديد</p>
                                <p>تم تعديل آلية تسديد بدل الوحدة السكنية الموضحة في البند (رابعاً) من العقد الأصلي،
                                    ويحل جدول السداد الجديد المرفق ربطاً محل الجدول السابق بالكامل.</p>

                            </div>
                            <br>
                            <br>
                            {{-- General Terms --}}
                            <div style="text-align:right; font-size:0.88rem; font-weight:bold; margin-top:12px;">
                                <p>ثانياً: أحكام عامة</p>

                                <p>١. تبقى جميع شروط العقد الأصلي نافذة وملزمة للطرفين ولا يُلغى منها شيء إلا ما ورد
                                    بشأنه في هذا الملحق.</p>
                                <p>٢. يعتبر هذا الملحق جزءاً مكملاً ومتمماً للعقد الأصلي.</p>
                                <p>٣. يتم احتساب مدة تسليم الوحدة السكنية البالغة (٣٦) شهراً من تاريخ توقيع هذا الملحق.
                                </p>
                                <p>٤. يقر الطرف الثاني بأن طلبه بتعديل جدول السداد جاء بناءً على رغبته ودون أي إكراه.
                                </p>
                                <p>٥. يلتزم الطرف الثاني بالتقيد بمواعيد السداد وفقاً لما ورد في هذا الملحق ويتحمل
                                    التبعات القانونية عند الإخلال.</p>
                                <p>٦. يتم تفسير هذا الملحق وفق القوانين العراقية النافذة وقانون الاستثمار رقم (١٣) لسنة
                                    (٢٠٠٦).</p>
                            </div>

                            {{-- Signatures --}}
                            <div style="text-align:center; margin-top:30px; font-size:0.9rem; font-weight:bold;">
                                <p>
                                    تم تحرير هذا الملحق في النجف الأشرف بتاريخ {{ now()->format('Y/m/d') }}
                                    من نسختين أصليتين، بيد كل طرف نسخة للعمل بموجبها.
                                </p>
                            </div>
                            <br>
                            <br>
                            <br>
                            <br>

                            <div class="flex ">
                                <div
                                    style="text-align: center; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p> الطرف الثاني
                                        <br>
                                        <br>
                                        <br> التوقيع /
                                        <br> الاسم / {{ $contract->customer->customer_full_name }}
                                        <br> رقم الهوية / {{ $contract->customer->customer_card_number }}
                                        <br> العنوان / {{ $contract->customer->full_address }}
                                    </p>
                                </div>
                                <div
                                    style="text-align: center; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p> الطرف الاول
                                        <br>
                                        <br>
                                        <br> التوقيع /
                                        <br> المدير المفوض لشركة بوابة العلم
                                        <br> للمقاولات العامة المحدودة/ اضافة
                                        <br> لوظيفته
                                    </p>
                                </div>
                            </div>

                            @if ($contract_installments->count() >= 1)
                                <br>
                                <br>
                                <br>
                                <div style="display: flex; justify-content: center;">
                                    <p style="font-size: 14px; font-weight: bold;">صفحة ( 1 - 1)</p>
                                </div>
                            @endif
                            <div
                                style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold; direction: rtl;">

                                @php
                                    // تقسيم 21 صف لكل صفحة
                                    $pages = $contract_installments->chunk(21);

                                    function nameStatic($ci, $method)
                                    {
                                        $n = $ci->sequence_number;

                                        if ($method == 3) {
                                            if ($n == 1) {
                                                return 'الدفعة المقدمة';
                                            }
                                            if ($ci->installment_type_id == 3) {
                                                return 'دفعة المفتاح';
                                            }
                                            return 'الدفعة الشهرية ' . App\Helpers\Number::convert($n - 1);
                                        }

                                        if ($method == 4) {
                                            return $n == 1
                                                ? 'الدفعة المقدمة'
                                                : 'الدفعة ' . App\Helpers\Number::convert($n);
                                        }

                                        return 'الدفعة ' . $ci->installment->installment_name;
                                    }
                                @endphp

                                <style>
                                    /* تثبيت الصفوف نفس ارتفاع جدولك الأصلي */
                                    .fixed-row td {
                                        height: 32px !important;
                                        max-height: 32px !important;
                                        min-height: 32px !important;
                                        white-space: nowrap;
                                        overflow: hidden;
                                    }

                                    .page-break {
                                        page-break-after: always;
                                    }
                                </style>

                                @foreach ($pages as $pageIndex => $chunk)
                                    @if ($pageIndex == 0)
                                        <div style="page-break-before: always;"></div>
                                    @endif
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    @if ($pageIndex == 0)
                                        <div
                                            style="text-align: right; margin: 1.2rem auto; font-size: 1rem; font-weight: bold; direction: rtl;">
                                            <p style="margin-bottom: 10px; text-align: center; font-size: 1.05rem;">
                                                جدول آلية التسديد
                                                <br>
                                                <span style="font-size: 0.95rem; font-weight: normal;">
                                                    استناداً إلى الفقرة ( اولاً) من ملحق العقد
                                                </span>
                                            </p>
                                        </div>
                                    @endif

                                    <table
                                        style="width:100%; border-collapse: collapse; text-align:right; font-weight: normal; table-layout: fixed;">

                                        <thead>
                                            <tr style="border-bottom: 2px solid #000;">
                                                <th style="padding: 6px; border:1px solid #000; width:5%;">#</th>
                                                <th style="padding: 6px; border:1px solid #000; width:20%;">نوع الدفعة
                                                </th>
                                                <th style="padding: 6px; border:1px solid #000; width:13%;">المبلغ رقماً
                                                </th>
                                                <th style="padding: 6px; border:1px solid #000; width:50%;">المبلغ
                                                    كتابةً</th>
                                                <th style="padding: 6px; border:1px solid #000; width:12%;">تاريخ
                                                    الاستحقاق</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($chunk as $ci)
                                                @php
                                                    $name = nameStatic($ci, $contract->contract_payment_method_id);
                                                @endphp

                                                <tr class="fixed-row">
                                                    <td style="padding:6px; border:1px solid #000;">
                                                        {{ $ci->sequence_number }}</td>
                                                    <td style="padding:6px; border:1px solid #000;">{{ $name }}
                                                    </td>
                                                    <td style="padding:6px; border:1px solid #000;">
                                                        {{ number_format($ci->installment_amount, 0) }}</td>
                                                    <td style="padding:6px; border:1px solid #000;">
                                                        {{ Numbers::TafqeetMoney($ci->installment_amount, 'IQD') }}
                                                    </td>
                                                    <td style="padding:6px; border:1px solid #000;">
                                                        {{ \Carbon\Carbon::parse($ci->installment_date)->format('Y/m/d') }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                    @if ($pageIndex < count($pages) - 1)
                                        <div class="page-break"></div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function convertNumbersToArabic() {
            const westernArabicNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            const easternArabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

            const walk = (node) => {
                let child, next;
                switch (node.nodeType) {
                    case Node.ELEMENT_NODE:
                    case Node.DOCUMENT_NODE:
                    case Node.DOCUMENT_FRAGMENT_NODE:
                        child = node.firstChild;
                        while (child) {
                            next = child.nextSibling;
                            walk(child);
                            child = next;
                        }
                        break;
                    case Node.TEXT_NODE:
                        node.nodeValue = node.nodeValue.replace(/\d/g, (digit) => {
                            return easternArabicNumerals[digit];
                        });
                        break;
                }
            };

            walk(document.body);
        }

        document.addEventListener("DOMContentLoaded", convertNumbersToArabic);
    </script>
</x-app-layout>
