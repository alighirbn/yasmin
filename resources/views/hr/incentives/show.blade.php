<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>تفاصيل حافز/استقطاع</h2>
<ul><li><strong>النوع:</strong> {{ $item->type }}</li>
<li><strong>المبلغ:</strong> {{ $item->amount }}</li>
<li><strong>السبب:</strong> {{ $item->reason }}</li>
</ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
