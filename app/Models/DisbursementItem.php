<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DisbursementItem extends Model
{
    /**
     * Define table name
     */
    protected $table = 'disbursement_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'program_id',
        'financial_id',
        'price',
        'reciept',
    ];

    /**
     * Update disbursement item data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('disbursement_item')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The program that belong to the disbursement item.
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
        return $this->belongsTo(User::class);
    }
}
