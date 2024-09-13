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

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="text" id="filter-customer" class="form-control"
                                placeholder="{{ __('ابحث عن اسم زبون') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="filter-building" class="form-control"
                                placeholder="{{ __('ابحث عن رقم العقار') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="filter-service-date" class="form-control"
                                placeholder="{{ __('عن تاريخ معين') }}">
                        </div>
                    </div>

                    <!-- DataTable -->
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Script -->
    {!! $dataTable->scripts() !!}

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#service-table').DataTable();

            // Custom filters
            $('#filter-customer').on('keyup', function() {
                table.column(6).search(this.value).draw(); // Customer name column
            });
            $('#filter-building').on('keyup', function() {
                table.column(5).search(this.value).draw(); // Building number column
            });
            $('#filter-service-date').on('change', function() {
                table.column(2).search(this.value).draw(); // Service date column
            });
        });

        // Handle DataTable errors gracefully
        $.fn.dataTable.ext.errMode = 'none';

        // Log if there's an error in AJAX call
        $('#service-table').on('error.dt', function(e, settings, techNote, message) {
            console.log('An error has been reported by DataTables: ', message);
        });
    </script>
</x-app-layout>
