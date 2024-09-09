@extends('layouts.app')

@section('content')
    <div class="text-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
            {{ __('Your Events') }}
        </h2>
    </div>

    <main>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-bold mb-6 text-center">{{ __('Your Upcoming Events') }}</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($events as $event)
                                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg shadow-lg p-4 border border-gray-300 dark:border-gray-600 flex flex-col justify-between aspect-w-1 aspect-h-1">
                                    <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        {{ $event->name }}
                                    </a>
                                    <span class="block text-gray-600 dark:text-gray-400 text-sm">
                                        {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                    </span>
                                    <!-- Delete Button -->
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure?')">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
