<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'price',
        'months',
        'monthly_amount',
    ];

    public function emiSchedules()
    {
        return $this->hasMany(EmiSchedule::class);
    }

    // Total paid
    public function totalPaid()
    {
        return $this->emiSchedules()->where('status', 'Paid')->sum('amount');
    }

    // Total remaining
    public function totalPending()
    {
        return $this->emiSchedules()->where('status', 'Pending')->sum('amount');
    }
}
