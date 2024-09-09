<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{

    public function show($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);


        $paymentData = [
        'merchant_id' => env('PAYFAST_MERCHANT_ID'),
        'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
        'amount' => number_format($booking->total_price, 2, '.', ''), // Amount from the booking
        'item_name' => 'Booking for event ' . $booking->event->name,
        'return_url' => route('payment.return'),
        'cancel_url' => route('payment.cancel'),
        'notify_url' => route('payfast.callback'),
        'name_first' => auth()->user()->first_name,
        'name_last' => auth()->user()->last_name,
        'email_address' => auth()->user()->email,
        ];

        // Redirect to a view where the payment form can be submitted to PayFast
        return view('payment', ['paymentData' => $paymentData]);
    }

    // Handle PayFast callback (IPN)
    public function handlePayFastCallback(Request $request)
    {
        if ($request->payment_status == 'COMPLETE') {
        // Handle successful payment (update booking status, send confirmation, etc.)
        return redirect()->route('dashboard')->with('success', 'Payment successful!');
        } else {
        // Handle failed payment
        return redirect()->route('dashboard')->with('error', 'Payment failed!');
        }
    }

    // Optional: Handle return URL after successful payment
    public function paymentReturn()
    {
    // Redirect to a success page or the dashboard
        return redirect()->route('dashboard')->with('success', 'Payment completed successfully!');
    }

    // Optional: Handle cancel URL if the user cancels the payment
    public function paymentCancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment was canceled.');
    }
}
