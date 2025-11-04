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
                                <p>استناداً إلى عقد بيع الوحدة السكنية المبرم بتاريخ
                                    {{ \Carbon\Carbon::parse($contract->contract_date)->format('Y/m/d') }}
                                    بين:</p>

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
                                    ويحل جدول السداد الجديد محل الجدول السابق بالكامل:</p>

                                <div
                                    style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                    @php $counter = 1; @endphp

                                    @if (in_array($contract->contract_payment_method_id, [3, 4]))
                                        {{-- ✅ Variable/Flexible Payment Plan --}}

                                        @if ($variable_payment_details['down_payment_amount'] > 0)
                                            <p>
                                                {{ $counter++ }}- الدفعة المقدمة:
                                                {{ number_format($variable_payment_details['down_payment_amount']) }}
                                                د.ع
                                                ({{ Numbers::TafqeetMoney($variable_payment_details['down_payment_amount'], 'IQD') }})
                                                تدفع عند توقيع هذا الملحق
                                            </p>
                                        @endif

                                        @if ($variable_payment_details['monthly_installment_amount'] > 0 && $variable_payment_details['number_of_months'] > 0)
                                            <p>
                                                {{ $counter++ }}- الأقساط الشهرية:
                                                {{ number_format($variable_payment_details['monthly_installment_amount']) }}
                                                د.ع
                                                ({{ Numbers::TafqeetMoney($variable_payment_details['monthly_installment_amount'], 'IQD') }})
                                                × {{ $variable_payment_details['number_of_months'] }} شهر،
                                                تدفع شهرياً ابتداءً من
                                                {{ \Carbon\Carbon::parse($contract->contract_date)->addMonth()->format('Y/m/d') }}
                                            </p>
                                        @endif

                                        @if ($variable_payment_details['key_payment_amount'] > 0)
                                            <p>
                                                {{ $counter++ }}- دفعة المفتاح:
                                                {{ number_format($variable_payment_details['key_payment_amount']) }}
                                                د.ع
                                                ({{ Numbers::TafqeetMoney($variable_payment_details['key_payment_amount'], 'IQD') }})
                                                عند تسليم الوحدة السكنية
                                            </p>
                                        @endif
                                    @else
                                        {{-- ✅ Fixed Payment Plan (Method 1 & 2) --}}
                                        @foreach ($contract_installments as $ins)
                                            @php
                                                $name = $ins->installment->installment_name;
                                                $amount = number_format($ins->installment_amount);
                                                $words = Numbers::TafqeetMoney($ins->installment_amount, 'IQD');
                                                $date = \Carbon\Carbon::parse($ins->installment_date)->format('Y/m/d');
                                            @endphp

                                            <p>{{ $counter++ }}- الدفعة {{ $name }}:
                                                مقدارها ({{ $amount }} د.ع) كتابةً ({{ $words }}),
                                                وتستحق بتاريخ {{ $date }}</p>
                                        @endforeach
                                    @endif
                                </div>

                                <p>يعتبر هذا الجدول جزءاً لا يتجزأ من العقد ويحل محل الجدول السابق.</p>
                            </div>

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

                            <div style="display:flex; justify-content:space-between; margin-top:40px;">
                                <div style="text-align:center; width:45%; font-size:0.85rem; font-weight:bold;">
                                    <p>الطرف الثاني</p><br><br>
                                    <p>التوقيع: ____________</p>
                                    <p>الاسم: {{ $contract->customer->customer_full_name }}</p>
                                    <p>رقم الهوية: {{ $contract->customer->customer_card_number }}</p>
                                </div>

                                <div style="text-align:center; width:45%; font-size:0.85rem; font-weight:bold;">
                                    <p>الطرف الأول</p><br><br>
                                    <p>التوقيع: ____________</p>
                                    <p>المدير المفوض لشركة بوابة العلم</p>
                                    <p>للمقاولات العامة / إضافة لوظيفته</p>
                                </div>
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
