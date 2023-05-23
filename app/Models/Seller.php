<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'description',
        'rating',
        'num_reviews',
        'user_id'
    ];

    public function user(): BelongsTo {
        $this->belongsTo(User::class);
    }

    public function orders(): HasMany {
        $this->hasMany(Order::class);
    }
}
