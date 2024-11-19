<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsOrder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sales_id',
        'variant_id',
        'amount',
        'discount',
    ];

    /**
     * The product variant that has the good order .
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * The goods sale that has the good order .
     */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(GoodsSales::class, 'sales_id');
    }
}
