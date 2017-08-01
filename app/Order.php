<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'orders';
    
    protected $fillable = ['guid','status','whyCanceled'];
    
    protected $hidden = [];

    public function products()
    {
    	return $this->hasMany('App\OrderProds');
    }
}
