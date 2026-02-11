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

    /**
     * Calculate total amount with interest
     * Formula: Total = Price × (1 + InterestRate)
     */
    public static function calculateTotalWithInterest($price, $interestRate)
    {
        return (float)$price * (1 + ($interestRate / 100));
    }

    /**
     * Calculate monthly amount from total and months
     * Formula: Monthly = Total / Months
     */
    public static function calculateMonthlyAmount($total, $months)
    {
        if ($months <= 0) return 0;
        return ceil($total / $months);
    }

    /**
     * Get computed total amount for this plan
     */
    public function getComputedTotal()
    {
        $product = $this->product;
        if (!$product) return 0;
        
        return self::calculateTotalWithInterest($product->price, $this->interest_rate ?? 0);
    }

    /**
     * Get computed monthly amount
     */
    public function getComputedMonthly()
    {
        return self::calculateMonthlyAmount($this->getComputedTotal(), $this->months);
    }
}
