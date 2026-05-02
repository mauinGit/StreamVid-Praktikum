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
}
