<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'duration',
        'release_year',
        'thumbnail',
        'cover',
        'video_url',
        'views_count',
        'is_featured',
    ];

    protected $casts = [
        'genre' => 'array',
        'is_featured' => 'boolean',
    ];

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'my_lists');
    }

    public function getGenreListAttribute(): string
    {
        return is_array($this->genre) ? implode(', ', $this->genre) : $this->genre;
    }

    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        return "{$minutes}m";
    }
}
