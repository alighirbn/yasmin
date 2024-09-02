<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('transfer.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto  lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('transfer-show')
                            <a href="{{ route('transfer.show', $transfer->url_address) }}" class="btn btn-custom-show">
                                {{ __('word.transfer_view') }}
                            </a>
                        @endcan
                        @can('transfer-update')
                            <a href="{{ route('transfer.edit', $transfer->url_address) }}" class="btn btn-custom-edit">
                                {{ __('word.transfer_edit') }}
                            </a>
                        @endcan
                        <button id="print" class="btn btn-custom-statement" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>

                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="p-4">

                            <div class="flex">
                                <div class=" mx-2 my-2 w-full ">
                                    {!! QrCode::size(90)->generate($transfer->id) !!}
                                </div>
                                <div class=" mx-2 my-2 w-full ">
                                    <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                        style="h-6;max-width: 100%; height: auto;">
                                </div>
                                <div class=" mx-2 my-2 w-full ">

                                    <p><strong>{{ __('رقم العقد:') }}</strong>
                                        {{ $transfer->id }}
                                    </p>
                                    <p><strong>{{ __('تاريخ العقد:') }}</strong> {{ $transfer->transfer_date }}</p>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
