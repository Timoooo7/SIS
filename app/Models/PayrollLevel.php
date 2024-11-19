<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollLevel extends Model
{
    use
        HasFactory,
        SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'payroll_level';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'level',
        'salary',
        'employee',
        'deleted_at',
    ];
}
