<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Cash
 *
 * @property int $id
 * @property int|null $value
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cash whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cash whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cash whereValue($value)
 * @mixin \Eloquent
 */
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
  public function order()
  {
    return $this->belongsTo('App\Order');
  }
  public function summ()
  {
    $summ = 0;
    $cash = $this::where('status', 'injected')->get();
    foreach ($cash as $item) {
      $summ += $item->value;
    }
    return $summ;
  }
}