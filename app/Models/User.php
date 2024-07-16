<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'roles_id',
        'proker_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Update user data.
     * 
     * @return number of affected rows
     */
    function change(int $id, $data): int
    {
        $affected = DB::table('users')
            ->where('id', $id)
            ->update($data);
        return $affected;
    }

    /**
     * The roles that has the user.
     */
    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The program that belong to the user.
     */
    public function program(): HasOne
    {
        return $this->hasOne(Program::class, 'pic_id');
    }

    /**
     * The department that belong to the user as manager.
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'manager_id');
    }

    /**
     * The stand that user in charge of.
     */
    public function stand(): HasOne
    {
        return $this->hasOne(Stand::class, 'pic_id');
    }
}
