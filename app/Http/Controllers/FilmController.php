<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $query = Film::query();

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->whereJsonContains('genre', $request->genre);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        $films = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get all unique genres for filter
        $allGenres = Film::all()->pluck('genre')->flatten()->unique()->sort()->values();
        $years = Film::select('release_year')->distinct()->orderBy('release_year', 'desc')->pluck('release_year');

        return view('films.index', compact('films', 'allGenres', 'years'));
    }

    public function show(Film $film)
    {
        // Increment views
        $film->increment('views_count');

        // Get recommendations based on genre
        $recommendations = Film::where('id', '!=', $film->id)
            ->where(function ($query) use ($film) {
                foreach ($film->genre as $genre) {
                    $query->orWhereJsonContains('genre', $genre);
                }
            })
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // If not enough genre-based recommendations, fill with popular
        if ($recommendations->count() < 5) {
            $moreFilms = Film::where('id', '!=', $film->id)
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->orderBy('views_count', 'desc')
                ->limit(5 - $recommendations->count())
                ->get();
            $recommendations = $recommendations->merge($moreFilms);
        }

        // Check if user has this in their list
        $inMyList = false;
        if (auth()->check()) {
            $inMyList = auth()->user()->myLists()->where('film_id', $film->id)->exists();
        }

        return view('films.show', compact('film', 'recommendations', 'inMyList'));
    }
}
