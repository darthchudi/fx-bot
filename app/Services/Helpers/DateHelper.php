<?php
namespace App\Services\Helpers;
use Carbon\Carbon;

class DateHelper{

	public function getPeriod(){
      $carbon = new Carbon();
      $today = $carbon->addHour();
      $hour = $today->hour;
      $presentTime = $today->toTimeString();
      $period = "";

      if($hour >= 0 && $hour < 12){
        $period = 'Morning';
      }

      else if ($hour >= 12 && $hour < 18 ){
        $period = 'Afternoon';
      }
      
      else if ($hour >=18 && $hour <=23){
        $period = 'Evening';
      }

    return $period;

  }
}