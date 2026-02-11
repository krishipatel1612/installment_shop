<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmiSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'installment_plan_id',
        'month_no',
        'amount',
        'due_date',
        'status',
        'penalty_amount',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(EmiPayment::class, 'emi_schedule_id');
    }

    public function paymentVerifications()
    {
        return $this->hasMany(\App\Models\PaymentVerification::class, 'emi_schedule_id');
    }

    // Check if overdue
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'Paid') return false;
        if (!$this->due_date) return false;

        return Carbon::parse($this->due_date)->isPast();
    }

    // Get days overdue
    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return Carbon::parse($this->due_date)->diffInDays(now());
    }

    // Total amount due (EMI + Penalty)
    public function getTotalDueAttribute()
    {
        return (float)$this->amount + (float)$this->penalty_amount;
    }

    // Calculate penalty based on days overdue
    public static function calculatePenalty($amount, $daysOverdue)
    {
        // 2% penalty per month or part thereof
        $monthsOverdue = ceil($daysOverdue / 30);
        return (float)$amount * 0.02 * $monthsOverdue;
    }

    // Mark as overdue and apply penalty
    public function markAsOverdue()
    {
        if ($this->is_overdue && $this->status === 'Pending') {
            $daysOverdue = $this->days_overdue;
            $newPenalty = self::calculatePenalty($this->amount, $daysOverdue);
            
            $this->status = 'Overdue';
            $this->penalty_amount = max($newPenalty, $this->penalty_amount);
            $this->save();
        }
    }
}
