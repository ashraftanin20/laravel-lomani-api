<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PaymentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'update_time',
        'email_address',
        'order_id',
    ];

    public function order(): BelongsTo {
        $this->belongsTo(Order::class);
    } 
    

}
