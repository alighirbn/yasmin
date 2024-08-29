<x-app-layout>

    <x-slot name="header">
        @include('user.nav.navigation')
    </x-slot>


    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('user.update', $user->url_address) }}">
                            @csrf
                            @method('patch')

                            <h2 class="font-semibold text-l text-gray-800 leading-tight mx-4 my-4 w-full">
                                {{ __('word.user_info') }}
                            </h2>
                            <div class="flex">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="name" class=" mb-1" :value="__('word.user_name')" />
                                    <x-text-input id="name" class="w-full block mt-1" type="text"
                                        value="{{ $user->name }}" name="name" />
                                    <x-input-error :messages="$errors->get('name')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="email" class=" mb-1" :value="__('word.email')" />
                                    <x-text-input id="email" class="w-full block mt-1 " type="text"
                                        value="{{ $user->email }}" name="email" />
                                    <x-input-error :messages="$errors->get('email')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="password" class=" mb-1" :value="__('word.password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" />
                                    <x-input-error :messages="$errors->get('password')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="password_confirmation" :value="__('word.confirm_password')" />

                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" />

                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                            <div class=" mx-4 my-4 block">
                                <x-input-label for="department_id" class="w-full mb-1" :value="__('word.department_id')" />
                                <select id="department_id" class="w-full block mt-1 " name="department_id">
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->department }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department_id')" class="w-full mt-2" />
                            </div>

                            <div class=" mx-4 my-4 ">
                                <x-input-label for="Status" class="w-full mb-1" :value="__('word.user_status')" />
                                <select name="Status" id="select-beast"
                                    class="form-control  nice-select  custom-select">
                                    <option value="active" {{ $user->Status == 'active' ? 'selected' : '' }}>active
                                    </option>
                                    <option value="disabled" {{ $user->Status == 'disabled' ? 'selected' : '' }}>
                                        disabled</option>
                                </select>
                                <x-input-error :messages="$errors->get('department_id')" class="w-full mt-2" />
                            </div>
                            <div class=" mx-4 my-4 w-full">
                                <label for="role_name" class="form-label">{{ __('word.user_role') }}</label>
                                <table class="table table-striped">
                                    <thead>
                                        <th scope="col" width="1%"><input type="checkbox" name="all_role"></th>
                                        <th scope="col" width="20%">{{ __('word.name') }}</th>
                                    </thead>

                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="role_name[{{ $role }}]"
                                                    value="{{ $role }}" class='role'
                                                    {{ in_array($role, $userRole) ? 'checked' : '' }}>
                                            </td>
                                            <td>{{ $role }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <x-input-error :messages="$errors->get('role_name')" class="w-full mt-2" />
                            </div>
                            <div class=" mx-4 my-4 w-full">
                                <x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[name="all_role"]').on('click', function() {

                if ($(this).is(':checked')) {
                    $.each($('.role'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.role'), function() {
                        $(this).prop('checked', false);
                    });
                }

            });
        });
    </script>


</x-app-layout>
