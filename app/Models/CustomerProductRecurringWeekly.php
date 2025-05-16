<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProductRecurringWeekly extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_product_id',
        'day',
    ];
}
