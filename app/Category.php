<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	/**
	* Связанная с моделью таблица.
	*
	* @var string
	*/
	protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['guid','name','image','description'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
