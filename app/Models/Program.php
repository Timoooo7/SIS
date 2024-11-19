<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define table name
     */
    protected $table = 'program';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'department_id',
        'financial_id',
        'staff_lock',
        'pic_id',
        'budget_approval',
        'expense_approval',
    ];

    /**
     * Update program data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('program')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The department that has the program.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * The user that has the program.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * The user that is staff of this program.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(ProgramStaff::class, 'program_id');
    }

    /**
     * The financial user that validate disbursement item.
     */
    public function financial(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The budget items that belong to the program.
     */
    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    /*
    * The expense items that belong to the program.
     */
    public function expenseItems(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }

    /*
    * The disbursement items that belong to the program.
     */
    public function disbursementItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }
}
