<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $events = AcademicCalendar::orderBy('event_date')->get();
        $upcomingEvents = AcademicCalendar::upcoming()->orderBy('event_date')->take(5)->get();
        $thisMonthEvents = AcademicCalendar::thisMonth()->orderBy('event_date')->get();

        return view('admin.academic.index', compact('events', 'upcomingEvents', 'thisMonthEvents'));
    }

    public function create()
    {
        return view('admin.academic.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'events' => 'required|array|min:1',
            'events.*.title' => 'required|string|max:255',
            'events.*.description' => 'nullable|string',
            'events.*.event_date' => 'required|date',
            'events.*.type' => 'required|in:holiday,celebration,event,deadline,meeting',
            'events.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'events.*.is_recurring' => 'boolean',
            'events.*.recurrence_type' => 'nullable|required_if:events.*.is_recurring,true|in:yearly,monthly,weekly',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $eventsCreated = 0;

        foreach ($request->events as $eventData) {
            $event = new AcademicCalendar();
            $event->fill($eventData);

            // Handle image upload for this specific event
            if (isset($eventData['image']) && $request->hasFile("events.{$eventsCreated}.image")) {
                $imagePath = $request->file("events.{$eventsCreated}.image")->store('academic-calendar/images', 'public');
                $event->image = $imagePath;
            }

            $event->save();
            $eventsCreated++;
        }

        $message = $eventsCreated === 1
            ? 'Event added to calendar successfully!'
            : "{$eventsCreated} events added to calendar successfully!";

        return redirect()->route('admin.academic-calendar.index')
            ->with('success', $message);
    }

    public function show($id)
    {
        $event = AcademicCalendar::findOrFail($id);
        return view('admin.academic.show', compact('event'));
    }

    public function edit($id)
    {
        $event = AcademicCalendar::findOrFail($id);
        return view('admin.academic.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = AcademicCalendar::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'type' => 'required|in:holiday,celebration,event,deadline,meeting',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|required_if:is_recurring,true|in:yearly,monthly,weekly',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $event->fill($request->except(['image']));

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $imagePath = $request->file('image')->store('academic-calendar/images', 'public');
            $event->image = $imagePath;
        }

        $event->save();

        return redirect()->route('admin.academic-calendar.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = AcademicCalendar::findOrFail($id);

        // Delete image if exists
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.academic-calendar.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $events = AcademicCalendar::whereBetween('event_date', [$start, $end])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->event_date->format('Y-m-d'),
                    'description' => $event->description,
                    'type' => $event->type,
                    'backgroundColor' => $this->getEventColor($event->type),
                    'borderColor' => $this->getEventColor($event->type),
                    'textColor' => '#ffffff',
                ];
            });

        return response()->json($events);
    }

    private function getEventColor($type)
    {
        return match($type) {
            'holiday' => '#dc3545',      // Red
            'celebration' => '#28a745',  // Green
            'event' => '#007bff',        // Blue
            'deadline' => '#ffc107',     // Yellow
            'meeting' => '#17a2b8',      // Teal
            default => '#6c757d',        // Gray
        };
    }
}
