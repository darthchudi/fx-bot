<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Twitter\TwitterService;
class SendTweet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    public $handle;
    public $name;
    public $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    
    public function __construct($id, $handle, $name, $text )
    {
        $this->id=$id;
        $this->handle=$handle;
        $this->name=$name;
        $this->text=$text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TwitterService $twitter)
    {
        $twitter->sendTweet("@{$this->handle} Hey, {$this->name}, {$this->text}", $this->id);
    }
}
