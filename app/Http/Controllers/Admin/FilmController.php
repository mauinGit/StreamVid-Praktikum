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
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:102400',
            'video_url' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        // Must have either video file or video URL
        if (!$request->hasFile('video_file') && !$request->filled('video_url')) {
            return back()->withErrors(['video_url' => 'Upload file video atau masukkan URL video.'])->withInput();
        }

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
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:102400',
            'video_url' => 'nullable|string',
            'is_featured' => 'boolean',
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
            // Delete old video if it was uploaded
            if ($film->video_url && !str_starts_with($film->video_url, 'http') && Storage::disk('public')->exists($film->video_url)) {
                Storage::disk('public')->delete($film->video_url);
            }
            $validated['video_url'] = $request->file('video_file')->store('films/videos', 'public');
        } elseif (!$request->filled('video_url')) {
            unset($validated['video_url']);
        }
        unset($validated['video_file']);

        $validated['is_featured'] = $request->has('is_featured');

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
}
