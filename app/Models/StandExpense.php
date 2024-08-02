<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StandExpense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'stand_expense_item';

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
        'stand_id',
        'operational_id',
        'reciept',
    ];


    /**
     * Update expense item data.
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
    public function operational(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operational_id');
    }
}
