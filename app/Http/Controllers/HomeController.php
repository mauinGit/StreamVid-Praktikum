<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Hero films - 3 random featured films untuk carousel
        $heroFilms = Film::where('is_featured', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Fallback: kalau featured film kurang dari 3, ambil dari semua film
        if ($heroFilms->count() < 3) {
            $heroFilms = Film::inRandomOrder()->limit(3)->get();
        }

        // Movie Terbaru (newest by created_at)
        $newestFilms = Film::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Trending Saat Ini (by views_count)
        $trendingFilms = Film::orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // Semua Film (paginated)
        $allFilms = Film::orderBy('created_at', 'desc')->paginate(18);

        return view('home', compact('heroFilms', 'newestFilms', 'trendingFilms', 'allFilms'));
    }
}