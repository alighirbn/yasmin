<x-app-layout>
    <x-slot name="header">
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-4">
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

                    <!-- Filter inputs -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="contract-id-filter" class="form-control"
                                placeholder="{{ __('word.contract_id') }}">
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
            var table = $('#contract-table').DataTable();
            // Apply filter on contract ID column (exact match)
            $('#contract-id-filter').on('keyup', function() {
                if (this.value === '') {
                    // If the input is empty, reset the filter and show all rows
                    table.column(1).search('').draw(); // Show all contract IDs
                } else {
                    // If there's a value, filter by exact match
                    table.column(1).search('^' + this.value + '$', true, false).draw();
                }
            });
        });

        $.fn.dataTable.ext.errMode = 'none';
    </script>
</x-app-layout>
