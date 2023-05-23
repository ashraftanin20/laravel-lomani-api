<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cart_qty',
        'image',
        'price',
        'order_id'
    ];

    public function order(): BelongsTo {
        $this->belongsTo(Order::class);
    }

    public function products(): HasMany {
        $this->hasMany(Product::class);
    } 
}
