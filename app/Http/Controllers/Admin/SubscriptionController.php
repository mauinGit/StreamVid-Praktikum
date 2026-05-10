<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Auto-expire subscriptions
        Subscription::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'expired']);

        $query = Payment::with(['user', 'subscription']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.subscriptions.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->update([
            'status' => 'success',
            'admin_notes' => 'Pembayaran disetujui pada ' . now()->format('d M Y H:i'),
        ]);

        // Activate subscription
        if ($payment->subscription) {
            $payment->subscription->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ]);

            // Expire other active subscriptions for this user
            Subscription::where('user_id', $payment->user_id)
                ->where('id', '!=', $payment->subscription_id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Pembayaran berhasil disetujui. Langganan user telah diaktifkan.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'failed',
            'admin_notes' => $request->input('reason', 'Pembayaran ditolak oleh admin.'),
        ]);

        if ($payment->subscription) {
            $payment->subscription->update(['status' => 'cancelled']);
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Pembayaran ditolak.');
    }
}
