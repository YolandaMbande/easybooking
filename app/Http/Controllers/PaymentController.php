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
            'merchant_id' => env('MERCHANT_ID'),
            'merchant_key' => env('MERCHANT_KEY'),
            'amount' => number_format($booking->total_price, 2, '.', ''),
            'item_name' => 'Booking for event ' . $booking->event->name,
            'return_url' => route('payment.return'),
            'cancel_url' => route('payment.cancel'),
            'notify_url' => route('payfast.callback'),
            'name_first' => auth()->user()->first_name,
            'name_last' => auth()->user()->last_name,
            'email_address' => auth()->user()->email,
        ];

    
        return view('payment.show', ['paymentData' => $paymentData, 'booking' => $booking]);
    }

    public function processPayment(Request $request)
    {
        
        $paymentData = [
            'merchant_id' => env('MERCHANT_ID'),
            'merchant_key' => env('MERCHANT_KEY'),
            'amount' => $request->amount,
            'item_name' => $request->item_name,
            'return_url' => route('payment.return'),
            'cancel_url' => route('payment.cancel'),
            'notify_url' => route('payfast.callback'),
            'name_first' => $request->name_first,
            'name_last' => $request->name_last,
            'email_address' => $request->email_address,
        ];

        return view('payment.process', ['paymentData' => $paymentData]);
    }

    public function handlePayFastCallback(Request $request)
    {
        if ($request->payment_status == 'COMPLETE') {
            return redirect()->route('payment.success')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('dashboard')->with('error', 'Payment failed!');
        }
    }

    public function paymentReturn()
    {
        return redirect()->route('welcome')->with('success', 'Payment completed successfully!');
    }

    public function paymentCancel($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('payment.cancel', compact('booking'));
    }

}
