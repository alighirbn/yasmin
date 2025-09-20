<x-app-layout>
    <x-slot name="header">
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Success & Error Messages --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Filter Section -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <!-- Contract ID Filter -->
                                <div class="col-md-4">
                                    <label for="contract-id-filter" class="form-label fw-bold">
                                        {{ __('word.contract_id') }}
                                    </label>
                                    <input type="text" id="contract-id-filter" class="form-control"
                                        placeholder="{{ __('word.contract_id') }}">
                                </div>

                                <!-- Stage Filter -->
                                <div class="col-md-4">
                                    <label for="stage-filter" class="form-label fw-bold">
                                        {{ __('word.stage') }}
                                    </label>
                                    <select id="stage-filter" class="form-control">
                                        <option value="">{{ __('word.all_stages') }}</option>
                                        <option value="temporary">{{ __('word.temporary') }}</option>
                                        <option value="accepted">{{ __('word.accepted') }}</option>
                                        <option value="authenticated">{{ __('word.authenticated') }}</option>
                                        <option value="terminated">{{ __('word.terminated') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-bordered table-hover align-middle w-100'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
            var table = $('#contract-table').DataTable();

            // Contract ID filter (exact match)
            $('#contract-id-filter').on('keyup', function() {
                if (this.value === '') {
                    table.column(1).search('').draw();
                } else {
                    table.column(1).search('^' + this.value + '$', true, false).draw();
                }
            });

            // Stage filter (reloads with stage parameter)
            $('#stage-filter').on('change', function() {
                var stage = this.value;
                var url = "{{ route('contract.index') }}";
                if (stage) {
                    url += "?stage=" + stage;
                }
                table.ajax.url(url).load();
            });
        });

        $.fn.dataTable.ext.errMode = 'none';
    </script>
</x-app-layout>
