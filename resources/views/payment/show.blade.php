<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md space-y-6">
        <h1 class="text-2xl font-bold text-center text-gray-900">Complete Your Payment</h1>
        <p class="text-center text-lg text-gray-700">Booking for: <span class="font-semibold">{{ $booking->event->name }}</span></p>
        <p class="text-center text-lg text-gray-700">Amount: R<span class="font-semibold">{{ $paymentData['amount'] }}</span></p>

        <form action="https://www.payfast.co.za/eng/process" method="post" class="space-y-4">
            @csrf

            <input type="hidden" name="merchant_id" value="{{ $paymentData['merchant_id'] }}">
            <input type="hidden" name="merchant_key" value="{{ $paymentData['merchant_key'] }}">
            <input type="hidden" name="amount" value="{{ $paymentData['amount'] }}">
            <input type="hidden" name="item_name" value="{{ $paymentData['item_name'] }}">
            <input type="hidden" name="return_url" value="{{ $paymentData['return_url'] }}">
            <input type="hidden" name="cancel_url" value="{{ $paymentData['cancel_url'] }}">
            <input type="hidden" name="notify_url" value="{{ $paymentData['notify_url'] }}">
            <input type="hidden" name="name_first" value="{{ $paymentData['name_first'] }}">
            <input type="hidden" name="name_last" value="{{ $paymentData['name_last'] }}">
            <input type="hidden" name="email_address" value="{{ $paymentData['email_address'] }}">

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Pay Now
                </button>
            </div>
        </form>
    </div>
</div>
