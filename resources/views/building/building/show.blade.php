<x-app-layout>

    <x-slot name="header">
        @include('building.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>

                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                            {{ __('word.building_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                <p id="building_number" class="w-full h-9 block mt-1" type="text"
                                    name="building_number">
                                    {{ $building->building_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                <p id="block_number" class="w-full h-9 block mt-1 " type="text" name="block_number">
                                    {{ $building->block_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                <p id="house_number" class="w-full h-9 block mt-1 " type="text" name="house_number">
                                    {{ $building->house_number }}
                            </div>
                        </div>

                        <div class="flex">
                            @if (isset($building->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $building->user_create->name }}
                                    {{ $building->created_at }}
                                </div>
                            @endif

                            @if (isset($building->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $building->user_update->name }}
                                    {{ $building->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
