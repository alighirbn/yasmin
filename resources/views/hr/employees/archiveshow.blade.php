<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('hr.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons mb-4">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            العودة
                        </a>
                    </div>

                    <div class="row">
                        @if ($employee->images->isEmpty())
                            <p class="text-center w-100">لا توجد صور مؤرشفة لهذا الموظف</p>
                        @else
                            @foreach ($employee->images as $image)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="{{ asset($image->image_path) }}" class="card-img-top"
                                            alt="Employee Image">
                                        <div class="card-body">
                                            <p class="card-text">تمت الإضافة في: {{ $image->created_at }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
