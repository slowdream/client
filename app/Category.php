<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
	
	protected $table = 'categories';
    
    protected $fillable = ['guid','name','image','description','parent_id','items_parent'];
    
    protected $hidden = [];

  public function fromDateTime ($value)
  {
    return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
  }

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
