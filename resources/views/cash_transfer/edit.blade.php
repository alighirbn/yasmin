<x-app-layout>

    <x-slot name="header">
        @include('payment.nav.navigation')
        @include('expense.nav.navigation')
        @include('cash_account.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h1>تعديل التحويل النقدي</h1>

                        <form action="{{ route('cash_transfer.update', $transfer->url_address) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="from_account_id">من حساب:</label>
                                <select name="from_account_id" id="from_account_id" class="form-control">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $transfer->from_account_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
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
                                        <option value="{{ $account->id }}"
                                            {{ $account->id == $transfer->to_account_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_account_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="amount">المبلغ:</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                                    value="{{ $transfer->amount }}" required>
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="transfer_date">تاريخ التحويل:</label>
                                <input type="date" name="transfer_date" id="transfer_date" class="form-control"
                                    value="{{ $transfer->transfer_date->format('Y-m-d') }}" required>
                                @error('transfer_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="transfer_note">ملاحظات:</label>
                                <textarea name="transfer_note" id="transfer_note" class="form-control">{{ $transfer->transfer_note }}</textarea>
                                @error('transfer_note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
