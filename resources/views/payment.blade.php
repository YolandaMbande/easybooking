@extends('layouts.app')

@section('content')
    <div class="flex justify-center mt-10">
        <div class="bg-gray-100 dark:bg-gray-700 shadow-lg rounded-lg p-8 max-w-lg w-full"> <!-- Changed background color -->
            <h2 class="text-3xl font-bold text-pink-500 mb-6 text-center">Proceed to Payment</h2>

            <form action="https://www.payfast.co.za/eng/process" method="POST">
                @foreach ($paymentData as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <button type="submit" class="block mt-6 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out w-full text-center">
                    Pay Now
                </button>
            </form>
        </div>
    </div>
@endsection
