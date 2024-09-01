<x-app-layout>

    <x-slot name="header">
        @include('building.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('building.update', $building->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $building->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $building->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                                {{ __('word.building_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="building_number" class="w-full mb-1" :value="__('word.building_number')" />
                                    <x-text-input id="building_number" class="w-full block mt-1" type="text"
                                        name="building_number"
                                        value="{{ old('building_number') ?? $building->building_number }}" />
                                    <x-input-error :messages="$errors->get('building_number')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="building_category_id" class="w-full mb-1" :value="__('word.building_category_id')" />
                                    <select id="building_category_id" class="w-full block mt-1 "
                                        name="building_category_id">
                                        @foreach ($building_categorys as $building_category)
                                            <option value="{{ $building_category->id }}"
                                                {{ (old('building_category_id') ?? $building->building_category_id) == $building_category->id ? 'selected' : '' }}>
                                                {{ $building_category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('building_category_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="building_type_id" class="w-full mb-1" :value="__('word.building_type_id')" />
                                    <select id="building_type_id" class="w-full block mt-1 " name="building_type_id">
                                        @foreach ($building_types as $building_type)
                                            <option value="{{ $building_type->id }}"
                                                {{ (old('building_type_id') ?? $building->building_type_id) == $building_type->id ? 'selected' : '' }}>
                                                {{ $building_type->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('building_type_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="block_number" class="w-full mb-1" :value="__('word.block_number')" />
                                    <x-text-input id="block_number" class="w-full block mt-1" type="text"
                                        name="block_number"
                                        value="{{ old('block_number') ?? $building->block_number }}" />
                                    <x-input-error :messages="$errors->get('block_number')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="house_number" class="w-full mb-1" :value="__('word.house_number')" />
                                    <x-text-input id="house_number" class="w-full block mt-1" type="text"
                                        name="house_number"
                                        value="{{ old('house_number') ?? $building->house_number }}" />
                                    <x-input-error :messages="$errors->get('house_number')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="building_area" class="w-full mb-1" :value="__('word.building_area')" />
                                    <x-text-input id="building_area" class="w-full block mt-1" type="text"
                                        name="building_area"
                                        value="{{ old('building_area') ?? $building->building_area }}" />
                                    <x-input-error :messages="$errors->get('building_area')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="building_map_x" class="w-full mb-1" :value="__('word.building_map_x')" />
                                    <x-text-input id="building_map_x" class="w-full block mt-1" type="text"
                                        name="building_map_x"
                                        value="{{ old('building_map_x') ?? $building->building_map_x }}" />
                                    <x-input-error :messages="$errors->get('building_map_x')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="building_map_y" class="w-full mb-1" :value="__('word.building_map_y')" />
                                    <x-text-input id="building_map_y" class="w-full block mt-1" type="text"
                                        name="building_map_y"
                                        value="{{ old('building_map_y') ?? $building->building_map_y }}" />
                                    <x-input-error :messages="$errors->get('building_map_y')" class="w-full mt-2" />
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

</x-app-layout>
