<x-app-layout>

    <x-slot name="header">

        @include('cash_transfer.nav.navigation')

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    Access Denied !!

                </div>
                <div class="p-6 text-gray-900">

                    your ip address {{ $ip }}

                </div>
                <div class="p-6 text-gray-900">

                    your mac address {{ exec('getmac') }}

                </div>

            </div>
        </div>

</x-app-layout>
