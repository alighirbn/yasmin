<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>تفاصيل موظف</h2>
<ul><li><strong>الاسم الأول:</strong> {{ $item->first_name }}</li>
<li><strong>الاسم الأخير:</strong> {{ $item->last_name }}</li>
<li><strong>القسم:</strong> {{ $item->department }}</li>
<li><strong>الراتب:</strong> {{ $item->basic_salary }}</li>
</ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
