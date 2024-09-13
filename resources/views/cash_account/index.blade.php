<x-app-layout>

    <x-slot name="header">

        @include('payment.nav.navigation')
        @include('expense.nav.navigation')
        @include('cash_account.nav.navigation')

    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <table>
                        {!! $dataTable->table() !!}
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.fn.dataTable.ext.errMode = 'none';
    </script>

    {!! $dataTable->scripts() !!}

</x-app-layout>
