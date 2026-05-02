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
    public function index()
    {
        return view('admin.reports.index');
    }

    public function export(string $type)
    {
        switch ($type) {
            case 'users':
                $data = User::where('role', 'user')->with('activeSubscription')->get();
                $pdf = Pdf::loadView('admin.reports.users-pdf', compact('data'));
                return $pdf->download('laporan-users-' . date('Y-m-d') . '.pdf');

            case 'films':
                $data = Film::orderBy('views_count', 'desc')->get();
                $pdf = Pdf::loadView('admin.reports.films-pdf', compact('data'));
                return $pdf->download('laporan-films-' . date('Y-m-d') . '.pdf');

            case 'subscriptions':
                $data = Subscription::with(['user', 'payment'])->latest()->get();
                $pdf = Pdf::loadView('admin.reports.subscriptions-pdf', compact('data'));
                return $pdf->download('laporan-subscriptions-' . date('Y-m-d') . '.pdf');

            default:
                abort(404);
        }
    }
}
