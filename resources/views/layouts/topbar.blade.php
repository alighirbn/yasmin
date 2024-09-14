<div class="w-full p-2 flex justify-between" style="background-color: #2b6a9a;">
    <button onclick="myFunction()" x-on:click.prevent="isOpen = !isOpen">
        <i class='bx bx-menu' style="color: #fff;">{{ __('word.dashboard') }}</i>
    </button>

    <div class="flex">
        <!-- Languages Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-1">
            <x-dropdown alignment="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ LaravelLocalization::getCurrentLocaleNative() }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <ul>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Notifications Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-1">
            <x-dropdown alignment="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <span class="inline-flex">
                            @if (auth()->user()->unreadNotifications()->count() > 0)
                                <span class="w-4 h-4 font-bold bg-red-600 text-white rounded">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                            <svg class="w-5 h-5 text-gray-700 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <ul>
                        @if (auth()->user()->unreadNotifications()->count() > 0)
                            <li class="border border-solid">
                                <a rel="alternate" href="{{ route('notification.markallasread') }}"
                                    class="block w-full px-4 py-2 text-center leading-5 text-green-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('word.markallasread') }}
                                </a>
                            </li>
                        @endif
                        @forelse (auth()->user()->unreadNotifications()->take(5)->get() as $notification)
                            <li class="border border-solid">
                                <a rel="alternate" href="{{ route('notification.markasread', $notification) }}"
                                    class="block w-full px-4 py-2 text-center leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $notification->data['name'] . ' - ' . $notification->data['action'] . ' - ' . \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </a>
                            </li>
                        @empty
                            <li>
                                <a rel="alternate"
                                    class="block w-full px-4 py-2 text-center leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('word.nonotification') }}
                                </a>
                            </li>
                        @endforelse
                        @forelse (auth()->user()->readNotifications()->take(5)->get() as $notification)
                            <li class="border border-solid">
                                <a rel="alternate" href="{{ route('notification.markasread', $notification) }}"
                                    class="block w-full px-4 py-2 text-center text-sm leading-5 text-gray-400 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $notification->data['name'] . ' - ' . $notification->data['action'] . ' - ' . \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </a>
                            </li>
                        @empty
                        @endforelse
                        <li class="border border-solid">
                            <a rel="alternate" href="{{ route('notification.index') }}"
                                class="block w-full px-4 py-2 text-center leading-5 text-black-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                {{ __('word.show_all') }}
                            </a>
                        </li>
                    </ul>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-1">
            <x-dropdown align="left" width="46">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('word.Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>
