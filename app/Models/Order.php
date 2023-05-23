<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'items_price',
        'shipping_price',
        'tax_price',
        'total_price',
        'payment_method',
        'is_paid',
        'paid_at',
        'id_delivered',
        'delivered_at',
        'seller_id',
        'user_id',
    ];

    public function paymentResult(): HasOne {
        $this->hasOne(PaymentResult::class);
    }

    public function shippingAddress(): HasOne {
        $this->hasOne(ShippingAddress::class);
    }

    public function orderItems(): HasMany {
        $this->hasMany(OrderItem::class);
    }
    public function seller(): BelongsTo {
        $this->belongsTo(Seller::class);
    }

    public function user(): BelongsTo {
        $this->belongsTo(User::class);
    }
}
