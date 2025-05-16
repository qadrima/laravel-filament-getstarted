<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    public function customerProducts()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function getFullLabelAttribute(): string
    {
        return "{$this->name} - Rp " . number_format($this->price, 0, ',', '.');
    }
}
