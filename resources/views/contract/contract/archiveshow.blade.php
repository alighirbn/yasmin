<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('contract.nav.navigation')
        @include('service.nav.navigation')
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

                    <h2>المرفقات</h2>
                    <div class="row">
                        @if ($contract->images->isEmpty())
                            <p>لا توجد مرفقات</p>
                        @else
                            @foreach ($contract->images as $image)
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <img src="{{ asset($image->image_path) }}" class="card-img-top"
                                            alt="Contract Image">
                                        <div class="card-body">
                                            <p class="card-text">تم الالتقاط في: {{ $image->created_at }}</p>
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
