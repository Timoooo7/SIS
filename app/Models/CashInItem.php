<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CashInItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'cash_in_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'financial_id',
        'reciept',
    ];

    /**
     * Update cash in item data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('cash_in_item')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The financial user that in charge of cash in item.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_id');
    }
}
