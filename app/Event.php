<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = ['name', 'comment'];

    protected $hidden = [];

    public function fromDateTime($value)
    {
        if (env('APP_ENV') == 'sqlsrv') {
            return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
        }

        return $this->asDateTime($value)->format(
          $this->getDateFormat()
        );
    }
}
