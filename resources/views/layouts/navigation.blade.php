<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700">   
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <div class="text-xl font-semibold text-white">
                    <a href="{{route('welcome')}}">
                    Easy Booking
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-white hover:text-gray-400">
                        {{ __('About Us') }}
                    </x-nav-link>
                    <x-nav-link :href="route('explore_events')" :active="request()->routeIs('explore_events')" class="text-white hover:text-gray-400">
                        {{ __('Explore Events') }}
                    </x-nav-link>

                    @if(Auth::check() && Auth::user()->hasRole('organizer'))
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-400">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('organiser.create_event')" :active="request()->routeIs('organiser.create_event')" class="text-white hover:text-gray-400">
                            {{ __('Create Event') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-white bg-gray-700 hover:bg-gray-600 rounded-md">
                                {{ Auth::user()->name }}
                                <svg class="fill-current h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                        
                    </x-dropdown>
                @else
                    <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="text-lg font-bold text-white hover:text-gray-400">
                        {{ __('Register') }}
                    </x-nav-link>
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')" class="text-lg font-bold text-white hover:text-gray-400">
                        {{ __('Login') }}
                    </x-nav-link>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:bg-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check() && Auth::user()->hasRole('organizer'))
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-400 hover:text-white">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('organiser.create_event')" :active="request()->routeIs('organiser.create_event')" class="text-gray-400 hover:text-white">
                    {{ __('Create Event') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="mt-6 space-y-1">
                @if(Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-400 hover:text-white">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-400 hover:text-white">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('register')" class="text-gray-400 hover:text-white">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('login')" class="text-gray-400 hover:text-white">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        </div>
    </div>
</nav>
