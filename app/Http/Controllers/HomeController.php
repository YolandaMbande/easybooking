<?php
namespace App\Http\Controllers;
use App\Models\Event;
class HomeController extends Controller {
    public function index() {
        $featuredEvents = Event::where('status','published')->where('featured',true)->with('ticketTypes')->orderBy('starts_at')->take(3)->get();
        $upcomingEvents = Event::where('status','published')->with('ticketTypes')->orderBy('starts_at')->take(8)->get();
        return view('home', compact('featuredEvents','upcomingEvents'));
    }
}
