<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalRequests = CollectionRequest::where('user_id', $user->id)->count();
        $pendingPickups = CollectionRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Scheduled'])
            ->count();
            
        $completedRequestsCount = CollectionRequest::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->count();
            
        $ecoContribution = CollectionRequest::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->sum('weight');

        $recentRequests = CollectionRequest::where('user_id', $user->id)
            ->with('collector')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalRequests', 
            'pendingPickups', 
            'completedRequestsCount', 
            'ecoContribution', 
            'recentRequests'
        ));
    }

    public function createRequest()
    {
        return view('user.create_request');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'nullable|date_format:H:i',
            'details' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'sector' => 'required|string|max:50',
            'road' => 'required|string|max:100',
        ]);

        CollectionRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'details' => $request->details,
            'address' => $request->address ?? Auth::user()->address,
            'phone' => $request->phone ?? Auth::user()->phone,
            'sector' => $request->sector,
            'road' => $request->road,
            'zone' => 'Uttara City Corporation Zone',
            'status' => 'Pending'
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Collection request created successfully!');
    }

    public function history()
    {
        $requests = CollectionRequest::where('user_id', Auth::id())
            ->with('collector')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.history', compact('requests'));
    }
}
