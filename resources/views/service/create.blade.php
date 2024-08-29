<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('service.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('service.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.service_info') }}
                            </h1>

                            <div class="flex ">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="service_contract_id" class="w-full mb-1" :value="__('word.building_number')" />
                                    <select id="service_contract_id" class="js-example-basic-single w-full block mt-1 "
                                        name="service_contract_id" data-placeholder="ادخل الاسم او رقم العقار">
                                        <option value="">

                                        </option>
                                        @foreach ($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                {{ old('service_contract_id') == $contract->id ? 'selected' : '' }}>
                                                {{ $contract->customer->customer_full_name . ' ** رقم العقار --   ' . $contract->building->building_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('service_contract_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="service_type_id" class="w-full mb-1" :value="__('word.service_type_id')" />
                                    <select id="service_type_id" class="js-example-basic-single w-full block mt-1 "
                                        name="service_type_id" data-placeholder="ادخل نوع الخدمة">
                                        <option value="">

                                        </option>
                                        @foreach ($service_types as $service_type)
                                            <option value="{{ $service_type->id }}"
                                                {{ old('service_type_id') == $service_type->id ? 'selected' : '' }}>
                                                {{ $service_type->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('service_type_id')" class="w-full mt-2" />
                                </div>

                            </div>

                            <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4  w-full">
                                {{ __('word.service_card') }}
                            </h2>

                            <div class="flex">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="service_amount" class="w-full mb-1" :value="__('word.service_amount')" />
                                    <x-text-input id="service_amount" class="w-full block mt-1" type="text"
                                        name="service_amount" value="{{ old('service_amount') }}" />
                                    <x-input-error :messages="$errors->get('service_amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="service_date" class="w-full mb-1" :value="__('word.service_date')" />
                                    <x-text-input id="service_date" class="w-full block mt-1" type="text"
                                        name="service_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                    <x-input-error :messages="$errors->get('service_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="service_note" class="w-full mb-1" :value="__('word.service_note')" />
                                    <x-text-input id="service_note" class="w-full block mt-1" type="text"
                                        name="service_note" value="{{ old('service_note') }}" />
                                    <x-input-error :messages="$errors->get('service_note')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class=" mx-4 my-4 w-full">
                                <x-primary-button x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
</x-app-layout>
