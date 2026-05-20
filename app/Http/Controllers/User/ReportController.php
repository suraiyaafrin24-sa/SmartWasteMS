<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('user.report', compact('reports'));
    }

    public function create()
    {
        return view('user.report_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'complained_person' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'complained_person' => $request->complained_person,
            'incident_date' => $request->incident_date,
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'Pending'
        ]);

        return redirect()->route('user.reports.index')->with('success', 'Report submitted successfully. We will look into it soon.');
    }
}
