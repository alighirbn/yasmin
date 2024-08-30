<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
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
                        @can('contract-update')
                            <a href="{{ route('contract.edit', $contract->url_address) }}" class="btn btn-custom-edit">
                                {{ __('word.contract_edit') }}
                            </a>
                        @endcan
                        <button id="print" class="btn btn-custom-statement" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="p-4">

                            <div class="flex">
                                <div class=" mx-2 my-2 w-full ">
                                    {!! QrCode::size(70)->generate($contract->id) !!}
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                        style="h-6;max-width: 60%; height: auto;">
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <p><strong>{{ __('رقم العقد:') }}</strong>
                                        {{ $contract->id }}
                                    </p>
                                    <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $contract->contract_date }}</p>

                                </div>
                            </div>

                            <div style="text-align: center; margin: 0 auto; font-size: 0.875rem;">
                                <p><strong>عقد شراء دار في مجمع واحة الياسمين السكني - النجف الاشرف</strong> </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>الطرف الاول - البائع - إدارة مجمع واحة الياسمين السكني في النجف الاشرف </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>الطرف الثاني - المشتري - {{ $contract->customer->customer_full_name }} بموجب الهوية
                                    المرقمة {{ $contract->customer->customer_card_number }}
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>إتفق الطرفان على ما يلي :</p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>اولاً - إستناد إلى إجازة الاستثمار المرقمة (318) والصادرة من هيئة إستثمار النجف
                                    الأشرف
                                    في (18-2-2024) ولتوفير دار سكني لدى الطرف الأول فقد اتفق الطرفان على قيام الطرف
                                    الأول
                                    بيعه الى الطرف الثاني الدار المرقمة ({{ $contract->building->block_number }}) ضمن
                                    البلوك المرقم ({{ $contract->building->house_number }}) على قطعة أرض مساحتها
                                    ({{ $contract->building->building_area }}) متر ضمن مجمع واحة الياسمين السكني في
                                    النجف
                                    الاشرف .</p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>ثانياً
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
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>ثالثا - في حالة رغبة المشتري الاستفادة من تمويل أحد المصارف فيتم ذلك بموجب عقد منفصل
                                    بين
                                    الطرف الثاني والمصرف كما أن الطرف الأول. سيبذل الجهد من أجل استحصال موافقة أحد
                                    المصارف
                                    على تمويل ما يحدده المصرف من مبلغ للرهن من قيمة العقد على أن يبقى الاتفاق على هذا
                                    التمويل وشروطه بين الطرف الثاني والمصرف بعيداً عن أي التزام على الطرف الأول.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>رابعا - يلتزم الطرف الثاني بتسديد الأقساط المشار إليها في الفقرة (2-3) من بنود العقد
                                    وفي
                                    الأوقات المحددة إزاء كل منها وفي حاله عدم تسديد الدفعات المستحقة أعلاه بوقتها المحدد
                                    أو
                                    التلكؤ في تسديد الدفعة المستحقة بعد مرور 30 يوم من تاريخ الاستحقاق يحق للطرف الأول
                                    فسخ
                                    العقد دون الحاجه الى أي انذار رسمي واعادة تمليك الوحدة السكنية الى شخص اخر ويتم
                                    اعادة ما
                                    نسبتة (50%) من مجموع المبالغ المستلمة الى الطرف الثاني ويسقط حق الطرف الثاني
                                    بالمطالبة
                                    ببقيه المبلغ ويصبح من حق الطرف الأول ولا يحق للطرف الثاني المطالبة بهذا المبلغ
                                    مطلقاً مع
                                    الاعتبار ان استفادة الطرف الثاني من مدة السماح البالغة (30 يوم) تكون لمرة واحده فقط
                                    وفي
                                    حاله تلكؤ الطرف الثاني مره أخرى فمن حق الطرف الأول فسخ العقد واعادة تمليك الوحدة
                                    السكنية
                                    الى شخص اخر دون امهال المشتري لاي مدة اخرى ودون الحاجه الى انذار رسمي ولا يحق للطرف
                                    الثاني المطالبة بالأموال التي سددها وتصبح من حق الطرف الاول .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>خامسا - في حاله رغبه الطرف الثاني (المشتري) ببيع الوحدة السكنية الى شخص اخر او
                                    التنازل عن
                                    العقد فيجب ان يقترن ذلك بموافقة الطرف الاول على أن يدفع الطرف الثاني رسوم لذلك تحدد
                                    فيما
                                    بعد من قبل الطرف الاول وعند تاريخ التحويل .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>سادسا - لا يحق للطرف الثاني التنازل عن العقد او تحويله الى شخص اخر بالوكاله او
                                    التخويل
                                    وانما يتم التنازل عن العقد بحضوره شخصياً ويكوت التوقيع على التنازل من قبله بالذات .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>سابعا - لا يحق للطرف الثاني مطالبه الطرف الأول باي اعمال خارج بنود العقد وخارج
                                    التصاميم
                                    الأساسية والمخططات الخاصة بالمشروع من خدمات اضافيه او فقرات مكمله خارج بنود العقد
                                    وخارج
                                    التصميم والمخططات الخاصة بالمشروع. وفي حاله القيام بها واكمالها فان الطرف الأول
                                    يستحق
                                    قيمتها وقت انشاءها واكمال العمل بها .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>ثامنا - عند التعرض الى اي ظروف طارئه استثنائيه قاهره يراعى ذلك بالنسبه للمده الخاصه
                                    بانهاء العمل ولاجل ذلك يحق للطرف الأول تمديد مد تسليم الوحدات السكنيه للفتره الخاصه
                                    بتوقف الاعمال نتيجه للظروف القاهره ويلتزم الطرف الثاني بقبول ذلك .
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>تاسعا - يلتزم الطرف الثاني بدفع جميع الرسوم والمصاريف المتعلقة للدوائر ذات العلاقة
                                    اللازم
                                    دفعها لتسجيل الوحدة السكنية بما في ذلك رسوم جميع الدوائر الخدمية.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p>عاشرا - تكون مده تسليم الدار الى الطرف الثاني بمدة لا تتجاوز 30 شهرا من تاريخ توقيع
                                    العقد
                                    ولا يحق للطرف الثاني مطالبه الطرف الاول بتسليم الدار قبل هذة الفترة حتى وان تم تسجيل
                                    الدار بأسمه في
                                    دائرة التسجيل العقاري.
                                </p>
                            </div>
                            <div style="text-align: right; margin: 0 auto; font-size: 0.8rem; font-weight: bold;">
                                <p> احد عشر - على بركة الله حرر هذا العقد في النجف الأشرف بتاريخ
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
                                <div style="text-align: right; margin: 0 auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>{{ $contract->customer->customer_full_name }}</p>
                                </div>

                                <div style="text-align: right; margin: 0 auto; font-size: 0.875rem; font-weight: bold;">
                                    <p>.....</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
