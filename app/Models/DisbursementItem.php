<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DisbursementItem extends Model
{
    use HasFactory, SoftDeletes;
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
        'letter_id',
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
     * The disbursement letter that belong to the disbursement item.
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(DisbursementLetter::class, 'letter_id');
    }

    /**
     * The financial user that validate disbursement item.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
