<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Ramsey\Collection\Collection;

/**
 * // Properties
 * @property int id
 * @property string name
 * @property Date created_at
 * @property Date updated_at
 *
 * // Relations
 * @property Menu[]|Collection menus
 */
class Ingredient extends Model
{
    use HasFactory;

    public const TABLE = "ingredients";
    protected $table = self::TABLE;

    protected $fillable = [
        'name'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

}
