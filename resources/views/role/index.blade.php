<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('role.nav.navigation')

    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('word.action') }}</th>
                                    <th>{{ __('word.name') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <div class="flex ">
                                                @can('role-show')
                                                    <a href="{{ route('role.show', $role->id) }}"
                                                        class="my-2 mx-2 view btn btn-custom-show">
                                                        {{ __('word.view') }}
                                                    </a>
                                                @endcan
                                                @can('role-update')
                                                    <a href="{{ route('role.edit', $role->id) }}"
                                                        class="my-2 mx-2 view btn btn-custom-edit">
                                                        {{ __('word.edit') }}
                                                    </a>
                                                @endcan
                                                @can('role-delete')
                                                    <form action="{{ route('role.destroy', $role->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="my-2 mx-2 btn btn-custom-delete">
                                                            {{ __('word.delete') }}
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                        <td>{{ $role->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

</x-app-layout>
