<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Product.
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property int $price
 * @property int $count
 * @property string $image
 * @property string $description
 * @property string $unit
 * @property int $category_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Category $category
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = ['name', 'guid', 'image', 'description', 'unit', 'price', 'count', 'deleted_at'];

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

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
