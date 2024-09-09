@extends('layouts.app')

@section('content')
    <div class="flex justify-center mt-10">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 max-w-lg w-full">
            <h2 class="text-3xl font-bold text-pink-500 mb-6 text-center">Proceed to Payment</h2>

            <form action="https://www.payfast.co.za/eng/process" method="POST">
                @foreach ($paymentData as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <button type="submit" class="block mt-6 bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out w-full text-center">
                    Pay Now
                </button>
            </form>
        </div>
    </div>
@endsection
