<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('user.nav.navigation')

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
                    <table dir="rtl" class="table table-hover text-center" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                                <th class="wd-10p border-bottom-0"> {{ __('word.action') }}</th>
                                <th class="wd-15p border-bottom-0"> {{ __('word.user_name') }}</th>
                                <th class="wd-20p border-bottom-0"> {{ __('word.email') }}</th>
                                <th class="wd-15p border-bottom-0"> {{ __('word.department_id') }}</th>
                                <th class="wd-15p border-bottom-0"> {{ __('word.user_status') }}</th>
                                <th class="wd-15p border-bottom-0"> {{ __('word.user_role') }} </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>
                                        <div class="flex ">
                                            @can('user-show')
                                                <a href="{{ route('user.show', $user->url_address) }}"
                                                    class="my-2 mx-2 view btn btn-custom-show">
                                                    {{ __('word.view') }}
                                                </a>
                                            @endcan
                                            @can('user-update')
                                                <a href="{{ route('user.edit', $user->url_address) }}"
                                                    class="my-2 mx-2 view btn btn-custom-edit">
                                                    {{ __('word.edit') }}
                                                </a>
                                            @endcan
                                            @can('user-delete')
                                                <form action="{{ route('user.destroy', $user->url_address) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="my-2 mx-2 btn btn-custom-delete">
                                                        {{ __('word.delete') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->get_department->department }}</td>
                                    <td>
                                        {{ $user->Status }}
                                    </td>

                                    <td>
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $v)
                                                <label
                                                    class=" font-bold bg-danger text-white rounded">{{ $v }}</label>
                                            @endforeach
                                        @endif
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
