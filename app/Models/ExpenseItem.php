<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ExpenseItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'expense_item';

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
        'financial_id',
        'reciept',
    ];

    /**
     * Update expense item data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('expense_item')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The program that belong to the expense item.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * The financial user that validate disbursement item.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_id');
    }
}
