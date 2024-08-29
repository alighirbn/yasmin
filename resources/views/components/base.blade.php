<x-app-layout>
    <!-- Page nav -->
    <x-slot name="header">
        @if (isset($nav))
            {{ $nav }}
        @endif
    </x-slot>

    <!-- Page form or datatable -->
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Script -->
    @if (isset($script))
        {{ $script }}
    @endif

</x-app-layout>
