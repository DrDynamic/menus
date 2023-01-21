<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * // Properties
 * @property int id
 * @property string name
 * @property int amount
 * @property string unit
 * @property int menu_id
 * @property Date created_at
 * @property Date updated_at
 *
 * // Relations
 * @property Menu menu
 */
class Ingredient extends Model
{
    use HasFactory;

    public const TABLE = "ingredients";
    protected $table = self::TABLE;

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

}
