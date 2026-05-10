<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base queries
        $userQuery = User::where('role', 'user');
        $filmQuery = Film::query();
        $subscriptionQuery = Subscription::query();
        $paymentQuery = Payment::where('status', 'success');
        $transactionQuery = Payment::with(['user', 'subscription']);

        // Apply date filter
        if ($startDate && $endDate) {
            $userQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            $filmQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            $subscriptionQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            $paymentQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            $transactionQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $totalUsers = $userQuery->count();
        $totalFilms = $filmQuery->count();
        $totalSubscribers = (clone $subscriptionQuery)->where('status', 'active')->where('end_date', '>=', now())->distinct('user_id')->count();
        $totalRevenue = $paymentQuery->sum('amount');
        $totalTransactions = (clone $transactionQuery)->count();

        $transactions = $transactionQuery->latest()->paginate(15);

        return view('admin.reports.index', compact(
            'totalUsers', 'totalFilms', 'totalSubscribers', 'totalRevenue', 'totalTransactions',
            'transactions', 'startDate', 'endDate'
        ));
    }

    public function export(Request $request, string $type)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        switch ($type) {
            case 'users':
                $query = User::where('role', 'user')->with('activeSubscription');
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
                }
                $data = $query->get();
                $pdf = Pdf::loadView('admin.reports.users-pdf', compact('data', 'startDate', 'endDate'));
                return $pdf->download('laporan-users-' . date('Y-m-d') . '.pdf');

            case 'films':
                $query = Film::orderBy('views_count', 'desc');
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
                }
                $data = $query->get();
                $pdf = Pdf::loadView('admin.reports.films-pdf', compact('data', 'startDate', 'endDate'));
                return $pdf->download('laporan-films-' . date('Y-m-d') . '.pdf');

            case 'subscriptions':
                $query = Subscription::with(['user', 'payment'])->latest();
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
                }
                $data = $query->get();
                $pdf = Pdf::loadView('admin.reports.subscriptions-pdf', compact('data', 'startDate', 'endDate'));
                return $pdf->download('laporan-subscriptions-' . date('Y-m-d') . '.pdf');

            default:
                abort(404);
        }
    }
}
