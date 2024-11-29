<x-app-layout>
    <x-slot name="header">
        @include('customer.nav.navigation')
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

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="text" id="filter-customer" class="form-control"
                                placeholder="{{ __('ابحث عن اسم زبون') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="filter-phone" class="form-control"
                                placeholder="{{ __('ابحث عن رقم الموبايل') }}">
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

    <script>
        $(document).ready(function() {
            var table = $('#customer-table').DataTable();

            // Custom filters
            $('#filter-customer').on('keyup', function() {
                table.column(2).search(this.value).draw(); // Adjust index if needed
            });
            $('#filter-phone').on('keyup', function() {
                table.column(3).search(this.value).draw(); // Adjust index if needed
            });
        });

        // Handle DataTable errors gracefully
        $.fn.dataTable.ext.errMode = 'none';

        // Log if there's an error in AJAX call
        $('#customer-table').on('error.dt', function(e, settings, techNote, message) {
            console.log('An error has been reported by DataTables: ', message);
        });
    </script>
</x-app-layout>
