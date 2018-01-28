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

		for($i=1; $i<=24; $i++){
			$this->gifs[] = $i;
		}
	}

	public function tweet($dollars, $pounds, $period){
		//Set Tweet Status[Content]
		$dollarTweet = "Good {$period}, a dollar costs N{$dollars}";
		$poundTweet = "Good {$period}, a pound costs N{$pounds}";

		//Select a random gif
		$gif = array_rand($this->gifs, 1);

		//Set Path to GIF file
		$file = base_path().'/gifs/screaming_'.$gif.'.gif';;

		//Upload GIF File to Twitter
		$reply = $this->client->media_upload([
			'media'=>$file
		]);

		echo 'Worked here';

		$media_id = [];

		//Get Uploaded GIF file media ID
		$media_id[] = $reply->media_id_string;

		dd($media_id);
		//Send Tweet of Exchange Rate along with GIF media ID
		// $dTweet = $this->client->statuses_update([
		// 	'status'=> $dollarTweet,
		// 	'media_id'=>$media
		// ]);

		// if($dTweet){
		// 	echo 'Yes!';
		// }
		// else{
		// 	echo 'No';
		// }
	}

	// public function tweetPounds($pound, $period){
	// 	//Set Tweet Status[Content]
	// 	$content = "Good {$period}, a pound costs N{$pound}";

	// 	//Select a random gif
	// 	$gif = array_rand($this->gifs, 1);

	// 	//Set Path to GIF file
	// 	$file = base_path().'/gifs/screaming_'.$gif.'.gif';

	// 	//Upload GIF File to Twitter
	// 	$reply = $this->client->media_upload([
	// 		'media'=>$file
	// 	]);

	// 	//Get Uploaded GIF file media ID
	// 	$media_id = $reply->media_id_string;


	// 	//Send Tweet of Exchange Rate along with GIF media ID
	// 	$reply = $this->client->statuses_update([
	// 		'status'=> $content,
	// 		'media_id'=>$media_id
	// 	]);

	// 	if($reply){
	// 		echo 'yes!';
	// 	}
	// }
}