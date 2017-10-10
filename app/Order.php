<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Order
 *
 * @property int $id
 * @property int|null $guid
 * @property string|null $status
 * @property string|null $contacts
 * @property string|null $whyCanceled
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderProds[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereWhyCanceled($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
	protected $table = 'orders';

  protected $fillable = ['guid','status','whyCanceled'];

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
  	return $this->hasMany('App\OrderProds');
  }

  public function getActive()
  {
  	return $this->firstOrCreate(['status'=>'active']);
  }

  public function cash()
  {
    return $this->hasMany('App\Cash');
  }
}
