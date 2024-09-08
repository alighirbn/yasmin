<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('payment.nav.navigation')
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
                            <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.contract_view') }}
                            </a>
                        @endcan
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto  bg-white">

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

                                <p><strong>{{ __('عدد العقد:') }}</strong>
                                    {{ $contract->id }}
                                </p>
                                <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $contract->contract_date }}</p>

                            </div>

                        </div>
                        <div style="text-align: center; margin: 1rem auto; font-size: 1rem;">
                            الدفعات في طور استحصال الموافقة
                        </div>

                        @if ($pendingPayments->isEmpty())
                            <p>لا توجد دفعات لم يتم الموافقة عليها</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> {{ __('word.action') }}</th>
                                        <th> {{ __('word.payment_id') }}</th>
                                        <th> {{ __('word.payment_date') }}</th>
                                        <th> {{ __('word.installment_name') }}</th>
                                        <th> {{ __('word.installment_percent') }}</th>
                                        <th> {{ __('word.payment_amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingPayments as $payment)
                                        <tr>
                                            <td>
                                                <div class="header-buttons">
                                                    <a href="{{ route('payment.show', $payment->url_address) }}"
                                                        class="btn btn-custom-show">{{ __('word.view') }}</a>
                                                    <form
                                                        action="{{ route('payment.approve', $payment->url_address) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-custom-edit">
                                                            {{ __('word.payment_approve') }}</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->payment_date }}</td>
                                            <td>{{ $payment->contract_installment->installment->installment_name }}
                                            <td>{{ $payment->contract_installment->installment->installment_percent * 100 . ' %' }}
                                            </td>
                                            <td>{{ number_format($payment->payment_amount, 0) }}</td>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
