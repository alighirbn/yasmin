<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('hr.nav.navigation')
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

                        <form action="{{ route('hr.advances.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                                {{ __('word.delete') }}
                            </button>
                        </form>

                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="print-container a4-width mx-auto bg-white">

                        <div class="flex items-center">
                            <div class="mx-2 my-2 w-full flex justify-center">
                                {!! QrCode::size(90)->generate($item->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full flex justify-center">
                                <img src="{{ asset('images/ba.png') }}" alt="Logo" class=" w-auto">
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <p><strong>{{ __('رقم السلفة:') }}</strong> {{ $item->id }}</p>
                                <p><strong>{{ __('تاريخ السلفة:') }}</strong> {{ $item->date }}</p>
                            </div>
                        </div>

                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>سند سلفة</p>
                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="employee_name" class="w-full mb-1" value="الموظف" />
                                <p id="employee_name" class="w-full h-9 block mt-1">
                                    {{ $item->employee->full_name }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="amount" class="w-full mb-1" value="المبلغ" />
                                <p id="amount" class="w-full h-9 block mt-1">
                                    {{ number_format($item->amount, 0) }} دينار
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="settled" class="w-full mb-1" value="مُسددة؟" />
                                <p id="settled" class="w-full h-9 block mt-1">
                                    {{ $item->settled ? 'نعم' : 'لا' }}
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="flex">
                        @if (isset($item->created_at))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }}
                                {{ $item->created_at }}
                            </div>
                        @endif

                        @if (isset($item->updated_at))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }}
                                {{ $item->updated_at }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
