<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>تفاصيل سلفة</h2>
<ul><li><strong>المبلغ:</strong> {{ $item->amount }}</li>
<li><strong>التاريخ:</strong> {{ $item->date }}</li>
<li><strong>مُسددة؟:</strong> {{ $item->settled }}</li>
</ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
