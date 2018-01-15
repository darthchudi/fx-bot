<?php

namespace App\Services\Twitter;
use App\Services\Twitter\Exceptions\RateLimitExceededException;
use App\Services\Twitter\Exceptions\UnableToTweetException;
use Codebird\Codebird;
use App\Rate;

class CodeBirdTwitterService implements TwitterService{
	protected $client;

	public $gifs;

	public function __construct(Codebird $client){
		$this->client= $client;

		for($i=1; $i<=25; $i++){
			$this->gifs[] = $i;
		}
	}

	//Function to tweet Rates without GIF
	public function tweetRates($text){
		$params['status']=$text;
		try{
			$this->client->statuses_update($params);
		} catch(UnableToTweetException $e){
			return $this->error('Unable to send tweet :(');
		}
	}

	public function tweetDollars($dollar, $period){
		//Set Tweet Status[Content]
		$status = "Good {$period}, a dollar costs is N{$dollar}";

		//Select a random gif
		$gif = array_rand($this->gifs, 1);

		//Set Path to GIF file
		$file = base_path().'/gifs/screaming_'.$gif.'.gif';

		//Upload GIF File to Twitter
		$reply = $this->client->media_upload([
			'media'=>$file
		]);

		//Get Uploaded GIF file media ID
		$media_id = $reply->media_id_string;


		//Send Tweet of Exchange Rate along with GIF media ID
		$reply = $this->client->statuses_update([
			'status'=> $status,
			'media_ids'=>$media_id
		]);

		if($reply){
			echo 'Yes!';
		}
		else{
			echo 'No';
		}
	}

	public function tweetPounds($pound, $period){
		//Set Tweet Status[Content]
		$status = "Good {$period}, a pound is {$pound}";

		//Select a random gif
		$gif = array_rand($this->gifs, 1);

		//Set Path to GIF file
		$file = base_path().'/gifs/screaming_'.$gif.'.gif';

		//Upload GIF File to Twitter
		$reply = $this->client->media_upload([
			'media'=>$file
		]);

		//Get Uploaded GIF file media ID
		$media_id = $reply->media_id_string;


		//Send Tweet of Exchange Rate along with GIF media ID
		$reply = $this->client->statuses_update([
			'status'=> $status,
			'media_ids'=>$media_id
		]);
	}



	//Function to tweet rates with GIF
	public function tweetGif(){

		$rate = new Rate;
	    $currentRate = $rate->latestFirst()->first();
	    $dollars = $currentRate->dollars;
	    $dollars = substr($dollars, 6, 3);
	    $pounds = substr($currentRate->pounds, 6, 3);
	    $period = $currentRate->time_period;


		//Select a random gif
		$gif = array_rand($this->gifs, 1);

		//Set Path to GIF file
		$file = base_path().'/gifs/screaming_'.$gif.'.gif';

		//Upload GIF File to Twitter
		$reply = $this->client->media_upload([
			'media'=>$file
		]);

		//Get Uploaded GIF file media ID
		$media_id = $reply->media_id_string;


		//Send Tweet of Exchange Rate along with GIF media ID
		$reply = $this->client->statuses_update([
			'status'=> "Good {$period}, the price of a dollar is {$dollars}.",
			'media_ids'=>$media_id
		]);

		if($reply){
			echo "Success! <br/>";
		}
		else{
			echo 'Errors <br/>';
		}

	}
}