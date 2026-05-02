<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function play(Film $film)
    {
        $user = auth()->user();

        // Record watch history
        WatchHistory::create([
            'user_id' => $user->id,
            'film_id' => $film->id,
            'watched_at' => now(),
        ]);

        // Increment view count
        $film->increment('views_count');

        return view('films.watch', compact('film'));
    }
}
