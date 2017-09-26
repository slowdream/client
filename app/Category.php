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
    if(env('APP_ENV') == 'sqlsrv') {
      return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
    return $this->asDateTime($value)->format(
      $this->getDateFormat()
    );
  }

  public function products()
  {
    return $this->hasMany('App\Product');
  }
}
