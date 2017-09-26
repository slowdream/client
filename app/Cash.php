<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cash extends Model
{
	protected $table = 'cash';

  protected $fillable = ['value','status'];

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
}