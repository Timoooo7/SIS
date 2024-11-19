<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContributionConfig extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'contribution_configuration';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price',
        'start',
        'period',
    ];

    /**
     * Financial Officer who set this configuration.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financial_id');
    }
}
