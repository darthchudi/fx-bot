<?php

namespace App\Services\Twitter;
use App\Services\Twitter\Exceptions\RateLimitExceededException;
use App\Services\Twitter\Exceptions\UnableToTweetException;
use Codebird\Codebird;
class CodeBirdTwitterService implements TwitterService{
	protected $client;
	public function __construct(Codebird $client){
		$this->client= $client;
	}

	public function getMentions($since = null){
		$mentions = $this->client->statuses_mentionsTimeline($since ? 'since_id='.$since : '');

		if((int)$mentions->rate->remaining===0){
			throw new RateLimitExceededException;
		}
		return collect($this->extractTweets($mentions));
	}

	public function sendTweet($text, $inReplyTo=null){
		$params=[
			'status'=>$text,
		];

		if($inReplyTo){
			$params['in_reply_to_status']=$inReplyTo;
		}

		try{
			$this->client->statuses_update($params);
		} catch(UnableToTweetException $e){
			return $this->error('Unable to send tweet :( ');
		}
		
	}

	protected function extractTweets($response){
		unset($response->rate);
		unset($response->httpstatus);

		return $response;
	}
}