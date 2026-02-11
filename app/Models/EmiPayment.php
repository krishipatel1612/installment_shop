<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'emi_schedule_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function schedule()
    {
        return $this->belongsTo(EmiSchedule::class, 'emi_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
