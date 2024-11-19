<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Stand extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'stand';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'place',
        'date',
        'pic_id',
        'menu_lock',
        'sale_validation',
        'expense',
        'income',
        'profit',
    ];


    /**
     * Update department data.
     * 
     * @return number of affected rows
     */
    function insert($data): int
    {
        $affected = DB::table('stand')
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
        $affected = DB::table('stand')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The person that in charge of the stand.
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * The person that validate the stand menu.
     */
    public function menu_validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'menu_lock');
    }

    /**
     * The person that validate the stand income sales.
     */
    public function sales_validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sale_validation');
    }

    /**
     * The expenses that belong to the stand.
     */
    public function expense(): HasMany
    {
        return $this->hasMany(StandExpense::class);
    }

    /**
     * The sales that belong to the stand.
     */
    public function sale(): HasMany
    {
        return $this->hasMany(StandSales::class, 'stand_id');
    }
}
