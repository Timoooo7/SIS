<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStaff extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'program_staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'user_id',
        'title',
        'deleted_at',
    ];


    /**
     * The employee in this program.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The program.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
