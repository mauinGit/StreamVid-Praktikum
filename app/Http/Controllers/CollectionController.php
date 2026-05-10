<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Continue watching (from history, latest first, unique per film)
        $histories = $user->watchHistories()
            ->with('film')
            ->latest('watched_at')
            ->get()
            ->unique('film_id')
            ->take(12)
            ->values();

        // Favorite films (from my list)
        $myList = $user->myLists()
            ->with('film')
            ->latest()
            ->get();

        // Recommendations (random films)
        $recommendations = \App\Models\Film::inRandomOrder()->limit(12)->get();

        return view('collection.index', compact('histories', 'myList', 'recommendations'));
    }
}
