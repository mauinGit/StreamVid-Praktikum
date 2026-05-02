<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function getPackageLabelAttribute(): string
    {
        return ucfirst($this->package);
    }

    public static function getPackagePrice(string $package): int
    {
        return match($package) {
            'basic' => 15000,
            'standard' => 29000,
            'premium' => 49000,
            default => 0,
        };
    }

    public static function getPackageDuration(string $package): int
    {
        return 30; // 30 days for all packages
    }
}
