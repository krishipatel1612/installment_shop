<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'emi_schedule_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'submitted_at',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Status: pending / verified / rejected

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function emiSchedule()
    {
        return $this->belongsTo(EmiSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Get pending verifications count
    public static function getPendingCount()
    {
        return self::where('status', 'pending')->count();
    }
}
