@extends('layouts.app')

@section('content')
    <!-- Load Google Maps and Places API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzFvUmTlPsGe3yc1O2Ug7ThQBv7-hpx3I&libraries=places&callback=initAutocomplete" async defer></script>

    <div class="text-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
            {{ __('Create Event') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 text-lg">
            Please fill out this form to create an event.
        </p>
    </div>

    <main>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <form method="POST" action="{{ route('events.store') }}">
                            @csrf

                            <!-- Event Name -->
                            <div>
                                <x-input-label for="name" :value="__('Event Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Event Description -->
                            <div class="mt-4">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" class="block mt-1 w-full" name="description" required></textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Event Location (with autocomplete) -->
                            <div class="mt-4">
                                <x-input-label for="location" :value="__('Location')" />
                                <x-text-input id="autocomplete" class="block mt-1 w-full" type="text" name="location" placeholder="Enter event location" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Hidden Fields for Latitude and Longitude -->
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">

                            <!-- Google Maps -->
                            <div class="mt-4">
                                <x-input-label for="map" :value="__('Event Location on Map')" />
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>

                            <!-- Event Date and Time -->
                            <div class="mt-4">
                                <x-input-label for="date_time" :value="__('Date and Time')" />
                                <x-text-input id="date_time" class="block mt-1 w-full" type="datetime-local" name="date_time" required />
                                <x-input-error :messages="$errors->get('date_time')" class="mt-2" />
                            </div>

                            <!-- Event Category -->
                            <div class="mt-4">
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <!-- Maximum Attendees -->
                            <div class="mt-4">
                                <x-input-label for="max_attendees" :value="__('Maximum Attendees')" />
                                <x-text-input id="max_attendees" class="block mt-1 w-full" type="number" name="max_attendees" required />
                                <x-input-error :messages="$errors->get('max_attendees')" class="mt-2" />
                            </div>

                            <!-- Ticket Price -->
                            <div class="mt-4">
                                <x-input-label for="ticket_price" :value="__('Ticket Price')" />
                                <x-text-input id="ticket_price" class="block mt-1 w-full" type="number" step="0.01" name="ticket_price" required />
                                <x-input-error :messages="$errors->get('ticket_price')" class="mt-2" />
                            </div>

                            <!-- Event Status -->
                            <div class="mt-4">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full" required>
                                    <option value="Upcoming">{{ __('Upcoming') }}</option>
                                    <option value="Ongoing">{{ __('Ongoing') }}</option>
                                    <option value="Completed">{{ __('Completed') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Event Visibility -->
                            <div class="mt-4">
                                <x-input-label for="visibility" :value="__('Visibility')" />
                                <select id="visibility" name="visibility" class="block mt-1 w-full" required>
                                    <option value="Public">{{ __('Public') }}</option>
                                    <option value="Private">{{ __('Private') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('visibility')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-4">
                                    {{ __('Create Event') }}
                                </x-primary-button>
                            </div>
                        </form>

                        <!-- JavaScript to handle Google Maps, autocomplete, and geocoding -->
                         <!-- No idea how to modify this atm -->
                        <script>
                            let map;
                            let marker;
                            let autocomplete;
                            let geocoder;

                            function initAutocomplete() {
                                // Initialize the map centered in Gauteng
                                map = new google.maps.Map(document.getElementById("map"), {
                                    center: { lat: -25.7479, lng: 28.2293 },
                                    zoom: 10,
                                });

                                // Initialize geocoder for address conversion
                                geocoder = new google.maps.Geocoder();

                                // Initialize the marker
                                marker = new google.maps.Marker({
                                    map: map,
                                    draggable: true,
                                });

                                // Set up autocomplete for the location input field
                                autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {
                                    types: ['geocode'], // Suggest only addresses
                                });

                                // When the user selects an address, update the map and hidden fields
                                autocomplete.addListener('place_changed', function () {
                                    const place = autocomplete.getPlace();

                                    if (!place.geometry) {
                                        alert("No details available for the input: '" + place.name + "'");
                                        return;
                                    }

                                    // Update map center and marker position
                                    map.setCenter(place.geometry.location);
                                    marker.setPosition(place.geometry.location);

                                    // Update hidden fields with latitude and longitude
                                    document.getElementById('latitude').value = place.geometry.location.lat();
                                    document.getElementById('longitude').value = place.geometry.location.lng();
                                });

                                // Update latitude and longitude when the marker is dragged
                                google.maps.event.addListener(marker, 'dragend', function () {
                                    document.getElementById('latitude').value = marker.getPosition().lat();
                                    document.getElementById('longitude').value = marker.getPosition().lng();
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
