<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('customer-create')
                            <button id="openModal" class="btn btn-primary">
                                {{ __('word.customer_add') }}
                            </button>
                        @endcan
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Modal Structure -->
                    <div id="customerModal"
                        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-500 bg-opacity-75 flex items-center justify-center p-10">
                        <div class="bg-white rounded-lg shadow-lg max-w-7xl w-full p-10">
                            <button id="closeModal" class="text-gray-800 hover:text-gray-900">&times;</button>
                            <form id="customerForm" method="post" action="{{ route('contract.customerstore') }}">
                                @csrf
                                <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 w-full">
                                    {{ __('word.customer_info') }}
                                </h1>
                                <!-- Customer fields -->
                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                        <x-text-input id="customer_full_name" class="w-full block mt-1" type="text"
                                            name="customer_full_name" value="{{ old('customer_full_name') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="mother_full_name" class="w-full mb-1" :value="__('word.mother_full_name')" />
                                        <x-text-input id="mother_full_name" class="w-full block mt-1" type="text"
                                            name="mother_full_name" value="{{ old('mother_full_name') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                        <x-text-input id="customer_phone" class="w-full block mt-1" type="text"
                                            name="customer_phone" value="{{ old('customer_phone') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_email" class="w-full mb-1" :value="__('word.customer_email')" />
                                        <x-text-input id="customer_email" class="w-full block mt-1" type="text"
                                            name="customer_email" value="{{ old('customer_email') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>

                                <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4 w-full">
                                    {{ __('word.customer_card') }}
                                </h2>
                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_number" class="w-full mb-1"
                                            :value="__('word.customer_card_number')" />
                                        <x-text-input id="customer_card_number" class="w-full block mt-1" type="text"
                                            name="customer_card_number" value="{{ old('customer_card_number') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_issud_auth" class="w-full mb-1"
                                            :value="__('word.customer_card_issud_auth')" />
                                        <x-text-input id="customer_card_issud_auth" class="w-full block mt-1"
                                            type="text" name="customer_card_issud_auth"
                                            value="{{ old('customer_card_issud_auth') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="customer_card_issud_date" class="w-full mb-1"
                                            :value="__('word.customer_card_issud_date')" />
                                        <x-text-input id="customer_card_issud_date" class="w-full block mt-1"
                                            type="text" name="customer_card_issud_date"
                                            value="{{ old('customer_card_issud_date') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>

                                <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4 w-full">
                                    {{ __('word.customer_address') }}
                                </h2>
                                <div class="flex">
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="full_address" class="w-full mb-1" :value="__('word.full_address')" />
                                        <x-text-input id="full_address" class="w-full block mt-1" type="text"
                                            name="full_address" value="{{ old('full_address') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="address_card_number" class="w-full mb-1"
                                            :value="__('word.address_card_number')" />
                                        <x-text-input id="address_card_number" class="w-full block mt-1"
                                            type="text" name="address_card_number"
                                            value="{{ old('address_card_number') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                    <div class="mx-4 my-4 w-full">
                                        <x-input-label for="saleman" class="w-full mb-1" :value="__('word.saleman')" />
                                        <x-text-input id="saleman" class="w-full block mt-1" type="text"
                                            name="saleman" value="{{ old('saleman') }}" />
                                        <div class="input-error w-full mt-2"></div>
                                    </div>
                                </div>

                                <div class="mx-4 my-4 w-full">
                                    <x-primary-button id="submitButton" class="ml-4">
                                        {{ __('word.save') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contract Form -->
                    <form method="post" action="{{ route('contract.store') }}">
                        @csrf
                        <input type="hidden" id="user_id_create" name="user_id_create"
                            value="{{ auth()->user()->id }}">

                        <h1 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                            {{ __('word.contract_info') }}
                        </h1>

                        <div class="flex">
                            <!-- Customer Select -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_customer_id" class="w-full mb-1" :value="__('word.contract_customer_id')" />
                                <select id="contract_customer_id" class="js-example-basic-single w-full block mt-1"
                                    name="contract_customer_id" data-placeholder="ادخل اسم الزبون">
                                    <option value=""></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('contract_customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_customer_id')" class="w-full mt-2" />
                            </div>

                            <!-- Building Select -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_building_id" class="w-full mb-1" :value="__('word.contract_building_id')" />
                                <select id="contract_building_id" class="js-example-basic-single w-full block mt-1"
                                    name="contract_building_id" data-placeholder="ادخل رقم العقار">
                                    <option value=""></option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            data-price="{{ $building->calculatePrice() }}"
                                            {{ $building->id == $building_id ? 'selected' : '' }}>
                                            {{ $building->building_number }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_building_id')" class="w-full mt-2" />
                            </div>

                            <!-- Payment Method -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_payment_method_id" class="w-full mb-1"
                                    :value="__('word.contract_payment_method_id')" />
                                <select id="contract_payment_method_id"
                                    class="js-example-basic-single w-full block mt-1"
                                    name="contract_payment_method_id" data-placeholder="ادخل طريقة الدفع">
                                    <option value=""></option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}"
                                            data-is-variable="{{ $payment_method->method_name === 'دفعات متغيرة' ? 'true' : 'false' }}"
                                            data-is-flexible="{{ $payment_method->method_name === 'دفعات مرنة' ? 'true' : 'false' }}"
                                            {{ old('contract_payment_method_id') == $payment_method->id ? 'selected' : '' }}>
                                            {{ $payment_method->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('contract_payment_method_id')" class="w-full mt-2" />
                            </div>
                        </div>

                        <div class="flex">
                            <!-- Contract Date -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <x-text-input id="contract_date" class="w-full block mt-1" type="text"
                                    name="contract_date"
                                    value="{{ old('contract_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                    placeholder="yyyy-mm-dd" />
                                <x-input-error :messages="$errors->get('contract_date')" class="w-full mt-2" />
                            </div>

                            <!-- Contract Amount -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_amount_display" class="w-full mb-1" :value="__('word.contract_amount')" />
                                <x-text-input id="contract_amount_display" class="w-full block mt-1" type="text"
                                    value="{{ number_format((float) old('contract_amount', 0), 0) }}"
                                    placeholder="0" />
                                <input type="hidden" id="contract_amount" name="contract_amount"
                                    value="{{ old('contract_amount', 0) }}">
                                <x-input-error :messages="$errors->get('contract_amount')" class="w-full mt-2" />
                            </div>

                            <!-- Discount -->
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="discount" class="w-full mb-1" :value="__('word.discount')" />
                                <x-text-input id="discount" class="w-full block mt-1" type="number"
                                    name="discount" value="{{ old('discount', 0) }}" step="0.5" min="0"
                                    max="100" placeholder="0.00" />
                                <x-input-error :messages="$errors->get('discount')" class="w-full mt-2" />
                            </div>
                        </div>

                        <!-- Variable Payment Plan (Method 3) -->
                        <div id="variable-payment-fields" class="hidden">
                            <h2 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-4 w-full">
                                {{ __('word.variable_payment_plan') }}
                            </h2>
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="down_payment_amount_display" class="w-full mb-1"
                                        :value="__('word.down_payment_amount')" />
                                    <x-text-input id="down_payment_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('down_payment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="down_payment_amount" name="down_payment_amount"
                                        value="{{ old('down_payment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('down_payment_amount')" class="w-full mt-2" />
                                    <div id="down_payment_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="down_payment_installment_display" class="w-full mb-1"
                                        :value="__('word.down_payment_installment')" />
                                    <x-text-input id="down_payment_installment_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('down_payment_installment', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="down_payment_installment"
                                        name="down_payment_installment"
                                        value="{{ old('down_payment_installment', 0) }}">
                                    <x-input-error :messages="$errors->get('down_payment_installment')" class="w-full mt-2" />
                                    <div id="down_payment_installment_error" class="text-red-600 text-sm mt-2 hidden">
                                    </div>
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="monthly_installment_amount_display" class="w-full mb-1"
                                        :value="__('word.monthly_installment_amount')" />
                                    <x-text-input id="monthly_installment_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('monthly_installment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="monthly_installment_amount"
                                        name="monthly_installment_amount"
                                        value="{{ old('monthly_installment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('monthly_installment_amount')" class="w-full mt-2" />
                                    <div id="monthly_installment_error" class="text-red-600 text-sm mt-2 hidden">
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="number_of_months" class="w-full mb-1" :value="__('word.number_of_months')" />
                                    <input id="number_of_months" name="number_of_months" type="range"
                                        min="1" max="120" value="{{ old('number_of_months', 36) }}"
                                        class="w-full" aria-label="Number of months for payment plan"
                                        aria-valuemin="1" aria-valuemax="120"
                                        aria-valuenow="{{ old('number_of_months', 36) }}">
                                    <span id="months-label" class="text-gray-700">36</span>
                                    <x-input-error :messages="$errors->get('number_of_months')" class="w-full mt-2" />
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="deferred_type" class="w-full mb-1" :value="__('word.deferred_type')" />
                                    <select id="deferred_type" class="w-full block mt-1" name="deferred_type">
                                        <option value="none" {{ old('deferred_type') == 'none' ? 'selected' : '' }}>
                                            {{ __('word.none') }}
                                        </option>
                                        <option value="spread"
                                            {{ old('deferred_type') == 'spread' ? 'selected' : '' }}>
                                            {{ __('word.spread') }}
                                        </option>
                                        <option value="lump-6"
                                            {{ old('deferred_type') == 'lump-6' ? 'selected' : '' }}>
                                            {{ __('word.lump_with_6th') }}
                                        </option>
                                        <option value="lump-7"
                                            {{ old('deferred_type') == 'lump-7' ? 'selected' : '' }}>
                                            {{ __('word.lump_with_7th') }}
                                        </option>
                                    </select>
                                    <x-input-error :messages="$errors->get('deferred_type')" class="w-full mt-2" />
                                    <div id="deferred_type_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="deferred_months" class="w-full mb-1" :value="__('word.deferred_months')" />
                                    <x-text-input id="deferred_months" class="w-full block mt-1" type="number"
                                        name="deferred_months" value="{{ old('deferred_months', 0) }}"
                                        min="0" placeholder="0" />
                                    <x-input-error :messages="$errors->get('deferred_months')" class="w-full mt-2" />
                                    <div id="deferred_months_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="key_payment_amount_display" class="w-full mb-1"
                                        :value="__('word.key_payment_amount')" />
                                    <x-text-input id="key_payment_amount_display"
                                        class="w-full block mt-1 bg-gray-100" type="text" readonly
                                        value="{{ number_format((float) old('key_payment_amount', 0), 0) }}" />
                                    <input type="hidden" id="key_payment_amount" name="key_payment_amount"
                                        value="{{ old('key_payment_amount', 0) }}">
                                    <x-input-error :messages="$errors->get('key_payment_amount')" class="w-full mt-2" />
                                    <div id="key_payment_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                            </div>

                            <!-- Payment Breakdown -->
                            <div class="mx-4 my-4 w-full">
                                <table class="table-auto w-full border text-sm">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="px-2 py-1">{{ __('word.item') }}</th>
                                            <th class="px-2 py-1">{{ __('word.amount') }}</th>
                                            <th class="px-2 py-1">{{ __('word.quantity') }}</th>
                                            <th class="px-2 py-1">{{ __('word.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment-breakdown"></tbody>
                                    <tfoot>
                                        <tr class="font-bold bg-gray-100">
                                            <td colspan="3" class="px-2 py-1 text-right">{{ __('word.total') }}
                                            </td>
                                            <td id="breakdown-total" class="px-2 py-1">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="payment-plan-error" class="text-red-600 text-sm mt-2 hidden"></div>
                            </div>
                        </div>

                        <!-- Flexible Payment Plan (Method 4) -->
                        <div id="flexible-payment-fields" class="hidden">
                            <h2 class="font-semibold underline text-l text-gray-900 leading-tight mx-4 my-4 w-full">
                                دفعات مرنة
                            </h2>

                            <!-- Reusing down_payment_amount for total down payment -->
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="flex_down_payment_amount_display" class="w-full mb-1"
                                        :value="'إجمالي الدفعة المقدمة (نقد + مؤجل)'" />
                                    <x-text-input id="flex_down_payment_amount_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('down_payment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <div id="flex_down_payment_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>

                                <!-- Reusing down_payment_installment for cash portion -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="flex_down_payment_installment_display" class="w-full mb-1"
                                        :value="'المبلغ النقدي الآن من الدفعة المقدمة'" />
                                    <x-text-input id="flex_down_payment_installment_display" class="w-full block mt-1"
                                        type="text"
                                        value="{{ number_format((float) old('down_payment_installment', 0), 0) }}"
                                        placeholder="0" />
                                    <div id="flex_down_payment_installment_error"
                                        class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>

                                <!-- NEW FIELD: down_payment_deferred_installment for deferred piece amount -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="down_payment_deferred_installment_display" class="w-full mb-1"
                                        :value="'مبلغ كل قسط مؤجل من الدفعة المقدمة'" />
                                    <x-text-input id="down_payment_deferred_installment_display"
                                        class="w-full block mt-1" type="text"
                                        value="{{ number_format((float) old('down_payment_deferred_installment', 0), 0) }}"
                                        placeholder="0" />
                                    <input type="hidden" id="down_payment_deferred_installment"
                                        name="down_payment_deferred_installment"
                                        value="{{ old('down_payment_deferred_installment', 0) }}">
                                    <div id="down_payment_deferred_installment_error"
                                        class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                            </div>

                            <div class="flex">
                                <!-- NEW FIELD: down_payment_deferred_frequency -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="down_payment_deferred_frequency" class="w-full mb-1"
                                        :value="'تكرار المؤجل (كل كم شهر)'" />
                                    <select id="down_payment_deferred_frequency" class="w-full block mt-1"
                                        name="down_payment_deferred_frequency">
                                        @foreach ([1, 2, 3, 4, 5, 6] as $m)
                                            <option value="{{ $m }}"
                                                {{ old('down_payment_deferred_frequency', 1) == $m ? 'selected' : '' }}>
                                                {{ "كل {$m} شهر" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="mx-4 my-2">

                            <!-- Reusing monthly_installment_amount for monthly payment -->
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="flex_monthly_installment_amount_display" class="w-full mb-1"
                                        :value="'مبلغ القسط الدوري'" />
                                    <x-text-input id="flex_monthly_installment_amount_display"
                                        class="w-full block mt-1" type="text"
                                        value="{{ number_format((float) old('monthly_installment_amount', 0), 0) }}"
                                        placeholder="0" />
                                    <div id="flex_monthly_installment_error" class="text-red-600 text-sm mt-2 hidden">
                                    </div>
                                </div>

                                <!-- Reusing number_of_months for installment count -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="flex_number_of_months" class="w-full mb-1"
                                        :value="'عدد الأقساط'" />
                                    <x-text-input id="flex_number_of_months" class="w-full block mt-1" type="number"
                                        min="0" max="360" value="{{ old('number_of_months', 0) }}" />
                                    <div id="flex_number_of_months_error" class="text-red-600 text-sm mt-2 hidden">
                                    </div>
                                </div>

                                <!-- NEW FIELD: monthly_frequency -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="monthly_frequency" class="w-full mb-1" :value="'تكرار القسط (كل كم شهر)'" />
                                    <select id="monthly_frequency" class="w-full block mt-1"
                                        name="monthly_frequency">
                                        @foreach ([1, 2, 3, 4, 5, 6] as $m)
                                            <option value="{{ $m }}"
                                                {{ old('monthly_frequency', 1) == $m ? 'selected' : '' }}>
                                                {{ "كل {$m} شهر" }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- NEW FIELD: monthly_start_date -->
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="monthly_start_date" class="w-full mb-1" :value="'تاريخ بدء الأقساط'" />
                                    <x-text-input id="monthly_start_date" class="w-full block mt-1" type="text"
                                        name="monthly_start_date"
                                        value="{{ old('monthly_start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                        placeholder="yyyy-mm-dd" />
                                    <div id="monthly_start_date_error" class="text-red-600 text-sm mt-2 hidden"></div>
                                </div>
                            </div>

                            <!-- Reusing key_payment_amount -->
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="flex_key_payment_amount_display" class="w-full mb-1"
                                        :value="'دفعة المفتاح (سيتم حسابها تلقائياً إن لزم)'" />
                                    <x-text-input id="flex_key_payment_amount_display"
                                        class="w-full block mt-1 bg-gray-100" type="text" readonly
                                        value="{{ number_format((float) old('key_payment_amount', 0), 0) }}" />
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="mx-4 my-4 w-full">
                                <table class="table-auto w-full border text-sm">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="px-2 py-1">البند</th>
                                            <th class="px-2 py-1">المبلغ</th>
                                            <th class="px-2 py-1">العدد/التكرار</th>
                                            <th class="px-2 py-1">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody id="flexible-breakdown"></tbody>
                                    <tfoot>
                                        <tr class="font-bold bg-gray-100">
                                            <td colspan="3" class="px-2 py-1 text-right">الإجمالي</td>
                                            <td id="flexible-breakdown-total" class="px-2 py-1">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="flexible-plan-error" class="text-red-600 text-sm mt-2 hidden"></div>
                            </div>
                        </div>

                        <div class="mx-4 my-4 w-full">
                            <x-input-label for="contract_note" class="w-full mb-1" :value="__('word.contract_note')" />
                            <x-text-input id="contract_note" class="w-full block mt-1" type="text"
                                name="contract_note" value="{{ old('contract_note') }}" />
                            <x-input-error :messages="$errors->get('contract_note')" class="w-full mt-2" />
                        </div>

                        <div class="mx-4 my-4 w-full">
                            <x-primary-button id="contract-submit"
                                class="ml-4">{{ __('word.save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select fields
            $('.js-example-basic-single').select2();

            // Modal handling
            var $modal = $('#customerModal');
            var $closeModal = $('#closeModal');
            var $customerForm = $('#customerForm');
            var $submitButtonModal = $('#submitButton');
            var $customerSelect = $('#contract_customer_id');

            // Open modal
            $('#openModal').on('click', function() {
                $modal.removeClass('hidden');
            });

            // Close modal
            $closeModal.on('click', function() {
                $modal.addClass('hidden');
            });

            // Handle customer form submission (AJAX)
            $customerForm.on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $submitButtonModal.text('{{ __('word.saving') }}').prop('disabled', true);

                $.ajax({
                    url: $customerForm.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {
                    if (data.success) {
                        var newOption = $('<option>', {
                            value: data.customer.id,
                            text: data.customer.customer_full_name
                        });
                        $customerSelect.append(newOption).val(data.customer.id).trigger('change');
                        $modal.addClass('hidden');
                    } else {
                        $('.input-error').html('');
                        $.each(data.errors, function(field, errors) {
                            var $errorElement = $(`#${field} ~ .input-error`);
                            if ($errorElement.length) {
                                $errorElement.html(errors.join('<br>'));
                            }
                        });
                    }
                }).fail(function(error) {
                    console.error('Error:', error);
                }).always(function() {
                    $submitButtonModal.text('{{ __('word.save') }}').prop('disabled', false);
                });
            });

            // Contract form elements
            var $discountInput = $('#discount');
            var $contractAmountDisplay = $('#contract_amount_display');
            var $contractAmount = $('#contract_amount');
            var $paymentMethodSelect = $('#contract_payment_method_id');
            var $variablePaymentFields = $('#variable-payment-fields');
            var $flexiblePaymentFields = $('#flexible-payment-fields');

            // Variable payment fields
            var $downPaymentDisplay = $('#down_payment_amount_display');
            var $downPayment = $('#down_payment_amount');
            var $downPaymentInstallmentDisplay = $('#down_payment_installment_display');
            var $downPaymentInstallment = $('#down_payment_installment');
            var $monthlyInstallmentDisplay = $('#monthly_installment_amount_display');
            var $monthlyInstallment = $('#monthly_installment_amount');
            var $numberOfMonths = $('#number_of_months');
            var $monthsLabel = $('#months-label');
            var $deferredType = $('#deferred_type');
            var $deferredMonths = $('#deferred_months');
            var $keyPaymentDisplay = $('#key_payment_amount_display');
            var $keyPayment = $('#key_payment_amount');

            // Flexible payment fields (reusing existing fields)
            var $flexDownPaymentDisplay = $('#flex_down_payment_amount_display');
            var $flexDownPaymentInstallmentDisplay = $('#flex_down_payment_installment_display');
            var $flexDownPaymentDeferredInstallmentDisplay = $('#down_payment_deferred_installment_display');
            var $flexDownPaymentDeferredInstallment = $('#down_payment_deferred_installment');
            var $flexDownPaymentDeferredFrequency = $('#down_payment_deferred_frequency');
            var $flexMonthlyInstallmentDisplay = $('#flex_monthly_installment_amount_display');
            var $flexNumberOfMonths = $('#flex_number_of_months');
            var $flexMonthlyFrequency = $('#monthly_frequency');
            var $flexMonthlyStartDate = $('#monthly_start_date');
            var $flexKeyPaymentDisplay = $('#flex_key_payment_amount_display');

            var $contractSubmitButton = $('#contract-submit');

            // Helpers
            function formatNumber(value) {
                return value.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function unformatNumber(value) {
                return (value || '').toString().replace(/,/g, '');
            }

            function formatInput($displayInput, $hiddenInput, $errorElement) {
                $displayInput.on('input', function() {
                    var numericValue = unformatNumber($displayInput.val());
                    if (isNaN(numericValue) || numericValue < 0) {
                        if ($errorElement) {
                            $errorElement.text('{{ __('word.invalid_amount') }}').removeClass('hidden');
                        }
                        $contractSubmitButton.prop('disabled', true);
                    } else {
                        if ($errorElement) {
                            $errorElement.text('').addClass('hidden');
                        }
                        var formattedValue = formatNumber(numericValue);
                        $displayInput.val(formattedValue);
                        if ($hiddenInput) {
                            $hiddenInput.val(numericValue);
                        }

                        if (isVariableSelected()) {
                            validatePaymentPlan();
                            autoCalculateKeyPayment();
                        } else if (isFlexibleSelected()) {
                            calculateFlexiblePlan();
                        }
                    }
                });
            }

            // Initialize formatting for variable payment inputs
            formatInput($downPaymentDisplay, $downPayment, $('#down_payment_error'));
            formatInput($downPaymentInstallmentDisplay, $downPaymentInstallment, $(
                '#down_payment_installment_error'));
            formatInput($monthlyInstallmentDisplay, $monthlyInstallment, $('#monthly_installment_error'));
            formatInput($contractAmountDisplay, $contractAmount, null);

            // Initialize formatting for flexible payment inputs (without hidden fields as they share with variable)
            formatInput($flexDownPaymentDisplay, null, $('#flex_down_payment_error'));
            formatInput($flexDownPaymentInstallmentDisplay, null, $('#flex_down_payment_installment_error'));
            formatInput($flexDownPaymentDeferredInstallmentDisplay, $flexDownPaymentDeferredInstallment, $(
                '#down_payment_deferred_installment_error'));
            formatInput($flexMonthlyInstallmentDisplay, null, $('#flex_monthly_installment_error'));

            // Format deferred months input
            $deferredMonths.on('input', function() {
                var value = parseInt($(this).val()) || 0;
                if (value < 0) {
                    $('#deferred_months_error').text('{{ __('word.invalid_deferred_months') }}')
                        .removeClass('hidden');
                    $contractSubmitButton.prop('disabled', true);
                } else {
                    $('#deferred_months_error').text('').addClass('hidden');
                    $(this).val(value);
                    validatePaymentPlan();
                    autoCalculateKeyPayment();
                }
            });

            // Check if variable payment plan is selected
            function isVariableSelected() {
                var $selectedOption = $paymentMethodSelect.find('option:selected');
                return $selectedOption.data('is-variable') === true || $selectedOption.attr('data-is-variable') ===
                    'true';
            }

            // Check if flexible payment plan is selected
            function isFlexibleSelected() {
                var $selectedOption = $paymentMethodSelect.find('option:selected');
                return $selectedOption.data('is-flexible') === true || $selectedOption.attr('data-is-flexible') ===
                    'true';
            }

            // Calculate contract amount with discount
            function calculateContractAmount() {
                var $selectedOption = $('#contract_building_id').find('option:selected');
                var basePrice = parseFloat($selectedOption.data('price')) || 0;
                var discount = parseFloat($discountInput.val()) || 0;
                var discountedAmount = basePrice - (basePrice * (discount / 100));

                $contractAmount.val(discountedAmount);
                $contractAmountDisplay.val(formatNumber(discountedAmount.toString()));

                if (isVariableSelected()) {
                    validatePaymentPlan();
                    autoCalculateKeyPayment();
                } else if (isFlexibleSelected()) {
                    calculateFlexiblePlan();
                }
            }

            // Validate payment plan (Method 3 - Variable)
            function validatePaymentPlan() {
                if (!isVariableSelected()) {
                    $contractSubmitButton.prop('disabled', false);
                    $('#payment-plan-error, #down_payment_installment_error, #deferred_type_error, #deferred_months_error')
                        .addClass('hidden');
                    return;
                }

                let discountedAmount = parseFloat($contractAmount.val()) || 0;
                let down = parseFloat(unformatNumber($downPaymentDisplay.val())) || 0;
                let downInstallment = parseFloat(unformatNumber($downPaymentInstallmentDisplay.val())) || 0;
                let monthly = parseFloat(unformatNumber($monthlyInstallmentDisplay.val())) || 0;
                let months = parseInt($numberOfMonths.val()) || 0;
                let deferredTypeValue = $deferredType.val();
                let deferredMonthsCount = parseInt($deferredMonths.val()) || 0;
                let deferred = down - downInstallment;
                let $errorElement = $('#payment-plan-error');
                let $downInstallmentError = $('#down_payment_installment_error');
                let $deferredTypeError = $('#deferred_type_error');
                let $deferredMonthsError = $('#deferred_months_error');
                let hasError = false;

                // Reset errors
                $errorElement.text('');
                $downInstallmentError.text('');
                $deferredTypeError.text('');
                $deferredMonthsError.text('');

                // Total payment validation
                let monthlyTotal = 0;
                if (deferredTypeValue === 'spread' && deferredMonthsCount > 0) {
                    monthlyTotal = (monthly + Math.floor(deferred / deferredMonthsCount)) * deferredMonthsCount +
                        monthly * (months - deferredMonthsCount);
                    if (deferred % deferredMonthsCount > 0) {
                        monthlyTotal += deferred % deferredMonthsCount;
                    }
                } else if (deferredTypeValue.startsWith('lump-') && deferred > 0) {
                    monthlyTotal = monthly * months + deferred;
                } else {
                    monthlyTotal = monthly * months;
                }
                let total = downInstallment + monthlyTotal + parseFloat(unformatNumber($keyPaymentDisplay.val()) ||
                    0);
                if (Math.abs(total - discountedAmount) > 0.01) {
                    $errorElement.text('{{ __('word.payment_plan_mismatch') }}').removeClass('hidden');
                    hasError = true;
                } else if (down > discountedAmount) {
                    $errorElement.text('{{ __('word.down_payment_exceeds_contract') }}').removeClass('hidden');
                    hasError = true;
                } else if (monthlyTotal > discountedAmount) {
                    $errorElement.text('{{ __('word.monthly_installments_exceed_contract') }}').removeClass(
                        'hidden');
                    hasError = true;
                } else {
                    $errorElement.addClass('hidden');
                }

                // Down payment installment validation
                if (downInstallment < 0) {
                    $downInstallmentError.text('{{ __('word.invalid_amount') }}').removeClass('hidden');
                    hasError = true;
                } else if (downInstallment > down) {
                    $downInstallmentError.text('{{ __('word.down_installment_exceeds_down') }}').removeClass(
                        'hidden');
                    hasError = true;
                } else {
                    $downInstallmentError.addClass('hidden');
                }

                // Deferred type and months validation
                if (deferred > 0 && deferredTypeValue === 'none') {
                    $deferredTypeError.text('{{ __('word.deferred_type_required') }}').removeClass('hidden');
                    hasError = true;
                } else if (deferredTypeValue === 'spread' && deferredMonthsCount <= 0) {
                    $deferredMonthsError.text('{{ __('word.deferred_months_required') }}').removeClass('hidden');
                    hasError = true;
                } else if (deferredTypeValue === 'spread' && deferredMonthsCount > months) {
                    $deferredMonthsError.text('{{ __('word.deferred_months_exceed_total') }}').removeClass(
                        'hidden');
                    hasError = true;
                } else if (deferredTypeValue.startsWith('lump-') && !['lump-6', 'lump-7'].includes(
                        deferredTypeValue)) {
                    $deferredTypeError.text('{{ __('word.invalid_deferred_type') }}').removeClass('hidden');
                    hasError = true;
                } else if (deferredTypeValue.startsWith('lump-')) {
                    let lumpMonth = parseInt(deferredTypeValue.split('-')[1]);
                    if (lumpMonth > months) {
                        $deferredTypeError.text('{{ __('word.lump_month_exceeds_total') }}').removeClass('hidden');
                        hasError = true;
                    } else {
                        $deferredTypeError.addClass('hidden');
                    }
                } else {
                    $deferredTypeError.addClass('hidden');
                    $deferredMonthsError.addClass('hidden');
                }

                $contractSubmitButton.prop('disabled', hasError);
            }

            // Auto-calculate key payment (Method 3 - Variable)
            function autoCalculateKeyPayment() {
                if (!isVariableSelected()) return;

                let discountedAmount = parseFloat($contractAmount.val()) || 0;
                let down = parseFloat(unformatNumber($downPaymentDisplay.val())) || 0;
                let downInstallment = parseFloat(unformatNumber($downPaymentInstallmentDisplay.val())) || 0;
                let monthly = parseFloat(unformatNumber($monthlyInstallmentDisplay.val())) || 0;
                let months = parseInt($numberOfMonths.val()) || 0;
                let deferredTypeValue = $deferredType.val();
                let deferredMonthsCount = parseInt($deferredMonths.val()) || 0;
                let deferred = down - downInstallment;

                $monthsLabel.text(months);
                $numberOfMonths.attr('aria-valuenow', months);

                let deferredPerMonth = deferredTypeValue === 'spread' && deferredMonthsCount > 0 ? Math.floor(
                    deferred / deferredMonthsCount) : 0;
                let remainder = deferredTypeValue === 'spread' && deferredMonthsCount > 0 ? deferred %
                    deferredMonthsCount : 0;
                let firstMonthlyAmount = deferredTypeValue === 'spread' && deferredMonthsCount > 0 ? monthly +
                    deferredPerMonth : monthly;
                let monthlyTotal = 0;
                let lumpMonth = deferredTypeValue.startsWith('lump-') ? parseInt(deferredTypeValue.split('-')[1]) :
                    0;

                if (deferredTypeValue === 'spread' && deferredMonthsCount > 0) {
                    monthlyTotal = (firstMonthlyAmount * deferredMonthsCount) + (monthly * (months -
                        deferredMonthsCount));
                    if (remainder > 0) {
                        monthlyTotal += remainder;
                    }
                } else if (deferredTypeValue.startsWith('lump-') && deferred > 0) {
                    monthlyTotal = monthly * months + deferred;
                } else {
                    monthlyTotal = monthly * months;
                }

                let key = discountedAmount - (downInstallment + monthlyTotal);
                if (key < 0) key = 0;

                $keyPaymentDisplay.val(formatNumber(key.toFixed(0)));
                $keyPayment.val(key);

                let $tbody = $('#payment-breakdown');
                $tbody.html(`
                <tr>
                    <td class="px-2 py-1">{{ __('word.down_payment_installment') }}</td>
                    <td class="px-2 py-1">${formatNumber(downInstallment)}</td>
                    <td class="px-2 py-1">1</td>
                    <td class="px-2 py-1">${formatNumber(downInstallment)}</td>
                </tr>
            `);

                if (deferredTypeValue === 'spread' && deferredMonthsCount > 0 && deferred > 0) {
                    let firstMonthsTotal = (firstMonthlyAmount * deferredMonthsCount) + (remainder > 0 ? remainder :
                        0);
                    $tbody.append(`
                    <tr>
                        <td class="px-2 py-1">{{ __('word.first_monthly_installments') }}</td>
                        <td class="px-2 py-1">${formatNumber(firstMonthlyAmount + (remainder > 0 ? remainder / deferredMonthsCount : 0))}</td>
                        <td class="px-2 py-1">${deferredMonthsCount}</td>
                        <td class="px-2 py-1">${formatNumber(firstMonthsTotal)}</td>
                    </tr>
                `);
                    if (months > deferredMonthsCount) {
                        $tbody.append(`
                        <tr>
                            <td class="px-2 py-1">{{ __('word.remaining_monthly_installments') }}</td>
                            <td class="px-2 py-1">${formatNumber(monthly)}</td>
                            <td class="px-2 py-1">${months - deferredMonthsCount}</td>
                            <td class="px-2 py-1">${formatNumber(monthly * (months - deferredMonthsCount))}</td>
                        </tr>
                    `);
                    }
                } else if (deferredTypeValue.startsWith('lump-') && deferred > 0) {
                    $tbody.append(`
                    <tr>
                        <td class="px-2 py-1">{{ __('word.monthly_installments') }}</td>
                        <td class="px-2 py-1">${formatNumber(monthly)}</td>
                        <td class="px-2 py-1">${months}</td>
                        <td class="px-2 py-1">${formatNumber(monthly * months)}</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1">{{ __('word.deferred_lump_sum') }}</td>
                        <td class="px-2 py-1">${formatNumber(deferred)}</td>
                        <td class="px-2 py-1">{{ __('word.with_installment') }} ${lumpMonth}</td>
                        <td class="px-2 py-1">${formatNumber(deferred)}</td>
                    </tr>
                `);
                } else {
                    $tbody.append(`
                    <tr>
                        <td class="px-2 py-1">{{ __('word.monthly_installments') }}</td>
                        <td class="px-2 py-1">${formatNumber(monthly)}</td>
                        <td class="px-2 py-1">${months}</td>
                        <td class="px-2 py-1">${formatNumber(monthly * months)}</td>
                    </tr>
                `);
                }

                $tbody.append(`
                <tr>
                    <td class="px-2 py-1">{{ __('word.key_payment') }}</td>
                    <td class="px-2 py-1">${formatNumber(key)}</td>
                    <td class="px-2 py-1">1</td>
                    <td class="px-2 py-1">${formatNumber(key)}</td>
                </tr>
            `);

                $('#breakdown-total').text(formatNumber(discountedAmount));
                validatePaymentPlan();
            }

            // Calculate flexible payment plan (Method 4)
            function calculateFlexiblePlan() {
                if (!isFlexibleSelected()) {
                    $('#flexible-plan-error').addClass('hidden').text('');
                    return;
                }

                let amount = parseFloat($contractAmount.val()) || 0;

                // Get values from display fields
                let downTotal = parseFloat(unformatNumber($flexDownPaymentDisplay.val())) || 0;
                let downCash = parseFloat(unformatNumber($flexDownPaymentInstallmentDisplay.val())) || 0;
                let deferredPiece = parseFloat(unformatNumber($flexDownPaymentDeferredInstallmentDisplay.val())) ||
                    0;
                let deferredFreq = parseInt($flexDownPaymentDeferredFrequency.val()) || 1;
                let deferredTotal = Math.max(0, downTotal - downCash);

                let monthly = parseFloat(unformatNumber($flexMonthlyInstallmentDisplay.val())) || 0;
                let monthsCount = parseInt($flexNumberOfMonths.val()) || 0;
                let monthlyFreq = parseInt($flexMonthlyFrequency.val()) || 1;

                // Calculate key payment
                let key = amount - (downCash + deferredTotal + (monthly * monthsCount));
                if (key < 0) key = 0;

                $flexKeyPaymentDisplay.val(formatNumber(key.toFixed(0)));

                // Build breakdown table
                let $tbody = $('#flexible-breakdown');
                $tbody.empty();

                $tbody.append(`
                    <tr>
                        <td class="px-2 py-1">دفعة مقدمة (نقد)</td>
                        <td class="px-2 py-1">${formatNumber(downCash)}</td>
                        <td class="px-2 py-1">1</td>
                        <td class="px-2 py-1">${formatNumber(downCash)}</td>
                    </tr>
                `);

                if (deferredTotal > 0) {
                    let pieces = deferredPiece > 0 ? Math.ceil(deferredTotal / deferredPiece) : 1;
                    $tbody.append(`
                        <tr>
                            <td class="px-2 py-1">دفعة مقدمة (مؤجل)</td>
                            <td class="px-2 py-1">${formatNumber(deferredTotal)}</td>
                            <td class="px-2 py-1">${pieces} × كل ${deferredFreq} شهر</td>
                            <td class="px-2 py-1">${formatNumber(deferredTotal)}</td>
                        </tr>
                    `);
                }

                if (monthly > 0 && monthsCount > 0) {
                    $tbody.append(`
                        <tr>
                            <td class="px-2 py-1">أقساط دورية</td>
                            <td class="px-2 py-1">${formatNumber(monthly)}</td>
                            <td class="px-2 py-1">${monthsCount} × كل ${monthlyFreq} شهر</td>
                            <td class="px-2 py-1">${formatNumber(monthly * monthsCount)}</td>
                        </tr>
                    `);
                }

                $tbody.append(`
                    <tr>
                        <td class="px-2 py-1">دفعة المفتاح</td>
                        <td class="px-2 py-1">${formatNumber(key)}</td>
                        <td class="px-2 py-1">1</td>
                        <td class="px-2 py-1">${formatNumber(key)}</td>
                    </tr>
                `);

                let total = downCash + deferredTotal + (monthly * monthsCount) + key;
                $('#flexible-breakdown-total').text(formatNumber(total));

                // Validation
                let hasError = false;
                let $err = $('#flexible-plan-error');
                $err.text('');

                let totalRounded = Math.round(total);
                let amountRounded = Math.round(amount);

                if (downCash > downTotal) {
                    $err.text('المبلغ النقدي للدفعة المقدمة يتجاوز إجمالي الدفعة المقدمة.').removeClass('hidden');
                    hasError = true;
                } else if (totalRounded !== amountRounded) {
                    $err.text(
                        `مجموع الخطة لا يساوي مبلغ العقد. المجموع = ${formatNumber(totalRounded)}, العقد = ${formatNumber(amountRounded)}`
                    ).removeClass('hidden');
                    hasError = true;
                } else {
                    $err.addClass('hidden');
                }

                $contractSubmitButton.prop('disabled', hasError);
            }

            // Toggle variable/flexible fields
            function toggleVariableFields() {
                if (isVariableSelected()) {
                    $variablePaymentFields.removeClass('hidden');
                    $flexiblePaymentFields.addClass('hidden');
                    autoCalculateKeyPayment();
                } else if (isFlexibleSelected()) {
                    $variablePaymentFields.addClass('hidden');
                    $flexiblePaymentFields.removeClass('hidden');
                    calculateFlexiblePlan();
                } else {
                    $variablePaymentFields.addClass('hidden');
                    $flexiblePaymentFields.addClass('hidden');
                    $('#payment-plan-error, #down_payment_installment_error, #deferred_type_error, #deferred_months_error, #flexible-plan-error')
                        .addClass('hidden');
                    $contractSubmitButton.prop('disabled', false);
                }
            }

            // Event listeners
            $paymentMethodSelect.on('change select2:select', toggleVariableFields);
            $('#contract_building_id').on('change select2:select', calculateContractAmount);
            $discountInput.on('input', calculateContractAmount);

            // Variable payment events
            $numberOfMonths.on('input', autoCalculateKeyPayment);
            $downPaymentDisplay.on('input', autoCalculateKeyPayment);
            $downPaymentInstallmentDisplay.on('input', autoCalculateKeyPayment);
            $monthlyInstallmentDisplay.on('input', autoCalculateKeyPayment);
            $deferredType.on('change', autoCalculateKeyPayment);
            $deferredMonths.on('input', autoCalculateKeyPayment);

            // Flexible payment events
            $flexDownPaymentDisplay.on('input', calculateFlexiblePlan);
            $flexDownPaymentInstallmentDisplay.on('input', calculateFlexiblePlan);
            $flexDownPaymentDeferredInstallmentDisplay.on('input', calculateFlexiblePlan);
            $flexDownPaymentDeferredFrequency.on('change', calculateFlexiblePlan);
            $flexMonthlyInstallmentDisplay.on('input', calculateFlexiblePlan);
            $flexNumberOfMonths.on('input', calculateFlexiblePlan);
            $flexMonthlyFrequency.on('change', calculateFlexiblePlan);
            $flexMonthlyStartDate.on('input', calculateFlexiblePlan);

            // Prevent double submission for contract form
            $('form[action="{{ route('contract.store') }}"]').on('submit', function(e) {
                if ($(this).data('submitting')) return false;
                $(this).data('submitting', true);

                $contractSubmitButton.text('{{ __('word.saving') }}').prop('disabled', true);

                // Update hidden fields before submission
                if (isVariableSelected()) {
                    // Variable method uses the original hidden fields
                    $downPayment.val(unformatNumber($downPaymentDisplay.val()));
                    $downPaymentInstallment.val(unformatNumber($downPaymentInstallmentDisplay.val()));
                    $monthlyInstallment.val(unformatNumber($monthlyInstallmentDisplay.val()));
                    $keyPayment.val(unformatNumber($keyPaymentDisplay.val()));
                } else if (isFlexibleSelected()) {
                    // Flexible method updates the same hidden fields with different values
                    $downPayment.val(unformatNumber($flexDownPaymentDisplay.val()));
                    $downPaymentInstallment.val(unformatNumber($flexDownPaymentInstallmentDisplay.val()));
                    $monthlyInstallment.val(unformatNumber($flexMonthlyInstallmentDisplay.val()));
                    $keyPayment.val(unformatNumber($flexKeyPaymentDisplay.val()));
                    // Also update the flexible-specific hidden field
                    $flexDownPaymentDeferredInstallment.val(unformatNumber(
                        $flexDownPaymentDeferredInstallmentDisplay.val()));
                    // Update number_of_months from flex input
                    $numberOfMonths.val($flexNumberOfMonths.val());
                }

                return true;
            });

            // Initial setup
            toggleVariableFields();
            calculateContractAmount();
        });
    </script>

</x-app-layout>
