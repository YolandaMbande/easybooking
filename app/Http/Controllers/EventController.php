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
        \Log::info('Create method in EventController accessed.');
        $categories = Category::all();
        return view('events.create_event', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'date_time' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'max_attendees' => 'required|integer',
            'ticket_price' => 'required|numeric',
            'status' => 'required|in:Upcoming,Ongoing,Completed',
            'visibility' => 'required|in:Public,Private',
        ]);

        // Create event under the authenticated user
        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'date_time' => $request->date_time,
            'category_id' => $request->category_id,
            'organizer_id' => Auth::id(),
            'max_attendees' => $request->max_attendees,
            'ticket_price' => $request->ticket_price,
            'status' => $request->status,
            'visibility' => $request->visibility,
        ]);


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


