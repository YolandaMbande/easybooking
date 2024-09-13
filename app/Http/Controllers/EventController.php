<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Create event page
    public function create()
    {

        $categories = Category::all();
        return view('events.create_event', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date_time' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'max_attendees' => 'required|integer|min:1',
            'ticket_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:Upcoming,Ongoing,Completed',
            'visibility' => 'required|string|in:Public,Private',
        ]);

        $validatedData['organizer_id'] = auth()->user()->id;

        Event::create($validatedData);

        return redirect()->route('dashboard')->with('success', 'Event created successfully!');
    }

    // Destroy event
    public function destroy(Event $event)
    {

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $event->delete();
        return redirect()->route('dashboard')->with('success', 'Event deleted successfully.');
    }


    public function showEventsWelcomePage() {
        $upcomingEvents = Event::where('status', 'upcoming')->get();
        $completeEvents = Event::where('status', 'complete')->get();
        $ongoingEvents = Event::where('status', 'ongoing')->get();

        //dd($upcomingEvents, $completeEvents, $ongoingEvents);

        return view('welcome', [
            'upcomingEvents' => $upcomingEvents,
            'completeEvents' => $completeEvents,
            'ongoingEvents' => $ongoingEvents,
        ]);
    }

    // Dashboard: Organizer's events
    public function dashboard()
    {
        $events = Event::where('organizer_id', auth()->id())->get();
        return view('dashboard', compact('events'));
    }


    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function showEvents()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }
}


