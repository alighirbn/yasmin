<x-app-layout>

    <x-slot name="header">
        @include('role.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="POST" action="{{ route('role.update', $role->id) }}">
                            @method('patch')
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('word.role_name') }}</label>
                                <input value="{{ $role->name }}" type="text" class="form-control" name="name"
                                    placeholder="Name" required>
                            </div>

                            <label for="permissions" class="form-label">{{ __('word.permission') }}</label>

                            <table class="table table-striped">
                                <thead>
                                    <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                                    <th scope="col" width="20%">{{ __('word.name') }}</th>
                                    <th scope="col" width="1%">{{ __('word.guard') }}</th>
                                </thead>

                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="permission[{{ $permission->id }}]"
                                                value="{{ $permission->id }}" class='permission'
                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ __('word.' . $permission->name) . ' -- ' . $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('word.edit') }}</button>
                            <a href="{{ route('role.index') }}" class="btn btn-default">{{ __('word.back') }}</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('[name="all_permission"]').on('click', function() {

                if ($(this).is(':checked')) {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', false);
                    });
                }

            });
        });
    </script>
</x-app-layout>
