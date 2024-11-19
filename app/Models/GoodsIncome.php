<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsIncome extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'goods_income';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price',
        'category',
        'category_id',
    ];

    /**
     * The category details that has the goods income.
     */
    public function category_detail()
    {
        return $this->category == 'program disbursement' ? $this->program : $this->sales;
    }

    /**
     * The good item details that has the goods income.
     */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(GoodsSales::class, 'category_id');
    }

    /**
     * The program details that has the good income.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(DisbursementItem::class, 'category_id');
    }
}
