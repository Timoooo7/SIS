<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'contribution';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'months',
    ];

    /**
     * Employee who has the contribution.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Contribution Receipt who belongs to this contribution.
     */
    public function receipt(): HasMany
    {
        return $this->hasMany(ContributionReceipt::class, 'contribution_id');
    }
}
