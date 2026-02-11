<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmiSchedule;
use App\Models\EmiPayment;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'product_id',
        'price',
        'months',
        'monthly_amount',
        'status',
        
    ];

    

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
    public function emiSchedules()
{
    return $this->hasMany(EmiSchedule::class);
}

public function emiPayments()
{
    return $this->hasMany(EmiPayment::class);
}
public function product()
{
    return $this->belongsTo(Product::class);
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

}