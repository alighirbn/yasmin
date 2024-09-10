<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('expense.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                        @if (!$expense->approved)
                            <form action="{{ route('expense.approve', $expense->url_address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-custom-edit">
                                    {{ __('word.expense_approve') }}</button>
                            </form>
                        @endif

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                        @can('expense-delete')
                            <form action="{{ route('expense.destroy', $expense->url_address) }}" method="post">
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

                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($expense->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">

                                <p><strong>{{ __('عدد سند الصرف:') }}</strong>
                                    {{ $expense->id }}
                                </p>
                                <p><strong>{{ __('تاريخ سند الصرف:') }}</strong> {{ $expense->expense_date }}</p>

                            </div>
                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_id" class="w-full mb-1" :value="__('word.expense_id')" />
                                <p id="expense_id" class="w-full h-9 block mt-1" type="text" name="expense_id">
                                    {{ $expense->id }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_date" class="w-full mb-1" :value="__('word.expense_date')" />
                                <p id="expense_date" class="w-full h-9 block mt-1 " type="text" name="expense_date">
                                    {{ $expense->expense_date }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_type_id" class="w-full mb-1" :value="__('word.expense_type_id')" />
                                <p id="expense_type_id" class="w-full h-9 block mt-1" type="text"
                                    name="expense_type_id">
                                    {{ $expense->expense_type->expense_type }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_amount" class="w-full mb-1" :value="__('word.expense_amount')" />
                                <p id="expense_amount" class="w-full h-9 block mt-1 " type="text"
                                    name="expense_amount">
                                    {{ number_format($expense->expense_amount, 0) }} دينار
                                </p>
                            </div>

                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_note" class="w-full mb-1" :value="__('word.expense_note')" />
                                <p id="expense_note" class="w-full h-9 block mt-1" type="text" name="expense_note">
                                    {{ $expense->expense_note }}
                                </p>
                            </div>

                        </div>

                    </div>
                    <div class="flex">
                        @if (isset($expense->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $expense->user_create->name }}
                                {{ $expense->created_at }}
                            </div>
                        @endif

                        @if (isset($expense->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $expense->user_update->name }}
                                {{ $expense->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
