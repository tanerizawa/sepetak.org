<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::whereIn('status', ['planned', 'done'])
            ->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date', 'asc')
            ->paginate(12)
            ->withQueryString();
        if ($upcoming->isEmpty() && $upcoming->currentPage() > 1) {
            return redirect()->to($upcoming->url($upcoming->lastPage()));
        }

        $past = Event::whereIn('status', ['planned', 'done'])
            ->where('event_date', '<', now()->startOfDay())
            ->orderBy('event_date', 'desc')
            ->limit(6)
            ->get();

        return view('events.index', compact('upcoming', 'past'));
    }
}
