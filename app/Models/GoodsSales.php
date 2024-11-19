<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSales extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_sale';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction',
        'customer',
        'operational_id',
        'cashier_id',
        'deleted_at',
    ];


    /**
     * The operational officer that validate this sales.
     */
    public function operational(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operational_id');
    }

    /**
     * The cashier that add this transaction.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * 
     * The order that bleongs to this sales.
     * 
     */
    public function order(): HasMany
    {
        return $this->hasMany(GoodsOrder::class, 'sales_id');
    }
}
