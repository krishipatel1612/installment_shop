<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'description'
    ];
public function category()
{
    return $this->belongsTo(Category::class);
}

public function installmentPlans()
{
    return $this->hasMany(InstallmentPlan::class);
}


}
