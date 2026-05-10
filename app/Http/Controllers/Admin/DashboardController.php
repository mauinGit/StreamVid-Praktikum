<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Quick stats for dashboard
        $activeSubscribers = Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->distinct('user_id')
            ->count();

        $pendingPayments = Payment::where('status', 'pending')->count();

        $monthlyRevenue = Payment::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Monthly user growth (last 12 months)
        $monthlyUsers = User::where('role', 'user')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Build 12-month chart data
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthNum = (int)$date->format('n');
            $yearNum = (int)$date->format('Y');
            $chartLabels[] = $date->format('M Y');

            $found = $monthlyUsers->first(fn($item) => $item->month == $monthNum && $item->year == $yearNum);
            $chartData[] = $found ? $found->count : 0;
        }

        // Top 3 trending films (by views)
        $trendingFilms = Film::orderBy('views_count', 'desc')->limit(3)->get();

        // Recent payments (all statuses)
        $recentPayments = Payment::with(['user', 'subscription'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'activeSubscribers',
            'pendingPayments',
            'monthlyRevenue',
            'chartLabels',
            'chartData',
            'trendingFilms',
            'recentPayments',
            'recentUsers'
        ));
    }
}
