<?php 

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function create()
    {
        $categories = Category::all();
        return view('events.create_event', compact('categories'));
    }

    // Explore events (all events)
    public function explore()
    {
        $events = Event::all();
        return view('explore_events', compact('events'));  // Path updated after file move
    }

    // Store event logic
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

    // Destroy event logic
    public function destroy(Event $event)
    {

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        $event->delete();
        return redirect()->route('dashboard')->with('success', 'Event deleted successfully.');
    }

    // Show events on the welcome page
    public function showEventsWelcomePage()
    {
        $upcomingEvents = Event::where('status', 'Upcoming')->get();
        $completeEvents = Event::where('status', 'Completed')->get();
        $ongoingEvents = Event::where('status', 'Ongoing')->get();

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

    // Show a specific event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    // Show all events
    public function showEvents()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    // Search for events - come back to this logic.
    public function search(Request $request)
    {
        $query = Event::query();
    
        if ($request->filled('event_name')) {
            $query->where('name', 'like', '%' . $request->event_name . '%');
        }
    
        if ($request->filled('suburb')) {
            $query->where('location', 'like', '%' . $request->suburb . '%');
        }
    
        if ($request->filled('date')) {
            $query->whereDate('date_time', $request->date);
        }
    
        $events = $query->get();
    
        return view('explore_events', compact('events'));
    }    

}


