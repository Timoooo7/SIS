<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisbursementLetter extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * Define table name
     */
    protected $table = 'disbursement_letter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'disbursement_id',
        'letter',
    ];

    /**
     * Program that has this letter.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Disbursement that belongs to this letter.
     */
    public function disbursement(): HasOne
    {
        return $this->hasOne(DisbursementItem::class, 'letter_id');
    }
}
