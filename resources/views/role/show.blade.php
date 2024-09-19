<x-app-layout>

    <x-slot name="header">
        @include('role.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-light p-4 rounded">
                        <h1>{{ $role->name }} </h1>
                        <div class="lead">

                        </div>

                        <div class="container mt-4">

                            <h3>{{ __('word.permission') }}</h3>

                            <table class="table table-striped">
                                <thead>
                                    <th scope="col" width="20%">{{ __('word.name') }}</th>
                                    <th scope="col" width="1%">{{ __('word.guard') }}</th>
                                </thead>

                                @foreach ($rolePermissions as $permission)
                                    <tr>
                                        <td>{{ __('word.' . $permission->name) . ' -- ' . $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                    </div>
                    <div class="mt-4">
                        <a href="{{ route('role.edit', $role->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                        <a href="{{ route('role.index') }}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
