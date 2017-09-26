<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
