<x-app-layout>

    <x-slot name="header">

        @include('building.nav.navigation')

    </x-slot>

    <div class="bg-custom py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
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
