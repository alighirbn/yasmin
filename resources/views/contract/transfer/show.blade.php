<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                        @can('transfer-update')
                            <a href="{{ route('transfer.edit', $transfer->url_address) }}" class="btn btn-custom-edit">
                                {{ __('word.transfer_edit') }}
                            </a>
                        @endcan
                        @can('transfer-print')
                            <a href="{{ route('transfer.print', $transfer->url_address) }}" class="btn btn-custom-print">
                                {{ __('word.transfer_print') }}
                            </a>
                        @endcan

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
