@extends('layouts.app')

@section('content')
    <main>

        <div class="text-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
                {{ __('Welcome To  Kwa Mayoli!') }}
            </h2>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-gray-100">

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold mb-2 text-center">{{ __('Upcoming Events') }}</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse ($upcomingEvents as $event)
                                        <div class="relative bg-white dark:bg-gray-900 p-4 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                            <h4 class="text-lg font-semibold">{{ $event->title }}</h4>
                                            <div class="absolute inset-0 flex items-center justify-center p-4 bg-gray-800 bg-opacity-70 text-white opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                <p>{{ $event->description }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center">{{ __('No upcoming events found.') }}</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mb-4">
                                <h3 class="text-lg font-bold mb-2 text-center">{{ __('Complete Events') }}</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse ($completeEvents as $event)
                                        <div class="relative bg-white dark:bg-gray-900 p-4 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                            <h4 class="text-lg font-semibold">{{ $event->title }}</h4>
                                            <div class="absolute inset-0 flex items-center justify-center p-4 bg-gray-800 bg-opacity-70 text-white opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                <p>{{ $event->description }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center">{{ __('No complete events found.') }}</p>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold mb-2 text-center">{{ __('Ongoing Events') }}</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse ($ongoingEvents as $event)
                                        <div class="relative bg-white dark:bg-gray-900 p-4 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                            <h4 class="text-lg font-semibold">{{ $event->title }}</h4>
                                            <div class="absolute inset-0 flex items-center justify-center p-4 bg-gray-800 bg-opacity-70 text-white opacity-0 hover:opacity-100 transition-opacity duration-300">
                                                <p>{{ $event->description }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center">{{ __('No ongoing events found.') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
