<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = Event::where('status', 'Upcoming')->where('visibility', 'Public')->get();
        return view('/home', compact('events'));
    }

}
