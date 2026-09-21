@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-white">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="relative min-h-[500px] lg:min-h-screen overflow-hidden bg-gray-100">

            <video
                autoplay
                muted
                loop
                playsinline
                class="absolute inset-0 w-full h-full object-cover"
            >
                <source
                    src="{{ asset('images/register_form.mp4') }}"
                    type="video/mp4"
                >
            </video>

            <!-- Text over image -->
            <div class="relative z-10 flex items-center h-full px-10 lg:px-16">

                <div class="max-w-lg">

                    <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6">
                        Create Account
                    </h1>

                    <p class="text-xl lg:text-2xl text-white leading-relaxed">
                        Create a new account to start booking events,
                        managing your tickets, and more.
                    </p>

                </div>

            </div>

        </div>


        <!-- RIGHT SIDE - FORM -->
        <div class="flex items-center justify-center px-6 py-12 lg:px-16">

            <div class="w-full max-w-md">

                <div class="mb-8">

                    <h2 class="text-3xl font-bold text-gray-900">
                        Sign up
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Create your account and start exploring events.
                    </p>

                </div>


                <form method="POST" action="{{ route('register') }}">

                    @csrf


                    <!-- Name -->
                    <div>
                        <x-input-label
                            for="name"
                            :value="__('Name')"
                        />

                        <x-text-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <x-input-error
                            :messages="$errors->get('name')"
                            class="mt-2"
                        />
                    </div>


                    <!-- Email -->
                    <div class="mt-5">

                        <x-input-label
                            for="email"
                            :value="__('Email')"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autocomplete="username"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Password -->
                    <div class="mt-5">

                        <x-input-label
                            for="password"
                            :value="__('Password')"
                        />

                        <x-text-input
                            id="password"
                            class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                        />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Confirm Password -->
                    <div class="mt-5">

                        <x-input-label
                            for="password_confirmation"
                            :value="__('Confirm Password')"
                        />

                        <x-text-input
                            id="password_confirmation"
                            class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        />

                        <x-input-error
                            :messages="$errors->get('password_confirmation')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Role -->
                    <div class="mt-5">

                        <x-input-label
                            for="role"
                            :value="__('Role')"
                        />

                        <select
                            id="role"
                            name="role"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                            <option value="organizer">
                                {{ __('Organizer') }}
                            </option>

                            <option value="user">
                                {{ __('Attendee') }}
                            </option>
                        </select>

                        <x-input-error
                            :messages="$errors->get('role')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Submit -->
                    <div class="mt-7">

                        <x-primary-button class="w-full justify-center py-3">
                            {{ __('Register') }}
                        </x-primary-button>

                    </div>


                    <!-- Login -->
                    <div class="text-center mt-6">

                        <span class="text-sm text-gray-500">
                            Already have an account?
                        </span>

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-900 hover:underline ml-1"
                        >
                            Log in
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
