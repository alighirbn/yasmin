<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('payment.nav.navigation')
        @include('expense.nav.navigation')
        @include('cash_account.nav.navigation')

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
                        <form method="post" action="{{ route('cash_account.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.cash_account_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="account_name" class="w-full mb-1" :value="__('word.account_name')" />
                                    <x-text-input id="account_name" class="w-full block mt-1" type="text"
                                        name="account_name" value="{{ old('account_name') }}" />
                                    <x-input-error :messages="$errors->get('account_name')" class="w-full mt-2" />
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
