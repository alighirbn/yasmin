<x-app-layout>

    <x-slot name="header">
        @include('contract.nav.navigation')
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
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filter input -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="transfer-id-filter" class="form-control"
                                placeholder="{{ __('word.transfer_id') }}">
                        </div>
                    </div>

                    <!-- DataTable -->
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
            var table = $('#transfer-table').DataTable();

            $('#transfer-id-filter').on('keyup', function() {
                table.column(1).search(this.value).draw(); // Assuming 'id' is in the first column
            });
        });

        $.fn.dataTable.ext.errMode = 'none';
    </script>

</x-app-layout>
