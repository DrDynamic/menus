<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\Permissions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * Properties
 * @property int id
 * @property string name
 * @property string email
 * @property Carbon email_verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * Foreigns
 * @property Role[]|Collection roles
 * @property string[]|Collection permissions
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const TABLE = 'users';
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission(string $permission)
    {
        return $this->permissions->contains($permission);
    }

    public function getPermissionsAttribute()
    {
        $permissions = collect();

        foreach ($this->roles as $role) {
            if (array_key_exists($role->id, Permissions::ROLES)) {
                $permissions->push(Permissions::ROLES[$role->id]);
            }
        }

        return $permissions->flatten()->unique()->values();
    }
}
