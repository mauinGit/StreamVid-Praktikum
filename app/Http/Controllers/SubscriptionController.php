<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription;

        $packages = [
            [
                'name' => 'basic',
                'label' => 'Basic',
                'price' => 15000,
                'features' => [
                    'Akses semua film',
                    'Kualitas SD (480p)',
                    '1 perangkat',
                    'Tanpa download',
                ],
            ],
            [
                'name' => 'standard',
                'label' => 'Standard',
                'price' => 29000,
                'features' => [
                    'Akses semua film',
                    'Kualitas HD (1080p)',
                    '2 perangkat',
                    'Download tersedia',
                ],
                'popular' => true,
            ],
            [
                'name' => 'premium',
                'label' => 'Premium',
                'price' => 49000,
                'features' => [
                    'Akses semua film',
                    'Kualitas 4K Ultra HD',
                    '4 perangkat',
                    'Download tersedia',
                    'Audio Dolby Atmos',
                ],
            ],
        ];

        return view('subscription.index', compact('packages', 'activeSubscription'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,standard,premium',
        ]);

        $package = $request->package;
        $price = Subscription::getPackagePrice($package);
        $user = auth()->user();

        return view('payment.checkout', compact('package', 'price', 'user'));
    }
}
