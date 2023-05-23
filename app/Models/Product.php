<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seller;
use App\Models\Review;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'brand',
        'category',
        'description',
        'price',
        'count_in_stock',
        'rating',
        'num_reviews',
        'seller_id',
        'order_item_id'
    ];

    public function seller(): BelongsTo {
        $this->belongsTo(Seller::class);
    }

    public function reviews(): HasMany {
        $this->hasMany(Review::class);
    }
}
