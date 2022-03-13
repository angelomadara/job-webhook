<?php

namespace App\Console\Commands;

use App\Http\Controllers\GoogleApiClientController;
use App\Models\GoogleJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GoogleApiJobUrlDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:url_deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'notify google to remove the job link to index';

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
     * @return int
     */
    public function handle()
    {
        // $date = date("Y-m-d"); // for testing only
        $date = date("Y-m-d",strtotime("-30 day"));
        $jobs = GoogleJob::where('date',$date)->get();

        $jobs_only_url = [];
        foreach ($jobs as $key => $job) {
            $jobs_only_url[] = [
                'url' => $job['url']
            ];
        }

        $_chunk = 100;

        $_jobs_only_url_count = count(array_chunk($jobs_only_url,$_chunk));

        $responses = [];

        for($i = 0; $i < $_jobs_only_url_count; ++$i){
            // $sent_jobs_to_google_response = array_chunk($jobs_only_url,$_chunk)[$i]; // for testing

            $sent_jobs_to_google_response = (new GoogleApiClientController)->batchUrlUpdated(array_chunk($jobs_only_url,$_chunk)[$i],'URL_DELETED');

            sleep(5);

            if($sent_jobs_to_google_response){
                sendResponseToSlack(count($jobs_only_url)." old job URLs successfully removed to Google");
            }

            $responses[] = [
                'sent_jobs_to_google_response'=>$sent_jobs_to_google_response,
            ];
        }

        // Log::info($responses);
    }
}
