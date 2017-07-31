<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = 'products';
    
    protected $fillable = ['name','guid','image','description','unit','warehouse'];
    
    protected $hidden = [];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

}
