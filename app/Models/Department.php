<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'department';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'budget',
        'disbursement',
        'expense',
    ];

    /**
     * Update department data.
     * 
     * @return number of affected rows
     */
    function insert($data): int
    {
        $affected = DB::table('department')
            ->insert($data);
        return $affected;
    }

    /**
     * Update department data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('department')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The programs that belong to the department.
     */
    public function program(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * The manager user that has control of department.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
