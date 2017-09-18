<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
	protected $table = 'products';
    
    protected $fillable = ['name','guid','image','description','unit','price','count'];
    
    protected $hidden = [];

  public function fromDateTime ($value)
  {
    return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
  }

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
}
