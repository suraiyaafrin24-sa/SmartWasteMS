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

        $totalRequests = CollectionRequest::where('user_id', $user->id)
            ->whereNotIn('status', ['Cancelled', 'Awaiting Payment'])
            ->count();
            
        $pendingPickups = CollectionRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Scheduled'])
            ->count();
            
        $completedRequestsCount = CollectionRequest::where('user_id', $user->id)
            ->whereIn('status', ['Completed', 'Collected'])
            ->count();
            
        $ecoContribution = CollectionRequest::where('user_id', $user->id)
            ->whereIn('status', ['Completed', 'Collected'])
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
}
