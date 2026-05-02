<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function index()
    {
        $histories = auth()->user()
            ->watchHistories()
            ->with('film')
            ->latest('watched_at')
            ->paginate(20);

        return view('history.index', compact('histories'));
    }
}
