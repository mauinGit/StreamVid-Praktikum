<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalSubscribers = Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->distinct('user_id')
            ->count();
        $totalFilms = Film::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        $recentPayments = Payment::with(['user', 'subscription'])
            ->where('status', 'success')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSubscribers',
            'totalFilms',
            'totalRevenue',
            'recentPayments',
            'recentUsers'
        ));
    }
}
