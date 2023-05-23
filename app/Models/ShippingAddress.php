<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'full_name',
        'address',
        'city',
        'postal_code',
        'country',
    ];

    public function order(): BelongsTo {
        $this->belongsTo(Order::class);
    }

}
