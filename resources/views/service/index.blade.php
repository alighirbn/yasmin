<x-app-layout>
    <x-slot name="header">
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Success Message --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    {{-- Filters --}}
                    <div class="row mb-3 g-2">
                        <div class="col-md-3">
                            <input type="text" id="filter-customer" class="form-control"
                                placeholder="{{ __('ابحث عن اسم الزبون') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="filter-building" class="form-control"
                                placeholder="{{ __('ابحث عن رقم العقار') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="filter-service-date" class="form-control"
                                placeholder="{{ __('عن تاريخ معين') }}">
                        </div>
                        <div class="col-md-3">
                            <select id="filter-service-type" class="form-control">
                                <option value="">{{ __('اختر نوع الخدمة') }}</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type->type_name }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DataTable --}}
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100'], true) !!}
                </div>
            </div>
        </div>
    </div>

    {{-- DataTables Scripts --}}
    {!! $dataTable->scripts() !!}

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

            $('#filter-service-type').on('change', function() {
                table.column(7).search(this.value).draw(); // Service type column
            });
        });

        // Handle DataTable errors gracefully
        $.fn.dataTable.ext.errMode = 'none';
        $('#service-table').on('error.dt', function(e, settings, techNote, message) {
            console.log('An error has been reported by DataTables: ', message);
        });
    </script>
</x-app-layout>
