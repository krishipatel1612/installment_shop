<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmiSchedule extends Model
{
    protected $fillable = [
        'order_id',
        'month_no',
        'amount',
        'status',
        'paid_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
