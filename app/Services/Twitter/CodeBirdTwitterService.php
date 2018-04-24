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

		for($i=1; $i<=23; $i++){
			$this->gifs[] = $i;
		}
	}

	public function tweet($dollars, $pounds, $period){
		//Set Tweet Status[Content]
		$dollarString = "Good {$period}, a dollar costs N{$dollars}";
		$poundString = "Good {$period}, a pound costs N{$pounds}";

		$dollarTweet = $this->upload($dollarString);
		$poundTweet = $this->upload($poundString);

		echo "Successfully tweeted dollars and pounds \n";
	}

	public function upload($tweet){
		//Select a random gif
		$gif = array_rand($this->gifs, 1);

		//Set Path to GIF file
		$file = public_path()."/gifs/screaming_".$gif.'.gif';
		$sizeBytes = filesize($file);
		$fp = fopen($file, 'r');

		//Upload GIF File to Twitter
		$reply = $this->client->media_upload([
			'command'=> 'INIT',
			'media_type' =>'image/gif',
			'total_bytes'=>$sizeBytes
		]);

		$media_id = $reply->media_id_string;

		$segment_id = 0;

		while(!feof($fp)){
			$chunk = fread($fp, 1048576);

			$reply = $this->client->media_upload([
				'command'=>'APPEND',
				'media_id'=>$media_id,
				'segment_index'=>$segment_id,
				'media'=>$chunk
			]);
			$segment_id++;
		}

		fclose($fp);

		$reply = $this->client->media_upload([
			'command'=>'FINALIZE',
			'media_id'=>$media_id
		]);

		$tweet = $this->client->statuses_update([
			'status'=> $tweet,
			'media_ids'=>$media_id
		]);
	}

	
}