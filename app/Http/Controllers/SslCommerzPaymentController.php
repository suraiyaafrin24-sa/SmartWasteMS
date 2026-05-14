<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DGvai\SSLCommerz\SSLCommerz;
use Illuminate\Support\Facades\Log;

class SslCommerzPaymentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $amount = $request->amount ?: 100;
            $request_id = $request->request_id;
            $tran_id = 'REQ' . $request_id . 'T' . uniqid();

            $sslc = new SSLCommerz();
            $user = auth()->user();

            $sslc->amount($amount)
                ->trxid($tran_id)
                ->product('Waste Collection Fee')
                ->customer(
                    $user->name ?? 'Guest', 
                    $user->email ?? 'no-email@example.com', 
                    $user->address ?? 'Dhaka', 
                    $user->phone ?? '01700000000'
                );

            $paymentResponse = $sslc->make_payment();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['data' => $paymentResponse->getTargetUrl()]);
            }

            return $paymentResponse;
        } catch (\Exception $e) {
            Log::error('SSLCommerz Error: ' . $e->getMessage());
            return back()->with('error', 'Payment Initiation Failed');
        }
    }

    public function success(Request $request)
    {
        $validate = SSLCommerz::validate_payment($request);

        if ($validate) {
            $tran_id = $request->tran_id;
            // Parse request_id from REQ{id}T{uniqid}
            if (preg_match('/REQ(\d+)T/', $tran_id, $matches)) {
                $requestId = $matches[1];
                $collectionRequest = \App\Models\CollectionRequest::find($requestId);
                if ($collectionRequest) {
                    $newStatus = $collectionRequest->collector_id ? 'Scheduled' : 'Pending';
                    
                    $collectionRequest->update([
                        'payment_status' => 'Paid',
                        'status' => $newStatus,
                        'transaction_id' => $tran_id
                    ]);

                    // Notify Collector if assigned
                    if ($collectionRequest->collector_id) {
                        $collector = \App\Models\User::find($collectionRequest->collector_id);
                        if ($collector) {
                            $collector->notify(new \App\Notifications\CollectionStatusNotification($collectionRequest, 'A new paid collection request has been assigned to you.', 'new_request'));
                        }
                    }
                }
            }

            return redirect()->route('user.dashboard', [
                'payment' => 'success',
                'tran_id' => $request->tran_id
            ]);
        }
        return redirect()->route('user.dashboard', ['payment' => 'failed']);
    }

    public function fail(Request $request)
    {
        return redirect()->route('user.dashboard', ['payment' => 'failed']);
    }

    public function cancel(Request $request)
    {
        return redirect()->route('user.dashboard', ['payment' => 'cancelled']);
    }

    public function ipn(Request $request)
    {
        SSLCommerz::validate_payment($request);
    }
}
