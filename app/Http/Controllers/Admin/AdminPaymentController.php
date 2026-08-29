<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminPaymentController extends Controller
{
    public function payments(Request $request)
    {
        $query = User::whereNotNull('last_transaction_id');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('last_transaction_id', 'LIKE', "%{$search}%")
                    ->orWhere('monthly_payment_status', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('sector', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $subscriptions = $query->orderBy('updated_at', 'desc')->paginate(15);

        $totalSubscriptionRevenue = User::whereNotNull('last_transaction_id')
            ->get()
            ->sum(function ($user) {
                if (preg_match('/M(\d+)T/', $user->last_transaction_id, $matches)) {
                    return match ((int) $matches[1]) {
                        6 => 1800,
                        12 => 3600,
                        default => 300,
                    };
                }

                return 300;
            });

        return view('admin.payments', compact('subscriptions', 'totalSubscriptionRevenue'));
    }
}
