<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'orders';
    
    protected $fillable = ['guid'];
    
    protected $hidden = [];

    public function ()
    {
    	return $this->hasMany('App\Product');
    }
}
