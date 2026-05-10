<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $query = Film::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $films = $query->latest()->paginate(10);
        $featuredFilms = Film::where('is_featured', true)->limit(3)->get();

        return view('admin.films.index', compact('films', 'featuredFilms'));
    }

    public function create()
    {
        return view('admin.films.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|array|min:1',
            'genre.*' => 'string',
            'duration' => 'required|integer|min:1',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'video_file' => 'required|mimes:mp4,webm,ogg|max:102400',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('films/thumbnails', 'public');
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('films/covers', 'public');
        }

        // Handle video upload
        if ($request->hasFile('video_file')) {
            $validated['video_url'] = $request->file('video_file')->store('films/videos', 'public');
        }
        unset($validated['video_file']);

        Film::create($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil ditambahkan!');
    }

    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|array|min:1',
            'genre.*' => 'string',
            'duration' => 'required|integer|min:1',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:102400',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($film->thumbnail && !str_starts_with($film->thumbnail, 'http') && Storage::disk('public')->exists($film->thumbnail)) {
                Storage::disk('public')->delete($film->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('films/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            if ($film->cover && !str_starts_with($film->cover, 'http') && Storage::disk('public')->exists($film->cover)) {
                Storage::disk('public')->delete($film->cover);
            }
            $validated['cover'] = $request->file('cover')->store('films/covers', 'public');
        } else {
            unset($validated['cover']);
        }

        // Handle video upload
        if ($request->hasFile('video_file')) {
            if ($film->video_url && !str_starts_with($film->video_url, 'http') && Storage::disk('public')->exists($film->video_url)) {
                Storage::disk('public')->delete($film->video_url);
            }
            $validated['video_url'] = $request->file('video_file')->store('films/videos', 'public');
        }
        unset($validated['video_file']);

        $film->update($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil diperbarui!');
    }

    public function destroy(Film $film)
    {
        // Delete uploaded files
        foreach (['thumbnail', 'cover', 'video_url'] as $field) {
            if ($film->$field && !str_starts_with($film->$field, 'http') && Storage::disk('public')->exists($film->$field)) {
                Storage::disk('public')->delete($film->$field);
            }
        }

        $film->delete();

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil dihapus!');
    }

    public function toggleFeatured(Film $film)
    {
        // If turning ON featured, check max 3
        if (!$film->is_featured) {
            $featuredCount = Film::where('is_featured', true)->count();
            if ($featuredCount >= 3) {
                return back()->with('success', 'Maksimal 3 film featured. Hapus salah satu terlebih dahulu.');
            }
        }

        $film->update(['is_featured' => !$film->is_featured]);

        return back()->with('success', $film->is_featured ? 'Film ditambahkan ke Hero Carousel.' : 'Film dihapus dari Hero Carousel.');
    }

    public function searchJson(Request $request)
    {
        $search = $request->input('q', '');
        $films = Film::where('title', 'like', '%' . $search . '%')
            ->where('is_featured', false)
            ->select('id', 'title', 'thumbnail', 'release_year')
            ->limit(10)
            ->get()
            ->map(function ($film) {
                $film->thumbnail_url = $film->thumbnail
                    ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail))
                    : 'https://picsum.photos/seed/'.$film->id.'/50/75';
                return $film;
            });

        return response()->json($films);
    }
}
