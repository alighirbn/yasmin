<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('contract.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ route('contract.show', $installment->contract->url_address) }}"
                            class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mx-4 mb-4">
                        {{ __('word.edit_installment') }}
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
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-gray-600">رقم العقد:</span>
                                <span class="font-bold">{{ $installment->contract->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">اسم الزبون:</span>
                                <span
                                    class="font-bold">{{ $installment->contract->customer->customer_full_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">مبلغ العقد:</span>
                                <span class="font-bold">{{ number_format($installment->contract->contract_amount, 0) }}
                                    IQD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Installment Info Card -->
                    <div class="bg-blue-50 rounded-lg shadow-md p-6 mb-6">
                        <h3 class="font-semibold text-lg mb-4">معلومات القسط الحالي</h3>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <span class="text-gray-600">نوع القسط:</span>
                                <span
                                    class="font-bold">{{ $installment->installment->installment_name ?? 'غير محدد' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">الترتيب:</span>
                                <span class="font-bold">{{ $installment->sequence_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">المبلغ المدفوع:</span>
                                <span
                                    class="font-bold text-green-600">{{ number_format($installment->paid_amount, 0) }}
                                    IQD</span>
                            </div>
                            <div>
                                <span class="text-gray-600">المبلغ المتبقي:</span>
                                <span
                                    class="font-bold text-red-600">{{ number_format($installment->getRemainingAmount(), 0) }}
                                    IQD</span>
                            </div>
                        </div>

                        @if ($installment->payment)
                            <div class="mt-4 p-4 bg-yellow-100 rounded">
                                <strong>⚠️ تنبيه:</strong> هذا القسط له دفعة مرتبطة. التعديلات محدودة.
                            </div>
                        @endif
                    </div>

                    <!-- Edit Form -->
                    <form method="POST"
                        action="{{ route('contract.installment.update', $installment->url_address) }}">
                        @csrf
                        @method('PATCH')

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="font-semibold text-lg mb-6">تعديل بيانات القسط</h3>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- Installment Amount -->
                                <div>
                                    <x-input-label for="installment_amount" class="mb-2" :value="__('word.installment_amount')" />
                                    <x-text-input id="installment_amount" class="w-full block" type="number"
                                        step="0.01" name="installment_amount" :value="old('installment_amount', $installment->installment_amount)" required />
                                    <x-input-error :messages="$errors->get('installment_amount')" class="mt-2" />

                                    <p class="text-sm text-gray-500 mt-2">
                                        الحد الأدنى: {{ number_format($installment->paid_amount, 0) }} IQD
                                    </p>
                                </div>

                                <!-- Installment Date -->
                                <div>
                                    <x-input-label for="installment_date" class="mb-2" :value="__('word.installment_date')" />
                                    <x-text-input id="installment_date" class="w-full block" type="date"
                                        name="installment_date" :value="old(
                                            'installment_date',
                                            $installment->installment_date->format('Y-m-d'),
                                        )" required />
                                    <x-input-error :messages="$errors->get('installment_date')" class="mt-2" />

                                    @if ($installment->isPartiallyPaid())
                                        <p class="text-sm text-orange-600 mt-2">
                                            ⚠️ القسط مدفوع جزئياً - التغيير في التاريخ محدود بـ 30 يوماً
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Warning Message -->
                            <div class="mt-6 p-4 bg-gray-100 rounded">
                                <p class="text-sm text-gray-700">
                                    <strong>ملاحظة:</strong>
                                </p>
                                <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                                    <li>لا يمكن تقليل مبلغ القسط عن المبلغ المدفوع</li>
                                    <li>سيتم التحقق من أن مجموع الأقساط يساوي مبلغ العقد</li>
                                    @if ($installment->isFullyPaid())
                                        <li class="text-red-600">لا يمكن تعديل قسط مدفوع بالكامل</li>
                                    @endif
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-4 mt-6">
                                <a href="{{ route('contract.show', $installment->contract->url_address) }}"
                                    class="btn btn-outline">
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    حفظ التعديلات
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Button (if applicable) -->
                    @if (
                        !$installment->isFullyPaid() &&
                            !$installment->isPartiallyPaid() &&
                            $installment->contract->contract_installments->count() > 1)
                        <div class="mt-6">
                            <form method="POST"
                                action="{{ route('contract.installment.destroy', $installment->url_address) }}"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذا القسط؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    حذف القسط
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
