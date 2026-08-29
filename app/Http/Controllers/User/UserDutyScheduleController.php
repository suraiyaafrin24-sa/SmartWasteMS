<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDutyScheduleController extends Controller
{
    public function dutySchedule(Request $request)
    {
        $query = \App\Models\DutyRoster::with('collector')
            ->where('sector', Auth::user()->sector);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sector', 'LIKE', "%{$search}%")
                  ->orWhereHas('collector', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $dutyRosters = $query->orderBy('date', 'desc')->paginate(15);
        $sectors = \App\Models\DutyRoster::select('sector')->distinct()->pluck('sector');

        return view('user.duty_schedule', compact('dutyRosters', 'sectors'));
    }
}
