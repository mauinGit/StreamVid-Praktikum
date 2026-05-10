<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Continue watching (from history, latest first)
        $histories = $user->watchHistories()
            ->with('film')
            ->latest('watched_at')
            ->limit(12)
            ->get();

        // Favorite films (from my list)
        $myList = $user->myLists()
            ->with('film')
            ->latest()
            ->get();

        return view('collection.index', compact('histories', 'myList'));
    }
}
