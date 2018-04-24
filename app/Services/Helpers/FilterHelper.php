<?php

namespace App\Services\Helpers;
use Illuminate\Http\Request;

//Simple class to remove asteriks from the rates

class FilterHelper{

  public function removeAsteriks($rate){
	list(, $data) = explode('/', $rate);

	if(preg_match('/.*(\*)$/', $data)){
	  $data = explode('*', $data);
	  $data = trim($data[0]);
	  return $data;
	}
	else{
	  $data = trim($data);
	  return $data;
	}  
  }

  
}