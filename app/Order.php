<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
	protected $table = 'orders';

  protected $fillable = ['guid','status','whyCanceled'];

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

  public function products()
  {
  	return $this->hasMany('App\OrderProds');
  }
}
