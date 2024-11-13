<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto  lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="p-4">

                            <div class="flex">
                                <div class=" mx-2 my-2 w-full ">
                                    {!! QrCode::size(90)->generate($contract->id) !!}
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    .
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    .
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract->building->building_number, 'C39') }}"
                                        alt="barcode" />

                                    <p><strong>{{ __('العدد :') }}</strong>
                                        {{ $contract->id }}
                                    </p>
                                    <p><strong>{{ __('التاريخ :') }}</strong> {{ $contract->contract_date }}</p>

                                </div>
                            </div>
                            <div style="text-align: center; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>عقد بيع </p>
                            </div>
                            <div style="text-align: center; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>(عقد بيع وحده سكنية في مجمع واحة الياسمين السكني / النجف الاشرف )</p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>الطرف الأول/ المدير المفوض لشركة بوابة العلم للمقاولات والتجارة العامة المحدودة/
                                    اضافة الى وظيفته. </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>الطرف الثاني/ ({{ $contract->customer->customer_full_name }}) العنوان
                                    (النجف الاشرف) رقم
                                    الموبايل({{ $contract->customer->customer_phone }})
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>بناءا على توفر وحده سكنية لدى الطرف الأول ضمن مجمع واحة الياسمين السكني والواقع في
                                    النجف الاشرف رقم ضمن القطعة المرقمة (56/1) مقاطعه (15 بحر النجف) بموجب الاجازة
                                    الاستثمارية
                                    المرقمة (318) في (18-2-2024).</p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>اولا/ اوصاف الوحده السكنية:
                                    ١-رقم الوحده السكنية ({{ $contract->building->house_number }}) رقم البلوك
                                    ({{ $contract->building->block_number }}) نوع الوحده السكنية
                                    ({{ $contract->building->building_type->type_name }})
                                    ٢- المساحة الكلية للوحده السكنية ({{ $contract->building->building_area }}) مساحة
                                    البناء({{ $contract->building->building_real_area }})
                                    <br>
                                    ثانيا / بدل شراء الوحده السكنية:
                                    رقما ({{ number_format($contract->contract_amount, 0) }})
                                    كتابتا({{ Numbers::TafqeetMoney($contract->contract_amount, 'IQD') }})
                                    <br>
                                    ثالثا/ آلية تسديد بدل الوحده السكنية تكون على شكل
                                    {{ $contract_installments->count() == 1 ? 'دفعة واحدة' : 'اثنى عشر دفعة' }} وكلاتي:
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>
                                    @foreach ($contract_installments as $contract_installment)
                                        @php

                                            $installment = $contract_installment->installment;
                                            $installmentNumber = $installment->installment_number;
                                            $installmentName = $installment->installment_name;
                                            $installmentPercent = $installment->installment_percent * 100;
                                            $installmentAmount = number_format(
                                                $contract_installment->installment_amount,
                                                0,
                                            );
                                            $installmentAmountText = Numbers::TafqeetMoney(
                                                $contract_installment->installment_amount,
                                                'IQD',
                                            );

                                            // Determine the payment timing with dynamic installment number conversion
                                            $paymentTiming =
                                                $installmentNumber == 1
                                                    ? 'عند ابرام العقد'
                                                    : 'بعد ثلاثة اشهر من الدفعه ' .
                                                        App\Helpers\Number::convert($installmentNumber - 1); // Dynamically change the installment number to its corresponding word
                                        @endphp

                                        {{ $installmentNumber . '- الدفعة ' . $installmentName . ': ( ' . $installmentPercent . ' %) من قيمة الكلية لبدل شراء الوحده السكنية وتدفع ' . $paymentTiming . ' ومقدارها ( ' . $installmentAmount . ') وكتابتا (' . $installmentAmountText . ') ' }}
                                        <br>
                                    @endforeach
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>في حالة رغبة الطرف الثاني الاستفاده من قرض مصرفي على الوحده السكنية وتسديدها الى
                                    الطرف الاول والبالغة مقدارها ( رقما) (وكتابتا) على ان يتحمل الطرف الثاني تسديد مبلغ
                                    القرض مع الارباح المترتبة عليه والمصاريف واتعاب المحاماة
                                    @if ($contract_installments->count() > 1)
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    @endif
                                    واي مصاريف اخرى ويبذل جهد
                                    من الطرف الأول لغرض الحصول على القرض من احد المصارف وفي ويعتبر مبلغ القرض سدادا لما
                                    يقابله من مبلغ الدفعات الاخيره المذكوره في الفقره ثالثا من الية تسديد بدل شراء
                                    الوحده السكنية المتفق عليها في هذا العقد.
                                    وفي حالة تعذر حصول الطرف الاول على قرض من احد المصارف فيتم تسديد المبلغ من قبل الطرف
                                    الثاني وفقا لالية تسديد الدفعات المتفق عليها في هذا العقد.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p><br> التزامات الطرف الأول:

                                    <br> _______________________
                                    <br> أولا/الطرف الأول بالجدول الزمني للمشروع والبناء وفق التصاميم الهندسية التفصيلية
                                    المصادق عليها من قبل هيئة استثمار النجف التي بموجبها تم منح الاجازه الاستثمارية.
                                    <br> ثانيا/ يلزم الطرف الاول بتسليم الوحده السكنية المنجزه المتعاقد عليها الى الطرف
                                    الثاني خالية من الشواغل عند انتهاء المده الممنوحة الى المشروع شرط أن يسدد الطرف
                                    الثاني جميع الالتزامات المالية قبل التسليم.
                                    <br> ثالثا/ يلتزم الطرف الأول بتوفير مركز متكامل لغرض الإجابة عن الأسئلة
                                    والاستفسارات الخاصه بالوحده السكنية وان المركز غير ملزم بالإجابة لغير المتعاقدين
                                    للحفاظ على سرية البيانات والمعلومات للمشتركين.
                                    <br> رابعا / يلتزم الطرف الأول باحكام الضمان الخاصه بالمشروع والتي التزم بها مع هيئة
                                    استثمار النجف الاشرف والقوانيين والانظمه الازمه بحدود تعلق الامر بالوحده السكنية
                                    موضوع العقد وخدماتها وبنيتها التحتية
                                    <br> <br> التزامات الطرف الثاني
                                    <br> _______________________
                                    <br> اولا/ يلتزم الطرف الثاني بتقديم البيانات الضرورية الخاصه به وتحديد محل اقامته
                                    ويكون هذا المحل هو العنوان المختار للتباليغ والانذارات وعند تغير عنوانه وجب عليه
                                    اعلام الطرف الأول بالمحل الجديد والا اعتبر العقد ملغى.
                                    <br> ثانيا/ في حالة تاخر الطرف الثاني عن التسديد المدرج في الفقره (ثالثا) من الية
                                    التسديد بدل شراء الوحده السكنية تضاف غرامة مقدارها (١٠%) من قيمة الدفعه المستحقه
                                    المتأخره عن كل شهر.
                                    @if ($contract_installments->count() == 1)
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    @endif <br> ثالثا/ في حالة تاخر الطرف الثاني عن التسديد بعد
                                    انتهاء مدة ثلاثون يوما جاز
                                    للطرف الأول فسخ العقد اذا لم يبادر الطرف الثاني بتسديد المبلغ والغرامات التأخيرية
                                    خلال (مدة خمسة عشريوما) من انتهاء الثلاثون يوم دون الحاجة الى انذاره عند طريق الكاتب
                                    العدل ودون الحاجة إلى اللجوء إلى القضاء استنادا الى احكام الماده(١٧٧و ١٧٨)من القانون
                                    المدني العراقي ويحق للطرف الأول التصرف بالعقار للغير على إن يتحمل الطرف الثاني
                                    الغرامات التأخيرية لحين احلال مشتري اخر محله ويستحق الطرف الثاني )٥٠%) من المبلغ
                                    الواصل للطرف الأول وفي حالة عدم استلام الطرف الثاني للمبلغ المستحق له جاز للطرف
                                    الأول ايداع المبلغ لدى الدوائر المختصه.

                                    <br> <br> احكام عامة
                                    <br> _______________________
                                    <br> أولا/ لايمكن بيع الوحده السكنية او التنازل عنها للغير او للخلف الخاص او الهبة
                                    باي شكل من اشكال العمليات التصرفية الا بالموافقة التحريرية من الطرف الأول.
                                    <br> ثانيا/ يلتزم الطرف الأول بنقل ملكية الوحده السكنية إلى الطرف الثاني في دائرة
                                    التسجيل العقاري في النجف الاشرف على إن يتحمل الطرف الثاني جميع الرسوم والمصاريف
                                    واتعاب المحاماة التى تقتضيها اجراءات نقل الملكية.
                                    <br> ثالثا/ في حالة رغبت الطرف الثاني او وكيله المخول قانونا بفسخ العقد على ان يقدم
                                    طلب إلى الطرف الأول لاستحصال موافقته ويتم استقطاع مبلغ مقداره (٥٠%) من المبلغ
                                    المستلم من قبل الطرف الأول ويسلم المبلغ المتبقي والبالغ قيمته(٥٠%)الى الطرف الثاني
                                    بعد بيع الوحده السكنية مشتري آخر.
                                    @if ($contract_installments->count() > 1)
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    @endif <br> رابعا/ يلتزم الطرف الثاني بدفع مبلغ مقداره (---)
                                    وذلك عن الخدمات وتشمل (
                                    الحراسات و شركة التنظيف والبستنه وكامرات المراقبه ).
                                    <br> خامسا/ تكون العمله العراقيه هي المعتمده في تسديد أقساط الوحده السكنية.
                                    <br> سادسا/ يلتزم الطرف الثاني بدفع جميع الرسوم المستحقه للدوائر والازمه لتسجيل
                                    الوحده السكنية في دائرة التسجيل العقاري والدوائر ذات العلاقه.
                                    <br> سابعا/ في حالة حدوث ظروف طارئة او قاهرة يراعى ذلك بالنسبة للمدد الزمنية المحدده
                                    من قبل الطرف الأول بخصوص تسليم الوحده السكنية ويحق للطرف الأول تمديد المدد الزمنية
                                    الخاصه بتسليم الوحده السكنية إلى الطرف الثاني.
                                    <br> ثامنا/ في حالة انهيار سوق العقار في العراق فان الطرف الأول غير ملزم بتغير اسعار
                                    الوحده السكنية موضوع هذا العقد
                                    <br> تاسعا/ تكون مدة تسليم الوحده السكنية الى الطرف الثاني مدة ( ٣٦شهر) تبدأ من
                                    تاريخ ابرام العقد ولا يحق للطرف الثاني مطالبة الطرف الأول بتسليم الدار قبل هذه
                                    الفتره حتى وان تم نقل ملكية الوحده السكنية الى الطرف الثاني.
                                    <br> عاشرا/ اي نزاع ينشأ بسبب هذا العقد تكون محكمة بداءة النجف هي المحكمة المختصه
                                    بالنظر في هذا النزاع.
                                    <br> الحادي عشر/ يخضع هذا العقد لقانون الاستثمار رقم (١٣) لسنة (٢٠٠٦) وتعديلاته
                                    وانظمته وتعليماته.
                                </p>
                            </div>

                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p> الثاني عشر/ تم انعقاد هذا العقد بأرادة الطرفين بتاريخ
                                    {{ \Carbon\Carbon::now()->format('Y/m/d') }}</p>

                            </div>
                            <br>
                            <br>
                            <br>

                            <div class="flex ">
                                <div
                                    style="text-align: center; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p> الطرف الثاني
                                        <br> الاسم / {{ $contract->customer->customer_full_name }}
                                        <br> رقم الهوية / {{ $contract->customer->customer_card_number }}
                                        <br> العنوان / النجف الاشرف
                                    </p>
                                </div>
                                <div
                                    style="text-align: center; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p> الطرف الاول
                                        <br> المدير المفوض لشركة بوابة العلم
                                        <br> للمقاولات العامة المحدودة/ اضافة
                                        <br> وظيفته
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
