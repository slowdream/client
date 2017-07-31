<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	
	protected $table = 'categories';
    
    protected $fillable = ['guid','name','image','description'];
    
    protected $hidden = [];

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
