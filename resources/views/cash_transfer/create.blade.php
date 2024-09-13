<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('payment.nav.navigation')
        @include('expense.nav.navigation')
        @include('cash_account.nav.navigation')
        @include('cash_transfer.nav.navigation')

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
                    <div class="container">
                        <h1>إضافة تحويل نقدي جديد</h1>

                        <form action="{{ route('cash_transfer.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="from_account_id">من حساب:</label>
                                <select name="from_account_id" id="from_account_id" class="form-control">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                                @error('from_account_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="to_account_id">إلى حساب:</label>
                                <select name="to_account_id" id="to_account_id" class="form-control">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                                @error('to_account_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="amount">المبلغ:</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                                    required>
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="transfer_date">تاريخ التحويل:</label>
                                <input type="date" name="transfer_date" id="transfer_date" class="form-control"
                                    required>
                                @error('transfer_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="transfer_note">ملاحظات:</label>
                                <textarea name="transfer_note" id="transfer_note" class="form-control"></textarea>
                                @error('transfer_note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">حفظ</button>
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
