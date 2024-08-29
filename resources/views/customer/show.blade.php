<x-app-layout>

    <x-slot name="header">
        @include('customer.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.customer_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_full_name" class="w-full mb-1" :value="__('word.customer_full_name')" />
                                <p id="customer_full_name" class="w-full h-9 block mt-1" type="text"
                                    name="customer_full_name">
                                    {{ $customer->customer_full_name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_phone" class="w-full mb-1" :value="__('word.customer_phone')" />
                                <p id="customer_phone" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_phone">
                                    {{ $customer->customer_phone }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_email" class="w-full mb-1" :value="__('word.customer_email')" />
                                <p id="customer_email" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_email">
                                    {{ $customer->customer_email }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_number" class="w-full mb-1" :value="__('word.customer_card_number')" />
                                <p id="customer_card_number" class="w-full h-9 block mt-1" type="text"
                                    name="customer_card_number">
                                    {{ $customer->customer_card_number }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_issud_auth" class="w-full mb-1" :value="__('word.customer_card_issud_auth')" />
                                <p id="customer_card_issud_auth" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_issud_auth">
                                    {{ $customer->customer_card_issud_auth }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="customer_card_issud_date" class="w-full mb-1" :value="__('word.customer_card_issud_date')" />
                                <p id="customer_card_issud_date" class="w-full h-9 block mt-1 " type="text"
                                    name="customer_card_issud_date">
                                    {{ $customer->customer_card_issud_date }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($customer->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $customer->user_create->name }}
                                    {{ $customer->created_at }}
                                </div>
                            @endif

                            @if (isset($customer->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $customer->user_update->name }}
                                    {{ $customer->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
