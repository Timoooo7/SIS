<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'product_variant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'product_id',
        'description',
        'price',
        'sale',
        'stock',
        'rate',
    ];

    /**
     * The product of this image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(GoodsProduct::class, 'product_id');
    }
}
