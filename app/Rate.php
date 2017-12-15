<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
	protected $guarded = [];


	public function scopeLatestFirst($query){
		return $query->orderBy('id', 'desc');
	}
}
