<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributionReceipt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'contribution_receipt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contribution_id',
        'financial_id',
        'receipt',
    ];

    /**
     * Contribution who belongs the receipt.
     */
    public function contribution(): BelongsTo
    {
        return $this->belongsTo(Contribution::class, 'contribution_id');
    }

    /**
     * Financial Officer who validate the receipt.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_id');
    }
}
