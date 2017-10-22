<?php

namespace App\Common;

class ResponseHelper{

	public $positive=[
		'Thanks!',
		'Blessings',
		'Peace and Positivity',
		'Great!',
		'Lol this is a generic reply',
		'1 love!'
	];

	public $neutral = [
		'lol okay?',
		'uhhh'
	];

	public $negative = [
		'Haba',
		'Na wa o', 
		'Ffs'
	];

	public function random($type){
		$type= strtolower($type);

		$set = $this->{$type};

		return $set[rand(0, count($set)-1)];
	}
}