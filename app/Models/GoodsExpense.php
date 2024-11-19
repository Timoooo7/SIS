<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsExpense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_expense';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'category_id',
        'price',
    ];

    /**
     * The category details that has the stand income.
     */
    public function category_detail()
    {
        return $this->category == 'goods expense' ? $this->capital : $this->withdraw;
    }

    /**
     * The stand details that has the stand income.
     */
    public function capital(): BelongsTo
    {
        return $this->belongsTo(GoodsCapital::class, 'category_id');
    }

    /**
     * The withdraw details that has the stand income.
     */
    public function withdraw(): BelongsTo
    {
        return $this->belongsTo(CashInItem::class, 'category_id');
    }
}
