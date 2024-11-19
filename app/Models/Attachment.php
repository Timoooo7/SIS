<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'attachment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'document',
        'link',
        'deleted_at',
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
