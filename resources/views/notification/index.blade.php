<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('word.notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <br>

                    <ul>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications()->count();
                            $readCount = auth()->user()->readNotifications()->count();
                        @endphp

                        @if ($unreadCount > 0)
                            <li>
                                <a rel="alternate" href="{{ route('notification.markallasread') }}"
                                    class="block w-full px-4 py-2 text-right leading-5 text-green-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    <span class="w-full px-4 py-2 font-bold bg-success text-white rounded">
                                        {{ __('word.markallasread') }}
                                    </span>
                                </a>
                            </li>

                            <br>
                            <li>
                                <a rel="alternate"
                                    class="block w-full px-4 py-2 text-right leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('word.unreadnotification') }}
                                    {{ $unreadCount }}
                                </a>
                            </li>
                        @endif

                        @forelse (auth()->user()->unreadNotifications()->take(500)->get() as $notification)
                            <li class="border border-solid">
                                <a rel="alternate" href="{{ route('notification.markasread', $notification) }}"
                                    class="block w-full px-4 py-2 text-lg text-right leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $notification->data['name'] . ' - ' . $notification->data['action'] . ' - وقت الاشعار - ' . \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s') }}
                                </a>
                            </li>
                        @empty
                            <li>
                                <a rel="alternate"
                                    class="block w-full px-4 py-2 text-right leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('word.nonotification') }}
                                </a>
                            </li>
                        @endforelse
                        <br>

                        <li>
                            <a rel="alternate"
                                class="block w-full px-4 py-2 text-right leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                {{ __('word.readnotification') }}
                                {{ $readCount }}
                            </a>
                        </li>
                        @forelse (auth()->user()->readNotifications()->take(500)->get() as $notification)
                            <li class="border border-solid">
                                <a rel="alternate" href="{{ route('notification.markasread', $notification) }}"
                                    class="block w-full px-4 py-2 text-lg text-right leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ $notification->data['name'] . ' - ' . $notification->data['action'] . ' - وقت الاشعار - ' . \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s') . ' - وقت فتح الاشعار - ' . \Carbon\Carbon::parse($notification->read_at)->format('Y-m-d H:i:s') }}
                                </a>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
