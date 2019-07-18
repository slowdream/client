<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Category.
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property string|null $parent_id
 * @property int $items_parent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereItemsParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['guid', 'name', 'image', 'description', 'parent_id', 'items_parent'];

    protected $hidden = [];

    public function fromDateTime($value)
    {
        if (env('APP_ENV') == 'sqlsrv') {
            return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
        }

        return $this->asDateTime($value)->format(
          $this->getDateFormat()
        );
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
