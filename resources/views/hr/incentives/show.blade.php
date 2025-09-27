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

                    <div
                        class="print-container a4-width mx-auto bg-white border border-gray-300 rounded-lg shadow p-10">
                        <div class="flex items-center mb-6">
                            <div class="mx-4 w-full flex justify-center">
                                {!! QrCode::size(100)->generate($item->id) !!}
                            </div>
                            <div class="mx-4 w-full flex justify-center">
                                <img src="{{ asset('images/ba.png') }}" alt="Logo" class="h-20 w-auto">
                            </div>
                            <div class="mx-4 w-full text-sm leading-7">
                                <p><strong>رقم السند:</strong> {{ $item->id }}</p>
                                <p><strong>تاريخ السند:</strong> {{ $item->date }}</p>
                            </div>
                        </div>
                        <div class="text-center my-6 font-bold text-xl">
                            <p>
                                ادارة العمليات والموارد البشرية

                            </p>
                        </div>
                        <br>
                        <div class="text-center my-6 font-bold text-xl bg-gray-50 rounded p-4 border mt-1">
                            <p>
                                سند
                                <span class="{{ $item->type === 'incentive' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item->type === 'incentive' ? 'حافز' : 'استقطاع' }}
                                </span>
                            </p>
                        </div>

                        <!-- بيانات الموظف -->
                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div>
                                <x-input-label value="الموظف" />
                                <p class="mt-1">{{ $item->employee->full_name }}</p>
                            </div>
                            <div>
                                <x-input-label value="القسم" />
                                <p class="mt-1">{{ $item->employee->department ?? '---' }}</p>
                            </div>
                            <div>
                                <x-input-label value="المسمى الوظيفي" />
                                <p class="mt-1">{{ $item->employee->position ?? '---' }}</p>
                            </div>

                        </div>

                        <!-- تفاصيل السند -->
                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div>
                                <x-input-label value="المبلغ" />
                                <p
                                    class="mt-1 {{ $item->type === 'incentive' ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
                                    {{ number_format($item->amount, 0) }} دينار
                                </p>
                            </div>
                            <div>
                                <x-input-label value="سبب الإصدار" />
                                <div class="bg-gray-50 rounded p-4 border mt-1">
                                    {{ $item->reason ?? '---' }}
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>

                        <!-- التواقيع -->
                        <div class="flex justify-between mt-16">
                            <div class="text-center">
                                <p class="font-semibold">الموظف</p>
                                <div class="mt-12 border-t border-gray-400 w-48 mx-auto"></div>
                            </div>
                            <div class="text-center">
                                <p class="font-semibold">المدير المفوض</p>
                                <div class="mt-12 border-t border-gray-400 w-48 mx-auto"></div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
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
