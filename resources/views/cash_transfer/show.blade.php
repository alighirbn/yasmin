<x-app-layout>
    <x-slot name="header">
        <!-- app css -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('payment.nav.navigation')
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

                        @if (!$cashTransfer->approved)
                            <form action="{{ route('cash_transfer.approve', $cashTransfer->url_address) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-custom-edit">
                                    {{ __('word.cash_transfer_approve') }}
                                </button>
                            </form>
                        @endif

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>

                        @can('cash_transfer-delete')
                            <form action="{{ route('cash_transfer.destroy', $cashTransfer->url_address) }}" method="post">
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

                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                {!! QrCode::size(90)->generate($cashTransfer->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <p><strong>{{ __('word.transfer_number') }}:</strong> {{ $cashTransfer->id }}</p>
                                <p><strong>{{ __('word.transfer_date') }}:</strong> {{ $cashTransfer->transfer_date }}
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="from_account_id" class="w-full mb-1" :value="__('word.from_account')" />
                                <p id="from_account_id" class="w-full h-9 block mt-1">
                                    {{ $cashTransfer->fromAccount->name }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="to_account_id" class="w-full mb-1" :value="__('word.to_account')" />
                                <p id="to_account_id" class="w-full h-9 block mt-1">
                                    {{ $cashTransfer->toAccount->name }}
                                </p>
                            </div>

                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="amount" class="w-full mb-1" :value="__('word.amount')" />
                                <p id="amount" class="w-full h-9 block mt-1">
                                    {{ number_format($cashTransfer->amount, 0) }} دينار
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label for="transfer_note" class="w-full mb-1" :value="__('word.transfer_note')" />
                                <p id="transfer_note" class="w-full h-9 block mt-1">
                                    {{ $cashTransfer->transfer_note }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        @if (isset($cashTransfer->user_id_create))
                            <div class="mx-4 my-4">
                                {{ __('word.user_create') }} {{ $cashTransfer->user_create->name }}
                                {{ $cashTransfer->created_at }}
                            </div>
                        @endif

                        @if (isset($cashTransfer->user_id_update))
                            <div class="mx-4 my-4">
                                {{ __('word.user_update') }} {{ $cashTransfer->user_update->name }}
                                {{ $cashTransfer->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
