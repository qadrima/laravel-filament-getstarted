<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'type',
        'recurring_type',
        'specific_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function recurringMonthly()
    {
        return $this->hasMany(CustomerProductRecurringMonthly::class);
    }

    public function recurringWeekly()
    {
        return $this->hasMany(CustomerProductRecurringWeekly::class);
    }
}
