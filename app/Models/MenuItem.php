<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MenuItem extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'menu_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'volume',
        'volume_unit',
        'mass',
        'mass_unit',
        'price',
        'stand_id',
        'sale',
    ];

    /**
     * Update department data.
     * 
     * @return number of affected rows
     */
    function insert($data): int
    {
        $affected = DB::table($this->table)
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
        $affected = DB::table($this->table)
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The person that in charge of the stand.
     */
    public function stand(): BelongsTo
    {
        return $this->belongsTo(Stand::class, 'stand_id');
    }

    /**
     * Sales of this menu.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Stand::class, 'menu_id');
    }
}
