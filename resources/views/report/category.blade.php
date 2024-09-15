<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('report.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    <div class="print-container a4-width mx-auto  bg-white">
                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                <h1 class="text-xl font-semibold mb-4"> تقرير العقارات حسب الفئة</h1>
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: auto; height: 90px;">
                            </div>

                        </div>

                        <table class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        الفئة</th>
                                    <th>
                                        عدد العقود</th>
                                    <th>
                                        عدد العقارات الشاغرة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['combined_data'] as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap ">
                                            {{ $data['category_name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap ">
                                            {{ $data['contract_count'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap ">
                                            {{ $data['building_count'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
