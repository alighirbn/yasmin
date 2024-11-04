<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <a href="{{ route('contract.temp', $contract->url_address) }}" class="btn btn-custom-print">
                            {{ __('word.print') }}
                        </a>
                        @can('contract-statement')
                            <a href="{{ route('contract.statement', $contract->url_address) }}"
                                class="btn btn-custom-statement">
                                {{ __('word.statement') }}
                            </a>
                        @endcan

                        @can('contract-update')
                            @if ($contract->payments()->count() == 0)
                                <a href="{{ route('contract.edit', $contract->url_address) }}" class="btn btn-custom-edit">
                                    {{ __('word.contract_edit') }}
                                </a>
                            @endif

                        @endcan
                        @can('contract-accept')
                            @if ($contract->stage == 'temporary')
                                <a href="{{ route('contract.accept', $contract->url_address) }}"
                                    class="btn btn-custom-approve">
                                    {{ __('word.contract_accept') }}
                                </a>
                            @endif

                        @endcan
                        @can('contract-authenticat')
                            @if (
                                $contract->stage == 'accepted' &&
                                    count($contract->images) >= 1 &&
                                    $contract->payments->where('approved', true)->count() >= 1)
                                <a href="{{ route('contract.authenticat', $contract->url_address) }}"
                                    class="btn btn-custom-approve">
                                    {{ __('word.contract_authenticat') }}
                                </a>
                            @endif

                        @endcan
                        @can('contract-archive')
                            @if ($contract->stage != 'temporary' && $contract->payments->where('approved', true)->count() >= 1)
                                <a href="{{ route('contract.archivecreate', $contract->url_address) }}"
                                    class="btn btn-custom-archive">
                                    {{ __('word.contract_archive') }}
                                </a>
                            @endif
                        @endcan
                        @can('contract-archiveshow')
                            @if (
                                $contract->stage != 'temporary' &&
                                    $contract->payments->where('approved', true)->count() >= 1 &&
                                    count($contract->images) >= 1)
                                <a href="{{ route('contract.archiveshow', $contract->url_address) }}"
                                    class="btn btn-custom-archive">
                                    {{ __('word.archiveshow') }}
                                </a>
                            @endif
                        @endcan
                        @can('contract-due')
                            <a href="{{ route('contract.due', ['contract_id' => $contract->id]) }}"
                                class="btn btn-custom-due">
                                {{ __('word.contract_due') . ' (' . $due_installments_count . ')' }}
                            </a>
                        @endcan
                        @can('payment-show')
                            <a href="{{ route('payment.index', ['contract_id' => $contract->id]) }}"
                                class="btn btn-custom-show">
                                {{ __('word.payment') }}
                            </a>
                            <a href="{{ route('payment.pending', $contract->url_address) }}" class="btn btn-custom-due">
                                {{ __('word.payment_pending') . ' (' . $pending_payments_count . ')' }}
                            </a>
                        @endcan
                        @can('contract-print')
                            @if ($contract->stage == 'authenticated' && count($contract->images) >= 1)
                                <a href="{{ route('contract.print', $contract->url_address) }}"
                                    class="btn btn-custom-print">
                                    {{ __('word.contract_print') }}
                                </a>
                            @endif
                        @endcan
                        @can('transfer-create')
                            <a href="{{ route('transfer.create', ['contract_id' => $contract->id]) }}"
                                class="btn btn-custom-transfer">
                                {{ __('word.contract_transfer') }}
                            </a>
                        @endcan
                        @can('transfer-show')
                            <a href="{{ route('transfer.contract', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.transfer_contract') }}
                            </a>
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
                                            &#10003; <!-- Checkmark symbol for completed stages -->
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
                                <p id="method_name" class="w-full h-9 block mt-1 " type="text" name="method_name">
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
                                        <td>{{ $installment->installment->installment_number }}</td>
                                        <td>{{ $installment->installment->installment_name }}</td>
                                        <td>{{ $installment->installment->installment_percent * 100 }} %</td>
                                        <td>{{ number_format($installment->installment_amount, 0) }} دينار</td>
                                        <td>{{ $installment->installment_date }}</td>
                                        <td>
                                            @if ($installment->payment == null)
                                                @if ($installment->isDue($installment->installment_date))
                                                    <span style="color: red;">لم تسدد - مستحقة</span>
                                                @else
                                                    <span>لم تسدد - غير مستحقة</span>
                                                @endif
                                            @elseif ($installment->payment->approved)
                                                مسددة في {{ $installment->payment->payment_date }}
                                            @else
                                                لم تتم الموافقة على الدفع
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
                                                            <a href="{{ route('contract.add', $installment->url_address) }}"
                                                                class="add_payment btn btn-custom-edit">
                                                                {{ __('word.add_payment') }}
                                                            </a>

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
