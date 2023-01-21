<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Properties
 * @property int id
 * @property string name
 * @property string image_url
 * @property int created_by_user_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * Relations
 * @property User createdBy
 */
class Menu extends Model
{
    use HasFactory;

    public const TABLE = "menus";

    protected $table = self::TABLE;

    public $fillable = [
        'name',
        'image_url',
        'created_by_user_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }
}
