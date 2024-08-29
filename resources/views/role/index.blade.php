<x-app-layout>

    <x-slot name="header">

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
                                                        class="my-2 mx-2 view btn btn-success edit">
                                                        {{ __('word.view') }}
                                                    </a>
                                                @endcan
                                                @can('role-update')
                                                    <a href="{{ route('role.edit', $role->id) }}"
                                                        class="my-2 mx-2 view btn btn-warning">
                                                        {{ __('word.edit') }}
                                                    </a>
                                                @endcan
                                                @can('role-delete')
                                                    <form action="{{ route('role.destroy', $role->id) }}" method="post"
                                                        class="my-2 mx-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-primary-button class="ml-4">
                                                            {{ __('word.delete') }}
                                                        </x-primary-button>
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
