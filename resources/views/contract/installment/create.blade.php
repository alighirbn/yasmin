<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        @include('contract.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mx-4 mb-4">
                        إضافة قسط جديد للعقد
                    </h2>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Contract Info Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h3 class="font-semibold text-lg mb-4">معلومات العقد</h3>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <span class="text-gray-600">رقم العقد:</span>
                                <span class="font-bold">{{ $contract->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">اسم الزبون:</span>
                                <span class="font-bold">{{ $contract->customer->customer_full_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">مبلغ العقد:</span>
                                <span class="font-bold">{{ number_format($contract->contract_amount, 0) }} IQD</span>
                            </div>
                            <div>
                                <span class="text-gray-600">مجموع الأقساط الحالي:</span>
                                <span
                                    class="font-bold">{{ number_format($contract->contract_installments->sum('installment_amount'), 0) }}
                                    IQD</span>
                            </div>
                        </div>

                        @php
                            $remaining =
                                $contract->contract_amount -
                                $contract->contract_installments->sum('installment_amount');
                        @endphp

                        @if ($remaining > 1)
                            <div class="mt-4 p-4 bg-yellow-100 rounded">
                                <strong>⚠️ ملاحظة:</strong> هناك فرق قدره {{ number_format($remaining, 0) }} IQD بين
                                مبلغ العقد ومجموع الأقساط.
                            </div>
                        @elseif($remaining < -1)
                            <div class="mt-4 p-4 bg-red-100 rounded">
                                <strong>⚠️ تحذير:</strong> مجموع الأقساط يتجاوز مبلغ العقد بمقدار
                                {{ number_format(abs($remaining), 0) }} IQD.
                            </div>
                        @else
                            <div class="mt-4 p-4 bg-green-100 rounded">
                                <strong>✓ ممتاز:</strong> مجموع الأقساط متطابق مع مبلغ العقد.
                            </div>
                        @endif
                    </div>

                    <!-- Create Form -->
                    <form method="POST" action="{{ route('contract.installment.store', $contract->url_address) }}">
                        @csrf

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="font-semibold text-lg mb-6">بيانات القسط الجديد</h3>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Installment Type -->
                                <div>
                                    <x-input-label for="installment_id" class="mb-2" value="نوع القسط" />
                                    <select id="installment_id" name="installment_id" class="select2 block w-full"
                                        required>
                                        <option value="">اختر نوع القسط</option>
                                        @foreach ($installmentTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('installment_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->installment_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('installment_id')" class="mt-2" />
                                </div>

                                <!-- Sequence Number -->
                                <div>
                                    <x-input-label for="sequence_number" class="mb-2" value="رقم الترتيب" />
                                    <x-text-input id="sequence_number" class="w-full block" type="number"
                                        name="sequence_number" :value="old(
                                            'sequence_number',
                                            $contract->contract_installments->max('sequence_number') + 1,
                                        )" />
                                    <x-input-error :messages="$errors->get('sequence_number')" class="mt-2" />
                                    <p class="text-sm text-gray-500 mt-1">
                                        اترك فارغاً للترتيب التلقائي
                                    </p>
                                </div>

                                <!-- Installment Amount -->
                                <div>
                                    <x-input-label for="installment_amount" class="mb-2" value="مبلغ القسط (IQD)" />
                                    <x-text-input id="installment_amount" class="w-full block" type="number"
                                        step="0.01" name="installment_amount" :value="old('installment_amount', $remaining > 1 ? $remaining : 0)" required />
                                    <x-input-error :messages="$errors->get('installment_amount')" class="mt-2" />
                                </div>

                                <!-- Installment Date -->
                                <div>
                                    <x-input-label for="installment_date" class="mb-2" value="تاريخ الاستحقاق" />
                                    <x-text-input id="installment_date" class="w-full block" type="date"
                                        name="installment_date" :value="old('installment_date', now()->format('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('installment_date')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="mt-6 p-4 bg-blue-50 rounded">
                                <p class="text-sm text-gray-700">
                                    <strong>💡 نصيحة:</strong>
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                                    <li>تأكد من أن مجموع جميع الأقساط يساوي مبلغ العقد</li>
                                    <li>رقم الترتيب يحدد موضع القسط في قائمة الأقساط</li>
                                    <li>يمكنك إعادة ترتيب الأقساط لاحقاً من خلال التعديل الجماعي</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-4 mt-6">
                                <a href="{{ route('contract.show', $contract->url_address) }}" class="btn btn-outline">
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    إضافة القسط
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Existing Installments Reference -->
                    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                        <h3 class="font-semibold text-lg mb-4">الأقساط الحالية</h3>
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>الترتيب</th>
                                        <th>نوع القسط</th>
                                        <th>المبلغ</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contract->contract_installments()->orderBy('sequence_number')->get() as $inst)
                                        <tr>
                                            <td>{{ $inst->sequence_number }}</td>
                                            <td>{{ $inst->installment->installment_name ?? 'غير محدد' }}</td>
                                            <td>{{ number_format($inst->installment_amount, 0) }} IQD</td>
                                            <td>{{ $inst->installment_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($inst->isFullyPaid())
                                                    <span class="badge badge-success">مدفوع</span>
                                                @elseif($inst->isPartiallyPaid())
                                                    <span class="badge badge-warning">جزئي</span>
                                                @else
                                                    <span class="badge badge-secondary">غير مدفوع</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold">
                                        <td colspan="2">المجموع:</td>
                                        <td>{{ number_format($contract->contract_installments->sum('installment_amount'), 0) }}
                                            IQD</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    dir: 'rtl',
                    language: 'ar'
                });
            });
        </script>
    @endpush
</x-app-layout>
