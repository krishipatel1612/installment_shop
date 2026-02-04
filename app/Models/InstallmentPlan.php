<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    protected $table = 'installment_plans';
protected $fillable = [
    'product_id',
    'months',
    'interest_rate',
    'total_amount',
    'monthly_amount'
];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
