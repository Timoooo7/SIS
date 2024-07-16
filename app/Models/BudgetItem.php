<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BudgetItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'budget_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'name',
        'price',
        'qty',
        'unit',
        'total_price',
    ];

    /**
     * Update budget item data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('budget_item')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The program that belong to the budget item.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
