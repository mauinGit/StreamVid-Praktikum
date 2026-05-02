<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,standard,premium',
            'method' => 'required|in:transfer_bank,e_wallet,qris',
        ]);

        $user = auth()->user();
        $package = $request->package;
        $price = Subscription::getPackagePrice($package);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package' => $package,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        // Create payment
        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'invoice_id' => Payment::generateInvoiceId(),
            'method' => $request->method,
            'amount' => $price,
            'status' => 'success',
        ]);

        // Expire any other active subscriptions
        Subscription::where('user_id', $user->id)
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        return redirect()->route('payment.success', ['invoice' => $payment->invoice_id]);
    }

    public function success(Request $request)
    {
        $payment = Payment::where('invoice_id', $request->invoice)->firstOrFail();
        $subscription = $payment->subscription;

        return view('payment.success', compact('payment', 'subscription'));
    }
}
