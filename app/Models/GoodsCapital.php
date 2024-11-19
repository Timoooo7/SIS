<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCapital extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_capital';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'qty',
        'unit',
        'total_price',
        'operational_id',
        'receipt',
    ];

    /**
     * The operational officer that validate this expense item.
     */
    public function operational(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operational_id');
    }
}
