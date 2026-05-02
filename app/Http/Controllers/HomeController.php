<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hero film - random featured film
        $heroFilm = Film::where('is_featured', true)->inRandomOrder()->first();

        // Featured films
        $featuredFilms = Film::where('is_featured', true)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Trending films (by views)
        $trendingFilms = Film::orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // All genres for catalog sections
        $allFilms = Film::all();
        $genres = $allFilms->pluck('genre')->flatten()->unique()->values();

        // Films by genre (random)
        $filmsByGenre = [];
        foreach ($genres->take(4) as $genre) {
            $filmsByGenre[$genre] = Film::whereJsonContains('genre', $genre)
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        return view('home', compact('heroFilm', 'featuredFilms', 'trendingFilms', 'filmsByGenre'));
    }
}
