<?php

namespace App\Console\Commands;

use App\Http\Controllers\GoogleApiClientController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleApiJobUrlUpdated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:url_updated {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a URL - notify Google of a new URL to crawl or that content at a previously-submitted URL has been updated';

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
        $data = $this->argument('data');
        // Log::info(count($data)." new job URLs successfully sent to Google");
        // return 'false';
        // print_r(gettype($data));
        $GoogleApiClientController = new GoogleApiClientController();
        $sent_jobs_to_google_response = $GoogleApiClientController->batchUrlUpdated($data,'URL_UPDATED');

        $message = "";
        if($sent_jobs_to_google_response){
            $message = count($data)." new job URLs successfully sent to Google";
        }

        sendResponseToSlack($message);

        /**
         * save the google responses
         * the response changed I need to restructure the logic for saving the response
         */
        // $a = $GoogleApiClientController->analyzeUrlUpdateResponse(new Request($sent_jobs_to_google_response));
        // Log::info($a);
        // print_r($a);
    }
}
