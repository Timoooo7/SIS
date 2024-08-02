<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FoodsIncome extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'foods_income';

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
        return $this->category == 'stand income' ? $this->stand : $this->program;
    }

    /**
     * The stand details that has the stand income.
     */
    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class, 'category_id');
    }

    /**
     * The program details that has the stand income.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(DisbursementItem::class, 'category_id');
    }
}
