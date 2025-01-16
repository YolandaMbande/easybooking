<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Web routes file
Route::get('/', [EventController::class, 'showEventsWelcomePage'])->name('welcome');

// Public routes
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Public event viewing routes (accessible without authentication)
Route::view('/explore_events', 'explore_events')->name('explore_events');
Route::get('/events', [EventController::class, 'showEvents'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/explore', [EventController::class, 'search'])->name('events.search');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Authentication routes
Auth::routes(['verify' => true]); // Enable verification

Route::middleware(['auth', 'verified'])->group(function () { 
    Route::get('/profile', function () {
        if (auth()->user()->hasVerifiedEmail()) {
            return view('profile.edit');
        } else {
            return view('auth.verify-email-notice');
        }
    })->middleware('auth')->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard (for logged-in users)
Route::get('/dashboard', [EventController::class, 'dashboard'])->middleware('auth')->name('dashboard');

//Creating an event
Route::prefix('organiser')->name('organiser.')->group(function () {
    Route::get('create', [EventController::class, 'create'])->name('create_event');
    Route::post('store', [EventController::class, 'store'])->name('store');
});

// Event booking routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});


// Payments route
Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::post('/pay/callback', [PaymentController::class, 'handlePayFastCallback'])->name('payfast.callback');
Route::get('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');
Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

// Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Email verification routes (no need for a custom controller)
Route::middleware('auth')->group(function() {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('throttle:6,1')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});
