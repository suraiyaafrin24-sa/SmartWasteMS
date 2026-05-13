# SSLCommerz Payment Integration Blueprint

Use this documentation to implement the SSLCommerz payment gateway in a Laravel project.

## 1. Installation & Setup

Run these commands in the project root:
```bash
composer require dgvai/laravel-sslcommerz
php artisan vendor:publish --provider="DGvai\SSLCommerz\SSLCommerzServiceProvider"
```

## 2. Environment Configuration (`.env`)

Add the following credentials to your `.env` file:
```env
SSLC_STORE_ID=sourc695a2a00a204e
SSLC_STORE_PASSWORD=sourc695a2a00a204e@ssl
SSLC_STORE_CURRENCY=BDT
SSLC_ALLOW_LOCALHOST=true
FRONTEND_URL=http://localhost:3000
```

## 3. Payment Controller (`app/Http/Controllers/SslCommerzPaymentController.php`)

Create this controller to handle the payment lifecycle:

```php
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
            $tran_id = 'SSLC_' . uniqid();

            $sslc = new SSLCommerz();
            $user = auth()->user();

            $sslc->amount($amount)
                ->trxid($tran_id)
                ->product('Service Payment')
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
        $validate = SSLCommerz::validate_payment($request->all());
        $frontendUrl = env('FRONTEND_URL', url('/dashboard'));

        if ($validate) {
            // Update your database here (e.g., Order::where('transaction_id', $request->tran_id)->update(['status' => 'paid']))
            return redirect($frontendUrl . '?payment=success&tran_id=' . $request->tran_id);
        }
        return redirect($frontendUrl . '?payment=failed');
    }

    public function fail(Request $request)
    {
        $frontendUrl = env('FRONTEND_URL', url('/dashboard'));
        return redirect($frontendUrl . '?payment=failed');
    }

    public function cancel(Request $request)
    {
        $frontendUrl = env('FRONTEND_URL', url('/dashboard'));
        return redirect($frontendUrl . '?payment=cancelled');
    }

    public function ipn(Request $request)
    {
        SSLCommerz::validate_payment($request->all());
    }
}
```

## 4. Routing (`routes/web.php`)

Add these routes to handle the payment and callbacks:

```php
use App\Http\Controllers\SslCommerzPaymentController;

Route::middleware(['auth'])->group(function () {
    Route::post('/pay', [SslCommerzPaymentController::class, 'index'])->name('sslc.pay');
});

Route::post('/success', [SslCommerzPaymentController::class, 'success'])->name('sslc.success');
Route::post('/fail', [SslCommerzPaymentController::class, 'fail'])->name('sslc.failure');
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn'])->name('sslc.ipn');
```

## 5. CSRF Exclusion (`bootstrap/app.php` - Laravel 11+)

External POST callbacks from SSLCommerz must bypass CSRF verification:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        '/success',
        '/fail',
        '/cancel',
        '/ipn',
    ]);
})
```

## 6. Frontend Example (Blade)

```html
<form action="{{ route('sslc.pay') }}" method="POST">
    @csrf
    <input type="hidden" name="amount" value="500">
    <button type="submit" class="btn btn-primary">
        Pay Now (৳500)
    </button>
</form>

<!-- Show success/fail alerts using request query parameters -->
@if(request()->query('payment') == 'success')
    <div class="alert alert-success">Payment Successful! ID: {{ request()->query('tran_id') }}</div>
@endif
```
