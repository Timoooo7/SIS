<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FoodsExpense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'foods_expense';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'category_id',
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
     * The category details that has the stand income.
     */
    public function category_detail()
    {
        return $this->category == 'stand expense' ? $this->stand : $this->withdraw;
    }

    /**
     * The stand details that has the stand income.
     */
    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class, 'category_id');
    }

    /**
     * The seeo cash in details that has the stand income.
     */
    public function withdraw(): BelongsTo
    {
        return $this->belongsTo(CashInItem::class, 'category_id');
    }
}
