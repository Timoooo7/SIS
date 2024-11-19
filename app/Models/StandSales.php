<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StandSales extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cashier_id',
        'stand_id',
        'discount',
        'transaction',
        'customer',
        'deleted_at',
    ];


    /**
     * Update item data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table($this->table)
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The stand that has the expense item.
     */
    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class, 'stand_id');
    }

    /**
     * The operational officer that has the expense item.
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
        return $this->hasMany(FoodOrder::class, 'sales_id');
    }
}
