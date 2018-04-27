<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Console\Command;

class SchedularDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daemon {--sleep=3600}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the scheduler every hour.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (true) {
            $presentHour = Carbon::now("Africa/Lagos")->hour;
            if($presentHour==10 || $presentHour==14 || $presentHour== 20){
                $this->line('<info>[' . Carbon::now("Africa/Lagos")->format('d-m-Y H:i:s') . ']</info> Sending Tweet');
                $this->call('fxbot:tweet');   
            }

            sleep($this->option('sleep'));
        }
    }
}
