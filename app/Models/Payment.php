<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_id',
        'method',
        'amount',
        'status',
        'payment_proof',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public static function generateInvoiceId(): string
    {
        return 'INV-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'transfer_bank' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'qris' => 'QRIS',
            default => $this->method,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'success' => 'Berhasil',
            'failed' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger',
            default => 'info',
        };
    }
}
