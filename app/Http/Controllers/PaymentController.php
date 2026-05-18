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
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user = auth()->user();
        $package = $request->package;
        $price = Subscription::getPackagePrice($package);

        // Create subscription as PENDING (not active yet)
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package' => $package,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'pending',
        ]);

        // Upload payment proof
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Create payment as PENDING
        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'invoice_id' => Payment::generateInvoiceId(),
            'method' => $request->input('method'),
            'amount' => $price,
            'status' => 'pending',
            'payment_proof' => $proofPath,
        ]);

        return redirect()->route('payment.receipt', ['invoice' => $payment->invoice_id]);
    }

    public function receipt(Request $request)
    {
        $payment = Payment::with(['user', 'subscription'])
            ->where('invoice_id', $request->invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('payment.receipt', compact('payment'));
    }

    public function history()
    {
        $payments = Payment::with('subscription')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('payment.history', compact('payments'));
    }
}
