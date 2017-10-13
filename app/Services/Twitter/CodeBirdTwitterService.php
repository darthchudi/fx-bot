<?php

namespace App\Services\Twitter;

class CodeBirdTwitterService implements TwitterService{
	public function getMentions($since = null){
		return 'getMentions';
	}

	public function sendTweet($text, $inReplyTo=null){

	}
}