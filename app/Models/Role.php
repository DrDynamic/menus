<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Properties
 * @property string id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * Foreign
 * @property User[]|Collection users
 */
class Role extends Model
{
    use HasFactory;

    public const TABLE = "roles";
    protected $table = self::TABLE;

    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
