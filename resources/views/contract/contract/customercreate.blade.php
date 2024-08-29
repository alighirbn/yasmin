<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>
                    <div>
                        <form method="post" action="{{ route('contract.customerstore') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4 my-8 w-full">
                                {{ __('word.customer_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                    <x-text-input id="customer_full_name" class="w-full block mt-1" type="text"
                                        name="customer_full_name" value="{{ old('customer_full_name') }}" />
                                    <x-input-error :messages="$errors->get('customer_full_name')" class="w-full mt-2" />
                                </div>
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                    <x-text-input id="customer_phone" class="w-full block mt-1" type="text"
                                        name="customer_phone" value="{{ old('customer_phone') }}" />
                                    <x-input-error :messages="$errors->get('customer_phone')" class="w-full mt-2" />
                                </div>
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="customer_email" class="w-full mb-1" :value="__('word.customer_email')" />
                                    <x-text-input id="customer_email" class="w-full block mt-1" type="text"
                                        name="customer_email" value="{{ old('customer_email') }}" />
                                    <x-input-error :messages="$errors->get('customer_email')" class="w-full mt-2" />
                                </div>

                            </div>

                            <h2 class="font-semibold underline text-l text-gray-800 leading-tight mx-4 my-8 w-full">
                                {{ __('word.customer_card') }}
                            </h2>

                            <div class="flex">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                    <x-text-input id="customer_card_number" class="w-full block mt-1" type="text"
                                        name="customer_card_number" value="{{ old('customer_card_number') }}" />
                                    <x-input-error :messages="$errors->get('customer_card_number')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="customer_card_issud_auth" class="w-full mb-1"
                                        :value="__('word.customer_card_issud_auth')" />
                                    <x-text-input id="customer_card_issud_auth" class="w-full block mt-1" type="text"
                                        name="customer_card_issud_auth"
                                        value="{{ old('customer_card_issud_auth') }}" />
                                    <x-input-error :messages="$errors->get('customer_card_issud_auth')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="customer_card_issud_date" class="w-full mb-1"
                                        :value="__('word.customer_card_issud_date')" />
                                    <x-text-input id="customer_card_issud_date" class="w-full block mt-1" type="text"
                                        name="customer_card_issud_date"
                                        value="{{ old('customer_card_issud_date') }}" />
                                    <x-input-error :messages="$errors->get('customer_card_issud_date')" class="w-full mt-2" />
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
        // Prevent double submission
        $('form').on('submit', function() {
            // Find the submit button
            var $submitButton = $(this).find('button[type="submit"]');

            // Change the button text to 'Submitting...'
            $submitButton.text('جاري الحفظ');

            // Disable the submit button
            $submitButton.prop('disabled', true);
        });
    </script>
</x-app-layout>
