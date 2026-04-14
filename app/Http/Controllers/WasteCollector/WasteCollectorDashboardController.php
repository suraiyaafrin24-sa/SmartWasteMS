<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WasteCollectorDashboardController extends Controller
{
    public function index()
    {
        return view('waste_collector.dashboard');
    }
}
