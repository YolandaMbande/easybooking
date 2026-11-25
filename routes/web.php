<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/events', [EventController::class,'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class,'show'])->name('events.show');
Route::view('/about','pages.about')->name('about');
Route::view('/organisers','pages.organisers')->name('organisers');
