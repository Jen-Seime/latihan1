<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image',
        'title',
        'description',
        'price',
        'stock',
        'category_id',

    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
