<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use App\Models\CollectionRequest;
use Illuminate\Support\Facades\Auth;

class WasteCollectorPaymentController extends Controller
{
    public function paymentCollection()
    {
        $collectorId = Auth::id();
        $payments = CollectionRequest::where('collector_id', $collectorId)
            ->where('payment_status', 'Paid')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('waste_collector.payment_collection', compact('payments'));
    }
}
