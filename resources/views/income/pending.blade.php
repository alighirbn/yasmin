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
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                {!! QrCode::size(90)->generate($income->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="max-width: 100%; height: auto;">
                            </div>
                            <div class="mx-2 my-2 w-full">

                                <p><strong>{{ __('عدد الايراد:') }}</strong> {{ $income->id }}</p>
                                <p><strong>{{ __('تاريخ الايراد:') }}</strong> {{ $income->income_date }}</p>
                            </div>
                        </div>
                        <div style="text-align: center; margin: 1rem auto; font-size: 1rem;">
                            سندات الايراد في طور استحصال الموافقة
                        </div>
                        @if ($pendingincomes->isEmpty())
                            <p>لا توجد سندات صرف لم يتم الموافقة عليها</p>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> {{ __('word.action') }}</th>
                                        <th> {{ __('word.income_id') }}</th>
                                        <th> {{ __('word.income_date') }}</th>
                                        <th> {{ __('word.income_amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingincomes as $income)
                                        <tr>
                                            <td>
                                                <div class="header-buttons">
                                                    <a href="{{ route('income.show', $income->url_address) }}"
                                                        class="btn btn-custom-show">
                                                        {{ __('word.view') }}
                                                    </a>
                                                    <form action="{{ route('income.approve', $income->url_address) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-custom-edit">
                                                            {{ __('word.income_approve') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $income->id }}</td>
                                            <td>{{ $income->income_date }}</td>
                                            <td>{{ number_format($income->income_amount, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
