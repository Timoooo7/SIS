<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollBalance extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'payroll_balance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'balance',
        'deleted_at',
    ];
}
