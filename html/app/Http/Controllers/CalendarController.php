<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IcsCalendar;
use App\Models\IcsEvent;
use App\Providers\GcalendarServiceProvider as GCAL;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $check_data = IcsCalendar::first();
        if (is_null($check_data)) {
            $cal = new GCAL;
            $cal->list_calendars();
        }
    }

    public function calendar(Request $request)
    {
 
        $today = $request->input('current');
        if (!$today) $today = date('Y-m-d');
        $seme = GCAL::current_seme();
        $create = false;
        if ($request->user()) {
            $create = $request->user()->can('create', IcsEvent::class);
            if ($request->user()->user_type == 'Student') {
                $events = IcsEvent::inTimeForStudent($today);
            } else {
                $events = IcsEvent::inTime($today);
            }
        } else {
            $events = IcsEvent::inTimeForStudent($today);
        }
        $edit = [];
        $delete = [];
        foreach ($events as $event) {
            $edit[$event->id] = $request->user()->can('update', $event);
            $delete[$event->id] = $request->user()->can('delete', $event);
        }
        return view('app.calendar', ['create' => $create, 'current' => $today, 'seme' => $seme, 'events' => $events, 'editable' => $edit, 'deleteable' => $delete]);
    }

}
