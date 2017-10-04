<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\OrderProds
 *
 * @property int $id
 * @property int|null $guid
 * @property int|null $product_id
 * @property int|null $count
 * @property int|null $price
 * @property int|null $order_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Order|null $order
 * @property-read \App\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderProds whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderProds extends Model
{
  protected $table = 'orders_prods';

  protected $fillable = ['product_id','count','price','order','guid','order_id'];

  protected $hidden = [];

  public function fromDateTime ($value)
  {
    if(env('APP_ENV') == 'sqlsrv') {
      return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
    return $this->asDateTime($value)->format(
      $this->getDateFormat()
    );
  }

  public function order()
  {
  	return $this->belongsTo('App\Order');
  }

  public function product()
  {
   	return $this->belongsTo('App\Product');
  }
}
