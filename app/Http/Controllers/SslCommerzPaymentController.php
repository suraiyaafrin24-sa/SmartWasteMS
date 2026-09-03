<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DGvai\SSLCommerz\SSLCommerz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SslCommerzPaymentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $amount = $request->amount ?: 100;
            $request_id = $request->request_id;
            $paymentType = $request->payment_type === 'subscription' ? 'subscription' : 'request';

            if ($paymentType !== 'subscription') {
                return redirect()->route('user.subscription', ['subscription_required' => 1])
                    ->with('error', 'Collection requests are available only with an active subscription package.');
            }

            $subscriptionPackages = [
                1 => 300,
                6 => 1800,
                12 => 3600,
            ];
            $subscriptionMonths = (int) $request->subscription_months;

            if ($paymentType === 'subscription') {
                $user = auth()->user();
                $isActive = $user->monthly_payment_status === 'Paid' && 
                            $user->subscription_valid_until && 
                            \Carbon\Carbon::parse($user->subscription_valid_until)->endOfDay()->isFuture();
                
                if ($isActive) {
                    return redirect()->route('user.subscription')
                        ->with('error', 'You already have an active subscription package. You can buy a new one once it expires.');
                }

                $subscriptionMonths = array_key_exists($subscriptionMonths, $subscriptionPackages) ? $subscriptionMonths : 1;
                $amount = $subscriptionPackages[$subscriptionMonths];
            }

            $tran_id = $paymentType === 'subscription'
                ? 'SUB' . auth()->id() . 'M' . $subscriptionMonths . 'T' . uniqid()
                : 'REQ' . $request_id . 'T' . uniqid();

            $sslc = new SSLCommerz();
            $user = auth()->user();

            $sslc->amount($amount)
                ->trxid($tran_id)
                ->product($paymentType === 'subscription' ? $subscriptionMonths . ' Month SmartBin Subscription' : 'Waste Collection Fee')
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
            $redirectRoute = str_starts_with($tran_id, 'SUB') ? 'user.subscription' : 'user.dashboard';

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

                    // Log the user back in (callback has no session)
                    $reqUser = \App\Models\User::find($collectionRequest->user_id);
                    if ($reqUser) {
                        Auth::login($reqUser);
                    }

                    // Notify Collector if assigned
                    if ($collectionRequest->collector_id) {
                        $collector = \App\Models\User::find($collectionRequest->collector_id);
                        if ($collector) {
                            $collector->notify(new \App\Notifications\CollectionStatusNotification($collectionRequest, 'A new paid collection request has been assigned to you.', 'new_request'));
                        }
                    }
                }
            } elseif (preg_match('/SUB(\d+)M(\d+)T/', $tran_id, $matches)) {
                $user = \App\Models\User::find($matches[1]);
                $subscriptionMonths = in_array((int) $matches[2], [1, 6, 12], true) ? (int) $matches[2] : 1;

                if ($user) {
                    $user->update([
                        'monthly_payment_status' => 'Paid',
                        'subscription_valid_until' => now()->addMonthsNoOverflow($subscriptionMonths),
                        'last_transaction_id' => $tran_id,
                    ]);

                    // Log the user back in (SSLCommerz callback has no session)
                    Auth::login($user);
                }
            }

            return redirect()->route($redirectRoute, [
                'payment' => 'success',
                'tran_id' => $request->tran_id
            ]);
        }
        return redirect()->route('user.dashboard', ['payment' => 'failed']);
    }

    public function fail(Request $request)
    {
        $redirectRoute = str_starts_with($request->tran_id ?? '', 'SUB') ? 'user.subscription' : 'user.dashboard';

        return redirect()->route($redirectRoute, ['payment' => 'failed']);
    }

    public function cancel(Request $request)
    {
        $redirectRoute = str_starts_with($request->tran_id ?? '', 'SUB') ? 'user.subscription' : 'user.dashboard';

        return redirect()->route($redirectRoute, ['payment' => 'cancelled']);
    }

    public function ipn(Request $request)
    {
        SSLCommerz::validate_payment($request);
    }
}
