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

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                        @can('cash_account-delete')
                            <form action="{{ route('cash_account.destroy', $cash_account->url_address) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                                    {{ __('word.delete') }}
                                </button>

                            </form>
                        @endcan
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto  bg-white">

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="cash_account_id" class="w-full mb-1" :value="__('word.cash_account_id')" />
                                <p id="cash_account_id" class="w-full h-9 block mt-1" type="text"
                                    name="cash_account_id">
                                    {{ $cash_account->id }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="account_name" class="w-full mb-1" :value="__('word.account_name')" />
                                <p id="account_name" class="w-full h-9 block mt-1" type="text" name="account_name">
                                    {{ $cash_account->account_name }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="balance" class="w-full mb-1" :value="__('word.balance')" />
                                <p id="balance" class="w-full h-9 block mt-1 " type="text" name="balance">
                                    {{ number_format($cash_account->balance, 0) }} دينار
                                </p>
                            </div>

                        </div>

                    </div>
                    <div class="flex">
                        @if (isset($cash_account->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $cash_account->user_create->name }}
                                {{ $cash_account->created_at }}
                            </div>
                        @endif

                        @if (isset($cash_account->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $cash_account->user_update->name }}
                                {{ $cash_account->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
