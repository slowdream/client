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
    return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
  }

    public function products()
    {
    	return $this->hasMany('App\OrderProds');
    }
}
