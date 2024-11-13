<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <style>
            .short-rows td {
                padding: 4px !important;
            }

            .short-rows td {
                padding: 4px 8px !important;
                /* Adjust padding to control row height */
            }

            .short-rows tr {
                height: 30px;
                /* Set a fixed height for rows, adjust as needed */
            }
        </style>
        <div class="flex justify-start">
            @include('contract.nav.navigation')
            @include('service.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">

                    <!-- Header Buttons -->
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan
                        @can('map-empty')
                            <a href="{{ route('map.empty') }}" class="btn btn-custom-transfer">
                                {{ __('word.map_empty_buildings') }}
                            </a>
                        @endcan
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto  bg-white">
                        <!-- Document Content Structure -->
                        <div class="document-content">
                            <div class="flex">
                                <div class=" mx-2 my-2 w-full ">
                                    {!! QrCode::size(90)->generate($contract->id) !!}
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <div
                                        style="text-align: center; margin: 1rem auto; font-size: 1.2rem; font-weight: bold;">
                                        <p> {{ __('word.reservation_form') }}</p>
                                    </div>
                                    <!-- Reservation Information -->
                                    <h2 class="text-center font-bold">{{ __('word.reservation_project') }}</h2>
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                        style="h-6;max-width: 100%; height: auto;">
                                </div>

                            </div>

                            <table class="table table-bordered table-striped short-rows">
                                <tr>
                                    <td>{{ __('word.reservation_date') }}</td>
                                    <td>{{ $contract->contract_date ?? ' ' }}</td>
                                    <td> </td>
                                    <td> </td>

                                </tr>
                                <tr>
                                    <td>{{ __('word.customer_full_name') }}</td>
                                    <td>{{ $contract->customer->customer_full_name ?? ' ' }}</td>
                                    <td>{{ __('word.mother_full_name') }}</td>
                                    <td>{{ $contract->mother_full_name ?? ' ' }}</td>

                                </tr>
                                <tr>
                                    <td>{{ __('word.customer_card_number') }}</td>
                                    <td>{{ $contract->customer->customer_card_number ?? ' ' }}</td>

                                    <td>{{ __('word.customer_card_issud_auth') }}</td>
                                    <td>{{ $contract->customer->customer_card_issud_auth ?? ' ' }}</td>

                                </tr>
                                <tr>
                                    <td>{{ __('word.full_address') }}</td>
                                    <td>{{ $contract->customer->full_address ?? ' ' }}</td>

                                    <td>{{ __('word.address_card_number') }}</td>
                                    <td>{{ $contract->customer->address_card_number ?? ' ' }}</td>

                                </tr>

                                <tr>
                                    <td>{{ __('word.saleman') }}</td>
                                    <td>{{ $contract->customer->saleman ?? ' ' }}</td>

                                    <td>{{ __('word.customer_phone') }}</td>
                                    <td>{{ $contract->customer->customer_phone ?? ' ' }}</td>

                                </tr>
                            </table>

                            <!-- Unit Information -->
                            <h3 class="text-center font-semibold my-2">{{ __('word.unit_information') }}</h3>
                            <table class="table table-bordered table-striped short-rows">
                                <tr>
                                    <td>{{ __('word.contract_building_id') }}</td>
                                    <td>{{ $contract->building->building_number ?? ' ' }}</td>
                                    <td>{{ __('word.building_type_id') }}</td>
                                    <td>{{ $contract->building->building_type->type_name ?? ' ' }}</td>

                                </tr>

                                <tr>
                                    <td>{{ __('word.building_area') }}</td>
                                    <td>{{ $contract->building->building_area ?? ' ' }}</td>

                                    <td>{{ __('word.building_real_area') }}</td>
                                    <td>{{ $contract->building->building_real_area ?? ' ' }}</td>

                                </tr>

                            </table>

                            <!-- Financial Information -->
                            <h3 class="text-center font-semibold my-2">{{ __('word.financial_information') }}</h3>
                            <table class="table table-bordered table-striped short-rows">
                                <tr>
                                    <td>{{ __('word.contract_payment_method_id') }}</td>
                                    <td>{{ $contract->payment_method->method_name ?? ' ' }}</td>
                                    <td>{{ __('word.contract_amount') }}</td>
                                    <td>{{ number_format($contract->contract_amount, 0) . ' دينار ' ?? ' ' }}</td>
                                </tr>
                            </table>

                            <!-- Additional Information and Notes -->
                            <div class="my-4">
                                <h4 class="font-semibold">{{ __('word.reservation_notes') }}</h4>
                                <p style=" font-size: 0.9rem; font-weight: bold;">
                                    <br> 1- لا تعتبر هذه الاستمارة بمثابة عقد ولا جزء من عقد البيع ولا يترتب أي التزامات
                                    على الشركة.
                                    <br> 2- الحجز ساري لحين وصول رسالة من الشركة للحضور لتسديد الدفعى الاولى و بعد تاريخ
                                    الحجز اعلاه وبعدها سيتم الغاء الحجز تلقائيا في حال لم يتم تسديد الدفعة الأولى لشراء
                                    الوحدة السكنية .
                                    <br> 3- يحضر على المشتري التنازل عن استمارة حجز الوحدة السكنية للغير ويعد التنازل
                                    باطلا
                                    <br> 4- في حال التعاقد بالوكالة يقر الوكيل بان البيانات الواردة بالنسبه له وللمشتري
                                    صحيحة وان وكالته سارية المفعول ويكون هوه المسؤول عما ورد من بيانات .
                                    <br> 5- يقر الزبون أنه اطلع على عقد البيع وعقد الخدمات وانه احاط علما بكافة بنوده
                                    وابدى استعداده لتوقيع العقد بعد تسديد الدفعة الأولى لشراء الوحدة السكنية ويقر بعلمه
                                    بتقديم الضمانات المتفق عليها بالمبالغ المتبقية من خلال تقديم ( صكوك وكمبيالات مصدقة
                                    ).
                                    <br> 6- يقر الزبون يعلمه ان عمولة الحجز البالغة ( . . . . . . . . ) هي عمولة ادارية
                                    لتنظيم وتثبيت
                                    الحجز وان المبلغ غير قابل للرد حتى وان لم يتم التعاقد وتم الغاء الحجز وهو مبلغ مقطوع
                                    لا يدخل ضمن سعر الوحدة السكنية.
                                    <br> 7- في حال تسديد الدفعة الأولى لشراء الوحدة السكنية في أحد المصارف المعتمدة من
                                    قبل الشركة يجب احضار وصل التسديد ضمن فترة سريان الحجز اعلاه وبخلافه يمكن للشركة
                                    التصرف بالوحدة السكنية وتعويض الزبون بوحدة سكنية بديلة دون اعتراض الزبون وعلى أن لا
                                    تتعدى مدة احضار الوصل أكثر من خمسة ايام من تاريخ الاستمارة وبخلافه يسقط حق الزبون
                                    نهائيا ويتم الرجوع الى بنود العقد وتطبيقها.
                                    <br> 8- اني الموقع ادناه والمذكورة معلوماتي اعلاه اقر باني قد قرأت هذه الاستمارة
                                    وتفاصيلها واطلعت على كافة بنودها واتعهد بعدم المطالبة باسترجاع عمولة الحجز كونه غير
                                    قابل للرد واتعهد بدفع الدفعة الأولى لشراء الوحدة السكنية حسب الفقرة (1) اعلاه من
                                    تاريخ هذه الاستمارة وبخلافه يسقط حقي بالمطالبة بالوحدة السكنية دون الرجوع الى
                                    المحاكم المختصة
                                </p>
                            </div>

                            <!-- Signature Section -->
                            <div class="signature-section flex justify-around mt-6">
                                <div class="text-center">
                                    <p>{{ __('word.agent_signature') }}</p>
                                    <p>__________________________</p>
                                </div>
                                <div class="text-center">
                                    <p>{{ __('word.customer_signature') }}</p>
                                    <p>__________________________</p>
                                </div>
                                <div class="text-center">
                                    <p>{{ __('word.agent_signature') }}</p>
                                    <p>__________________________</p>
                                </div>
                                <div class="text-center">
                                    <p>{{ __('word.customer_signature') }}</p>
                                    <p>__________________________</p>
                                </div>
                            </div>

                            <!-- Created and Updated By -->

                        </div>

                    </div>
                    <div class="flex justify-start mt-6">
                        @if (isset($contract->user_id_create))
                            <div class="mx-4">
                                {{ __('word.user_create') }} {{ $contract->user_create->name }} -
                                {{ $contract->created_at }}
                            </div>
                        @endif
                        @if (isset($contract->user_id_update))
                            <div class="mx-4">
                                {{ __('word.user_update') }} {{ $contract->user_update->name }} -
                                {{ $contract->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
