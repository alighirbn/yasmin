<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('hr.nav.navigation')
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

                        <form action="{{ route('hr.incentives.destroy', $item) }}" method="post" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-custom-delete"
                                onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                {{ __('word.delete') }}
                            </button>
                        </form>

                    </div>

                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex items-center">
                            <div class="mx-2 my-2 w-full flex justify-center">
                                {!! QrCode::size(90)->generate($item->id) !!}
                            </div>
                            <div class="mx-2 my-2 w-full flex justify-center">
                                <img src="{{ asset('images/ba.png') }}" alt="Logo" class="h-16 w-auto">
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <p><strong>رقم السند:</strong> {{ $item->id }}</p>
                                <p><strong>تاريخ السند:</strong> {{ $item->date }}</p>
                            </div>
                        </div>

                        <div class="text-center my-4 font-bold text-lg">
                            <p>سند {{ $item->type === 'incentive' ? 'حافز' : 'استقطاع' }}</p>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label value="الموظف" />
                                <p>{{ $item->employee->full_name }}</p>
                            </div>
                            <div class="mx-4 my-4 w-full">
                                <x-input-label value="المبلغ" />
                                <p>{{ number_format($item->amount, 0) }} دينار</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="mx-4 my-4 w-full">
                                <x-input-label value="السبب" />
                                <p>{{ $item->reason ?? '---' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        @if ($item->created_at)
                            <div class="mx-4 my-4">
                                {{ __('word.user_create') }} {{ $item->created_at }}
                            </div>
                        @endif
                        @if ($item->updated_at)
                            <div class="mx-4 my-4">
                                {{ __('word.user_update') }} {{ $item->updated_at }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
