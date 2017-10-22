<?php

namespace App\Common; 
class EmojiTing{	
	public $positive = [
		'&#x1F601;',
		'&#x1F60A;',
		'&#x1F60C;',
		'&#x1F618;',
		'&#x1F648;',
		'&#x1F49B;',
		'&#x1F49C;',
		'&#x1F64F;',
		'&#x1F33B;',
		'&#x1F339;',
		'&#x1F33A;',
		'&#x1F490;'
	];

	public $neutral = [
		'&#x1F612;',
		'&#x1F914;',
		'&#x1F611;',
		'&#x1F615;',
		'&#x1F643;'
	];

	public $negative[
		'&#x1F613;',
		'&#x1F614;',
		'&#x1F629;',
		'&#x1F62D;',
		'&#x1F622;',
		'&#x1F62A;',
		'&#x1F62B;',
		'&#x2639;'
	];



	public function random($type){
		$type= strtolower($type);

		$set = $this->{$type};

		return html_entity_decode($set[rand(0, count($set)-1)], 0, 'UTF-8');
	}
	
}