<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WasteCollectorDutyScheduleController extends Controller
{
    public function dutySchedule()
    {
        $collectorId = Auth::id();
        $schedule = \App\Models\DutyRoster::where('collector_id', $collectorId)
            ->orderBy('date', 'desc')
            ->orderBy('shift', 'asc')
            ->get();
            
        return view('waste_collector.duty_schedule', compact('schedule'));
    }
}
