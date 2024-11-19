<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodOrder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'foods_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sales_id',
        'menu_id',
        'amount',
    ];

    /**
     * The menu that has the food order .
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_id');
    }

    /**
     * The stand sales that has the food order .
     */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(StandSales::class, 'sales_id');
    }
}
