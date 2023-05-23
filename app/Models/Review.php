<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'comment',
        'rating',
        'product_id',
    ];

    public function product(): BelongsTo {
        $this->belongsTo(Product::class);
    }
}
