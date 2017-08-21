<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProds extends Model
{
    protected $table = 'orders_prods';
    
    protected $fillable = ['product_id','count','price','order','guid','order_id'];
    
    protected $hidden = [];

    public function order()
    {
    	return $this->belongsTo('App\Order');
    }
    public function product()
    {
     	return $this->belongsTo('App\Product');
    }
}
