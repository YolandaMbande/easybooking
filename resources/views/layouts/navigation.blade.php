<nav x-data="{ open: false }" class="bg-white border-b border-gray-700">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">

            <!-- LEFT SIDE: Navigation -->
            <div class="hidden sm:flex items-center space-x-6">

                <x-nav-link
                    :href="route('explore_events')"
                    :active="request()->routeIs('explore_events')"
                    class="text-sm text-gray-900 hover:text-gray-400"
                >
                    {{ __('Explore Events') }}
                </x-nav-link>

                @if(Auth::check() && Auth::user()->hasRole('organizer'))

                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="text-sm text-gray-900 hover:text-gray-400"
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link
                        :href="route('organiser.create_event')"
                        :active="request()->routeIs('organiser.create_event')"
                        class="text-sm text-gray-900 hover:text-gray-400"
                    >
                        {{ __('Create Event') }}
                    </x-nav-link>

                @endif

            </div>


            <!-- CENTER: Logo + Easy Booking -->
            <div class="absolute left-1/2 -translate-x-1/2 flex items-center">
                <a
                    href="{{ route('welcome') }}"
                    class="flex items-center space-x-2"
                >

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Easy Booking"
                    class="h-12 w-auto"
                >
                    <span class="text-base font-semibold text-gray-900 whitespace-nowrap">
                        Easy Booking
                    </span>
                </a>
            </div>


            <!-- RIGHT SIDE: User / Authentication -->
            <div class="hidden sm:flex sm:items-center ml-auto">

                @if(Auth::check())

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-gray-700 hover:bg-gray-600 rounded-md">
                                {{ Auth::user()->name }}

                                <svg
                                    class="fill-current h-3.5 w-3.5 ml-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>

                        </x-slot>

                    </x-dropdown>

                @else

                    <div class="flex items-center space-x-4">
                        <x-nav-link
                            :href="route('register')"
                            :active="request()->routeIs('register')"
                            class="text-sm font-semibold text-gray-900 hover:text-gray-400"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 mr-1"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                                />
                            </svg>

                            {{ __('Register') }}
                        </x-nav-link>
                        <!-- <x-nav-link
                            :href="route('login')"
                            :active="request()->routeIs('login')"
                            class="text-sm font-semibold text-gray-900 hover:text-gray-400"
                        >
                            {{ __('Login') }}
                        </x-nav-link>
-->
                    </div>

                @endif

            </div>


            <!-- MOBILE HAMBURGER -->
            <div class="sm:hidden ml-auto">

                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:bg-gray-600 transition duration-150 ease-in-out"
                >
                    <svg
                        class="h-5 w-5"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{'hidden': open, 'inline-flex': ! open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{'hidden': ! open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>

        </div>
    </div>


    <!-- MOBILE MENU -->
    <div
        :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden"
    >

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
                :href="route('about')"
                :active="request()->routeIs('about')"
                class="text-gray-400 hover:text-gray-900"
            >
                {{ __('About Us') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('explore_events')"
                :active="request()->routeIs('explore_events')"
                class="text-gray-400 hover:text-gray-900"
            >
                {{ __('Explore Events') }}
            </x-responsive-nav-link>

            @if(Auth::check() && Auth::user()->hasRole('organizer'))

                <x-responsive-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                    class="text-gray-400 hover:text-gray-900"
                >
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('organiser.create_event')"
                    :active="request()->routeIs('organiser.create_event')"
                    class="text-gray-400 hover:text-gray-900"
                >
                    {{ __('Create Event') }}
                </x-responsive-nav-link>

            @endif

        </div>


        <div class="pt-4 pb-1 border-t border-gray-600">

            <div class="mt-6 space-y-1">

                @if(Auth::check())

                    <x-responsive-nav-link
                        :href="route('profile.edit')"
                        class="text-gray-400 hover:text-gray-900"
                    >
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-gray-400 hover:text-gray-900"
                        >
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                @else

                    <x-responsive-nav-link
                        :href="route('register')"
                        class="text-gray-400 hover:text-gray-900"
                    >
                        {{ __('Register') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link
                        :href="route('login')"
                        class="text-gray-400 hover:text-gray-900"
                    >
                        {{ __('Login') }}
                    </x-responsive-nav-link>

                @endif

            </div>

        </div>

    </div>
</nav>
