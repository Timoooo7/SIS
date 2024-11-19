<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsProduct extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'pic_id',
    ];

    /**
     * Image list of this product.
     */
    public function image(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    /**
     * Variant list of this product.
     */
    public function variant(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    /**
     * PIC of this product.
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
