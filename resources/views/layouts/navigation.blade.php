<nav x-data="{ open: false }" class="bg-pink-300 border-b border-gray-100 dark:border-gray-700">   
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">

                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <div class="text-xl font-semibold text-white">
                    Easy Booking
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-white hover:text-pink-500">
                        {{ __('About Us') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.explore')" :active="request()->routeIs('events.explore')" class="text-white hover:text-pink-500">
                        {{ __('Explore Events') }}
                    </x-nav-link>

                    @if(Auth::check() && Auth::user()->hasRole('organizer'))
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-pink-500">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="text-white hover:text-pink-500">
                            {{ __('Create Event') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Register & Login Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="text-lg font-bold text-white hover:text-pink-500">
                    {{ __('Register') }}
                </x-nav-link>
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')" class="text-lg font-bold text-white hover:text-pink-500">
                    {{ __('Login') }}
                </x-nav-link>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white bg-pink-300 hover:text-pink-500 focus:outline-none focus:bg-pink-400 transition duration-150 ease-in-out">
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
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-pink-500 hover:text-white">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="text-pink-500 hover:text-white">
                    {{ __('Create Event') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="mt-6 space-y-1">
                <x-responsive-nav-link :href="route('register')" class="text-pink-500 hover:text-white">
                    {{ __('Register') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')" class="text-pink-500 hover:text-white">
                    {{ __('Login') }}
                </x-responsive-nav-link>
            </div>
        </div>
    </div>
</nav>
