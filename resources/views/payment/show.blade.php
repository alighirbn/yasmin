<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('payment.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('cash_account.nav.navigation')
            @include('cash_transfer.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('contract-show')
                            <a href="{{ route('contract.show', $payment->contract->url_address) }}"
                                class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                        @can('payment-delete')
                            <form action="{{ route('payment.destroy', $payment->url_address) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                                    {{ __('word.delete') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                    @hasanyrole('admin|ahmed|all access')
                        <div class="header-buttons">
                            @if (!$payment->approved)
                                <form action="{{ route('payment.approve', $payment->url_address) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label for="cash_account_id">الصندوق</label>
                                    <select name="cash_account_id" required>
                                        @foreach ($cash_accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-custom-edit">
                                        {{ __('word.payment_approve') }}</button>
                                </form>
                            @endif
                        </div>
                    @endhasanyrole

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                {!! QrCode::size(90)->generate($payment->contract->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6; max-width: 100%; height: auto;">
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($payment->id, 'C39') }}"
                                    alt="barcode" />
                                <p><strong>{{ __('عدد الدفعة:') }}</strong>
                                    {{ $payment->id }}
                                </p>
                                <p><strong>{{ __('تاريخ الدفعة:') }}</strong> {{ $payment->payment_date }}</p>
                            </div>
                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>{{ $payment->payment_amount < 0 ? 'سند استرداد' : 'سند قبض' }}</p>
                        </div>
                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="payment_id" class="w-full mb-1" :value="__('word.payment_id')" />
                                <p id="payment_id" class="w-full h-9 block mt-1" type="text" name="payment_id">
                                    {{ $payment->id }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="payment_date" class="w-full mb-1" :value="__('word.payment_date')" />
                                <p id="payment_date" class="w-full h-9 block mt-1" type="text" name="payment_date">
                                    {{ $payment->payment_date }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="installment_name" class="w-full mb-1" :value="__('word.installment_name')" />
                                <p id="installment_name" class="w-full h-9 block mt-1" type="text"
                                    name="installment_name">
                                    {{ $payment->contract_installment?->installment?->installment_name ?? __('بدون') }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="payment_amount" class="w-full mb-1" :value="__('word.payment_amount')" />
                                <p id="payment_amount" class="w-full h-9 block mt-1" type="text"
                                    name="payment_amount">
                                    {{ number_format(abs($payment->payment_amount), 0) }} دينار
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                <p id="customer_full_name" class="outlined-text" type="text"
                                    name="customer_full_name">
                                    {{ $payment->contract->customer->customer_full_name }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1" type="text"
                                    name="customer_card_number">
                                    {{ $payment->contract->customer->customer_card_number }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_id" class="w-full mb-1" :value="__('word.contract_id')" />
                                <p id="contract_id" class="w-full h-9 block mt-1" type="text" name="contract_id">
                                    {{ $payment->contract->id }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="contract_date" class="w-full mb-1" :value="__('word.contract_date')" />
                                <p id="contract_date" class="w-full h-9 block mt-1" type="text" name="contract_date">
                                    {{ $payment->contract->contract_date }}
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                <p id="building_category_id" class="w-full h-9 block mt-1" type="text"
                                    name="building_category_id">
                                    {{ $payment->contract->building->building_category->category_name }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                <p id="building_number" class="w-full h-9 block mt-1" type="text"
                                    name="building_number">
                                    {{ $payment->contract->building->building_number }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1" type="text"
                                    name="block_number">
                                    {{ $payment->contract->building->block_number }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1" type="text"
                                    name="house_number">
                                    {{ $payment->contract->building->house_number }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                <p id="building_area" class="w-full h-9 block mt-1" type="text"
                                    name="building_area">
                                    {{ $payment->contract->building->building_area }}
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="payment_note" class="w-full mb-1" :value="__('word.payment_note')" />
                                <p id="payment_note" class="w-full h-9 block mt-1" type="text"
                                    name="payment_note">
                                    {{ $payment->payment_note }}
                                </p>
                            </div>
                        </div>

                        @if ($payment->contract_installment?->installment?->installment_name === 'الاولى')
                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <p>في حال رغب صاحب الوصل باسترداد مبلغ الدفعة الأولى، يُلزم بدفع غرامة قدرها خمسة
                                        ملايين دينار، وذلك خلال مدة لا تتجاوز خمسة عشر يوماً من تاريخ إصدار الوصل. وبعد
                                        الفترة المحددة تسري عليه فقرات العقد، فتُطبق عليه كافة بنود العقد دون استثناء.
                                    </p>
                                </div>
                            </div>
                        @endif
                        <br>
                        <br>

                        <div class="flex justify-center">
                            <div class="mx-4 my-4 w-full text-center">
                                <p>المشتري</p>
                            </div>
                            <div class="mx-4 my-4 w-full text-center">
                                <p>امين الصندوق</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        @if (isset($payment->user_id_create))
                            <div class="mx-4 my-4">
                                {{ __('word.user_create') }} {{ $payment->user_create->name }}
                                {{ $payment->created_at }}
                            </div>
                        @endif

                        @if (isset($payment->user_id_update))
                            <div class="mx-4 my-4">
                                {{ __('word.user_update') }} {{ $payment->user_update->name }}
                                {{ $payment->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
