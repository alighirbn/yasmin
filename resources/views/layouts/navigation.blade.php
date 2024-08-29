<nav x-data="{ open: false }" class="bg-white border-b border-gray-100" >
    <!-- Primary Navigation Menu -->
    <div class=" max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    @can('dashboard-Planning')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('word.Planning') }}
                        </x-nav-link>
                    @endcan
                    @can('dashboard-users')
                        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                            {{ __('word.users') }}
                        </x-nav-link>
                    @endcan
                    @can('dashboard-roles')
                        <x-nav-link :href="route('role.index')" :active="request()->routeIs('role.index')">
                            {{ __('word.roles') }}
                        </x-nav-link>
                    @endcan
                   
                </div>

            </div>

            <!-- languages Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-1">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                {{ LaravelLocalization::getCurrentLocaleNative() }}
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
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
                                    <a rel="alternate" hreflang="{{ $localeCode }} "
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

            <!-- notification Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-1">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <span class="inline-flex">
                                @if (auth()->user()->unreadNotifications()->groupBy('notifiable_type')->count() == 0)
                                @else
                                    <span class="w-4 h-4 font-bold bg-danger text-white rounded">
                                        {{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif

                                <svg class="w-5 h-5 text-gray-700 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"
                                        clip-rule="evenodd" fill-rule="evenodd"></path>
                                </svg>

                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                        </button>
                    </x-slot>
                    <x-slot name="content">

                        <ul>
                            @if (auth()->user()->unreadNotifications()->count() != 0)
                                <li class=" border border-solid">
                                    <a rel="alternate" href="{{ route('notification.markallasread') }}"
                                        class="block w-full px-4 py-2 text-center leading-5 text-green-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        {{ __('word.markallasread') }}

                                    </a>
                                </li>
                            @endif
                            @forelse (auth()->user()->unreadNotifications()->take(5)->get() as $notification)
                                <li class=" border border-solid">
                                    <a rel="alternate" href="{{ route('notification.markasread', $notification) }}"
                                        class="block w-full px-4 py-2 text-center leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        {{ round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 60, 0) > 59 ? (round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 3600, 0) > 24 ? $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 86400, 0) . 'يوم' : $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 3600, 0) . 'ساعة') : $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 60, 0) . 'دقيقة' }}
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
                                        {{ round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 60, 0) > 59 ? (round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 3600, 0) > 24 ? $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 86400, 0) . 'يوم' : $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 3600, 0) . 'ساعة') : $notification->data['name'] . ' - ' . $notification->data['action'] . ' - قبل ' . round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at)) / 60, 0) . 'دقيقة' }}
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
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('اعدادات الحساب') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>



            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('word.dashboard') }}
            </x-responsive-nav-link>
        </div>
        

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('الحساب') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __(' تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
