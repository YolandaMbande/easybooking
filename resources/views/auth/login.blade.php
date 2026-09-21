@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-white">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="relative min-h-[500px] lg:min-h-screen overflow-hidden bg-gray-100">

            <!-- Background Video -->
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

            <!-- Optional dark overlay for readability -->
            <div class="absolute inset-0 bg-black/20"></div>

            <!-- Text over video -->
            <div class="relative z-10 flex items-center h-full px-10 lg:px-16">

                <div class="max-w-lg">

                    <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6">
                        Welcome Back
                    </h1>

                    <p class="text-xl lg:text-2xl text-white leading-relaxed">
                        Log in to your account to discover events,
                        manage your tickets, and more.
                    </p>

                </div>

            </div>

        </div>


        <!-- RIGHT SIDE - LOGIN FORM -->
        <div class="flex items-center justify-center px-6 py-12 lg:px-16">

            <div class="w-full max-w-md">

                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />

                <div class="mb-8">

                    <h2 class="text-3xl font-bold text-gray-900">
                        Log in
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Welcome back! Please enter your details.
                    </p>

                </div>


                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    <!-- Email Address -->
                    <div>

                        <x-input-label
                            for="email"
                            :value="__('Email')"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-1 w-full focus:ring-2 focus:ring-bright-pink"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
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
                            class="block mt-1 w-full focus:ring-2 focus:ring-bright-pink"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Remember Me -->
                    <div class="block mt-5">

                        <label
                            for="remember_me"
                            class="inline-flex items-center"
                        >

                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                name="remember"
                            >

                            <span class="ms-2 text-sm text-gray-600">
                                {{ __('Remember me') }}
                            </span>

                        </label>

                    </div>


                    <!-- Forgot Password + Login -->
                    <div class="flex items-center justify-between mt-7">

                        @if (Route::has('password.request'))

                            <a
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('password.request') }}"
                            >
                                {{ __('Forgot your password?') }}
                            </a>

                        @endif

                        <x-primary-button class="ms-3 px-6 py-3">
                            {{ __('Log in') }}
                        </x-primary-button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


<style>
    .focus\:ring-bright-pink:focus {
        ring-color: #FF007F;
    }
</style>