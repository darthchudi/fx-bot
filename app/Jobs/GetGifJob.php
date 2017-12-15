<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Giphy;

class GetGifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Search for Gif using GIPHY API
        $giphy = Giphy::search('WTF');
        $count=22;

        //Loop through gifs returned, perform a cURL operation and download image binary file to a GIF and save
        foreach($giphy->data as $gifs){
            $url = $gifs->images->original->url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curlData = curl_exec ($ch);
            curl_close ($ch);

            $downloadPath = __DIR__."/../../gif_test/screaming_".$count.".gif";
            file_put_contents($downloadPath, $curlData);

            $count+=1;
        }

        if($curlData){
            echo 'Successfully downloaded GIFs';
        }

        else{
            echo '!! ERROR !!'; 
        }
    }
}
