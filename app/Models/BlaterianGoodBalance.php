<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlaterianGoodBalance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'blaterian_good_balance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance',
        'expense',
        'income',
        'profit',
    ];
}
