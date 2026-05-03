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

        return view('admin.films.index', compact('films'));
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
            'video_url' => 'required|string',
            'is_featured' => 'boolean',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('films/thumbnails', 'public');
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('films/covers', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

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
            'video_url' => 'required|string',
            'is_featured' => 'boolean',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($film->thumbnail && Storage::disk('public')->exists($film->thumbnail)) {
                Storage::disk('public')->delete($film->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('films/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($film->cover && Storage::disk('public')->exists($film->cover)) {
                Storage::disk('public')->delete($film->cover);
            }
            $validated['cover'] = $request->file('cover')->store('films/covers', 'public');
        } else {
            unset($validated['cover']);
        }

        $validated['is_featured'] = $request->has('is_featured');

        $film->update($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil diperbarui!');
    }

    public function destroy(Film $film)
    {
        // Delete files
        if ($film->thumbnail && Storage::disk('public')->exists($film->thumbnail)) {
            Storage::disk('public')->delete($film->thumbnail);
        }
        if ($film->cover && Storage::disk('public')->exists($film->cover)) {
            Storage::disk('public')->delete($film->cover);
        }

        $film->delete();

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil dihapus!');
    }
}
