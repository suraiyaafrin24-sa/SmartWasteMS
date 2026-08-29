<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{
    public function subscription()
    {
        $user = Auth::user();
        $validUntil = $user->subscription_valid_until
            ? \Carbon\Carbon::parse($user->subscription_valid_until)
            : null;

        $isActive = $this->hasActiveSubscription($user);

        return view('user.subscription', compact('user', 'validUntil', 'isActive'));
    }

    public function mySubscription()
    {
        $user = Auth::user();
        $validUntil = $user->subscription_valid_until
            ? \Carbon\Carbon::parse($user->subscription_valid_until)
            : null;

        $isActive = $this->hasActiveSubscription($user);

        // Detect plan details from last transaction ID
        $months = null;
        if ($user->last_transaction_id && preg_match('/SUB\d+M(\d+)T/', $user->last_transaction_id, $matches)) {
            $months = (int) $matches[1];
        }

        $planName = 'N/A';
        $price = 0;
        if ($isActive) {
            if ($months === 1) {
                $planName = 'Monthly Plan';
                $price = 300;
            } elseif ($months === 6) {
                $planName = '6 Months Plan';
                $price = 1800;
            } elseif ($months === 12) {
                $planName = '1 Year Plan';
                $price = 3600;
            } else {
                $planName = 'SmartBin Standard Plan';
                $price = 300;
            }
        }

        return view('user.my_subscription', compact('user', 'validUntil', 'isActive', 'planName', 'price'));
    }

    public function subscriptionReceipt()
    {
        $user = Auth::user();
        if (!$user->last_transaction_id) {
            return redirect()->back()->with('error', 'No subscription record found.');
        }

        $validUntil = $user->subscription_valid_until
            ? \Carbon\Carbon::parse($user->subscription_valid_until)
            : null;

        $isActive = $this->hasActiveSubscription($user);

        // Detect plan details from last transaction ID
        $months = null;
        if ($user->last_transaction_id && preg_match('/SUB\d+M(\d+)T/', $user->last_transaction_id, $matches)) {
            $months = (int) $matches[1];
        }

        $planName = 'N/A';
        $price = 0;
        if ($months === 1) {
            $planName = 'Monthly Plan';
            $price = 300;
        } elseif ($months === 6) {
            $planName = '6 Months Plan';
            $price = 1800;
        } elseif ($months === 12) {
            $planName = '1 Year Plan';
            $price = 3600;
        } else {
            $planName = 'SmartBin Standard Plan';
            $price = 300;
        }

        return view('user.subscription_receipt', compact('user', 'validUntil', 'isActive', 'planName', 'price', 'months'));
    }

    private function hasActiveSubscription($user): bool
    {
        if (! $user || $user->monthly_payment_status !== 'Paid' || ! $user->subscription_valid_until) {
            return false;
        }

        return \Carbon\Carbon::parse($user->subscription_valid_until)->endOfDay()->isFuture();
    }
}
