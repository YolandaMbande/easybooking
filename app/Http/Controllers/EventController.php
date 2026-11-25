<?php
namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;
class EventController extends Controller {
    public function index(Request $request) {
        $events = Event::where('status','published')->with('ticketTypes')
            ->when($request->filled('q'), fn($q) => $q->where(fn($s) => $s->where('title','like','%'.$request->q.'%')->orWhere('city','like','%'.$request->q.'%')->orWhere('category','like','%'.$request->q.'%')))
            ->when($request->filled('category'), fn($q) => $q->where('category',$request->category))
            ->orderBy('starts_at')->paginate(12)->withQueryString();
        $categories = Event::where('status','published')->select('category')->distinct()->pluck('category')->filter();
        return view('events.index', compact('events','categories'));
    }
    public function show(Event $event) {
        abort_unless($event->status === 'published',404);
        $event->load('ticketTypes');
        return view('events.show', compact('event'));
    }
}
