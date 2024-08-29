<x-app-layout>

    <x-slot name="header">
        @include('user.nav.navigation')
    </x-slot>


    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex-column justify-content-around mt-4">
                        <div class=" mx-4 my-4 ">
                            <x-input-label for="name" class=" mb-1" :value="__('word.user_name')" />
                            <p id="name" class=" h-9 block mt-4" type="text" name="user_name">
                                {{ $user->name }}
                            </p>

                        </div>

                        <div class=" mx-4 my-4 ">
                            <x-input-label for="email" class=" mb-1" :value="__('word.email')" />
                            <p id="email" class=" h-9 block mt-4 " type="text" name="email">
                                {{ $user->email }}
                            </p>

                        </div>

                        <div class=" mx-4 my-4 ">
                            <x-input-label for="department_id" class=" mb-1" :value="__('word.department_id')" />
                            <p id="department_id" class=" h-9 block mt-4 " type="text" name="department_id">
                                {{ $user->get_department->department }}
                            </p>

                        </div>

                        <div class=" mx-4 my-4 ">
                            <x-input-label for="user_status" class=" mb-1" :value="__('word.user_status')" />
                            <p id="user_status" class=" h-9 block mt-4 " type="text" name="user_status">
                                {{ $user->Status }}
                            </p>

                        </div>


                        <div class=" mx-4 my-4 ">
                            <x-input-label for="user_role" class=" mb-1" :value="__('word.user_role')" />
                            <p id="user_role" class=" h-9 block mt-4 " type="text" name="user_role">

                                @if (!empty($user->getRoleNames()))
                                    @foreach ($user->getRoleNames() as $v)
                                        <label
                                            class=" font-bold bg-danger text-white rounded">{{ $v }}</label>
                                    @endforeach
                                @endif

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
