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
                                    <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                        style="h-6;max-width: 100%; height: auto;">
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($contract->building->building_number, 'C39') }}"
                                        alt="barcode" />

                                    <p><strong>{{ __('رقم العقد:') }}</strong>
                                        {{ $contract->id }}
                                    </p>
                                    <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $contract->contract_date }}</p>

                                </div>
                            </div>

                            <div style="text-align: center; margin: 0.8rem auto; font-size: 0.875rem;">
                                <p><strong>عقد شراء دار في مجمع واحة الياسمين الاستثماري السكني - النجف الاشرف</strong>
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>الطرف الاول - البائع - عبدالله صبحي عيسى / المدير المفوض لشركة بوابة العلم
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>الطرف الثاني - المشتري - {{ $contract->customer->customer_full_name }} بموجب الهوية
                                    المرقمة {{ $contract->customer->customer_card_number }}
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>إتفق الطرفان على ما يلي :</p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>اولاً - إستناد إلى إجازة الاستثمار المرقمة (318) والصادرة من هيئة إستثمار النجف
                                    الأشرف
                                    في (18-2-2024) ولتوفير دار سكني لدى الطرف الأول فقد اتفق الطرفان على قيام الطرف
                                    الأول
                                    بيعه الى الطرف الثاني الدار المرقمة ({{ $contract->building->house_number }}) ضمن
                                    البلوك المرقم ({{ $contract->building->block_number }}) على قطعة أرض مساحتها
                                    ({{ $contract->building->building_area }}) متر ضمن مجمع واحة الياسمين الاستثماري
                                    السكني في
                                    النجف
                                    الاشرف .</p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>ثانياً - يكون التعامل بالدينار العراقي فقط.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>ثالثاً
                                    - إن بدل شراء الدار مبلغاً إجمالياً قدره (
                                    {{ number_format($contract->contract_amount, 0) }}) دينار
                                    عراقي فقط لا غير. يكون تسديد مبلغ الدار على {{ $contract_installments->count() }}
                                    دفعات<br>
                                    @foreach ($contract_installments as $contract_installment)
                                        {{ $contract_installment->installment->installment_number .
                                            '- الدفعة ' .
                                            $contract_installment->installment->installment_name .
                                            ' ( ' .
                                            $contract_installment->installment->installment_percent * 100 .
                                            ' %) من قيمة شراء الدار تدفع في ' .
                                            $contract_installment->installment_date .
                                            ' المبلغ قدره ' .
                                            number_format($contract_installment->installment_amount, 0) .
                                            ' دينار عراقي فقط لا غير. ' }}
                                        <br>
                                    @endforeach
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>رابعاً - في حالة رغبة المشتري الاستفادة من قرض تمويل أحد المصارف فيتم ذلك بموجب عقد
                                    منفصل
                                    بين
                                    الطرف الثاني والمصرف كما أن الطرف الأول. سيبذل الجهد من أجل استحصال موافقة أحد
                                    المصارف عند توفر قرض التمويل
                                    على ما يحدده المصرف من مبلغ للرهن من قيمة العقد على أن يبقى الاتفاق على هذا
                                    التمويل وشروطه بين الطرف الثاني والمصرف بعيداً عن أي التزام على الطرف الأول بشرط
                                    انزال مبلغ القرض في حساب الطرف الاول.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>خامساً - يلتزم الطرف الثاني بتسديد الأقساط المشار إليها في الفقرة (ثالثاً-رابعاً) من
                                    بنود العقد
                                    وفي
                                    الأوقات المحددة إزاء كل منها وفي حاله عدم تسديد الدفعات المستحقة أعلاه بوقتها المحدد
                                    أو
                                    التلكؤ في تسديد الدفعة المستحقة بعد مرور 15 يوم من تاريخ الاستحقاق يحق للطرف الأول
                                    فسخ
                                    العقد دون الحاجه الى أي انذار رسمي واعادة تمليك الوحدة السكنية الى شخص اخر بعد سحبها
                                    من الطرف الثاني ويتم
                                    اعادة ما
                                    نسبتة (50%) من مجموع المبالغ المستلمة الى الطرف الثاني ويسقط حق الطرف الثاني
                                    بالمطالبة
                                    ببقيه المبلغ ويصبح من حق الطرف الأول ولا يحق للطرف الثاني المطالبة بهذا المبلغ
                                    مطلقاً مع
                                    الاعتبار ان استفادة الطرف الثاني من مدة السماح البالغة (15 يوم) تكون لمرة واحده فقط
                                    وفي
                                    حاله تلكؤ الطرف الثاني مره أخرى فمن حق الطرف الأول فسخ العقد واعادة تمليك الوحدة
                                    السكنية
                                    الى شخص اخر دون امهال المشتري لاي مدة اخرى ودون الحاجه الى انذار رسمي ولا يحق للطرف
                                    الثاني المطالبة بالأموال التي سددها وتصبح من حق الطرف الاول.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>سادساً - في حالة رغبة الطرف الثاني فسخ العقد وتنظيم عقد لمشتري جديد فينظم عقد
                                    للمستفيد الجديد بعد موافقة الطرف الاول ودفع الطرف الثاني مبلغ وقدرة 5،000،000 دينار
                                    للطرف الاول كأجور تنظيم عقد جديد وتسري بنود هذا العقد الجديد على الطرف الذي تحول له
                                    الوحدة السكنية.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>سابعاً - لا يحق للطرف الثاني التنازل عن العقد او تحويله الى شخص اخر بالوكاله او
                                    التخويل
                                    وانما يتم التنازل عن العقد بحضوره شخصياً ويكون التوقيع على التنازل من قبله بالذات .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>ثامناً - في حالة مطالبة الطرف الثاني اجراء بعض الاظافات الداخلية فقط للوحدة السكنية
                                    يكون ملزم بدفع كافة النفقات والمصاريف بعد موافقة الطرف الاول والتنفيذ من قبل القسم
                                    الهندسي للشركة حصراً.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>تاسعاً - عند التعرض الى اي ظروف طارئه استثنائيه قاهره يراعى ذلك بالنسبه للمده الخاصه
                                    بانهاء العمل ولاجل ذلك يحق للطرف الأول تمديد مدة تسليم الوحدات السكنيه للفتره الخاصه
                                    بتوقف الاعمال نتيجه للظروف القاهره ويلتزم الطرف الثاني بقبول ذلك .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>عاشراً - يلتزم الطرف الثاني بدفع جميع الرسوم والمصاريف المتعلقة للدوائر ذات العلاقة
                                    اللازم
                                    دفعها لتسجيل الوحدة السكنية بما في ذلك رسوم جميع الدوائر الخدمية واتعاب المحاماة.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>احد عشر - في حالة مطالبة الطرف الثاني بفسخ العقد لأي سبب كان يتم استقطاع 50 % من قيمة
                                    المبلغ المدفوع من قبل الطرف الاول ولايحق للطرف الثاني المطالبة بأي مبلغ اخر.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>اثنى عشر - يلتزم الطرف الثاني بتقديم البيانات المطلوبة الخاصة به وتحديد محل اقامته
                                    ويكون هذا المحل هو العنوان المختار للتبليغ والانذارات وعنده تغيره وجب عليه اعلام
                                    الطرف الاول بالمحل الجديد والا يعتبر اخلالا بالالتزام.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>ثلاثة عشر - في حال غياب الطرف الثاني المستفيد او فقدانه او انقطعت اخباره او فارق
                                    الحياة او فقد الاهلية ولم يراجع ذويه او راجعوا دون التوصل الى حل فيما يخص المبالغ
                                    التي في ذمته الناشئة على الوحدة السكنية او استحالت تنفيذ التزاماته الواردة في هذا
                                    العقد يتم ايداع الاموال المذكوره في الفقرة خامسا من هذا العقد 50% في الدوائر
                                    المختصة.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>اربعة عشر - في حالة نشوء اي خلاف بين اطراف العقد يكون القضاء العراقي ومحكمة بداءة
                                    النجف هي المختصة بالفصل في النزاع الناشئ.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>خمسة عشر - يلتزم الطرف الثاني بدفع اجور الخدمات وتشمل الحراسات ورفع النفايات والخدمات
                                    التشغيلية الخاصة بالمشروع وتحدد من قبل الطرف الاول.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p>سته عشر - تكون مده تسليم الدار الى الطرف الثاني بمدة لا تتجاوز 36 شهرا من تاريخ توقيع
                                    العقد
                                    ولا يحق للطرف الثاني مطالبه الطرف الاول بتسليم الدار قبل هذة الفترة حتى وان تم تسجيل
                                    الدار بأسمه في
                                    دائرة التسجيل العقاري.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0.8rem auto; font-size: 0.9rem; font-weight: bold;">
                                <p> سبعة عشر - على بركة الله حرر هذا العقد في النجف الأشرف بتاريخ
                                    {{ $contract->contract_date }}</p>
                            </div>
                            <div class="flex ">
                                <div
                                    style="text-align: right; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>اسم وتوقيع الطرف الثاني (المشتري)
                                    </p>
                                </div>
                                <div
                                    style="text-align: right; margin: 1rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>إسم وتوقيع الطرف الاول (البائع)</p>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    style="text-align: right; margin: 0.8rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>{{ $contract->customer->customer_full_name }}</p>
                                </div>

                                <div
                                    style="text-align: right; margin: 0.8rem auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>عبدالله صبحي عيسى / المدير المفوض</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
