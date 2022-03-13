<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanReceivedJobsBulkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jobs = $this->data;

        /**
         * selecting unique jobs and filling up all the information from the second database
         */
        foreach($jobs as $key => $job){
            CleanReceivedJobsJob::dispatch($job)->delay(now()->addSeconds(10));
        }

        /**
         *  when the job selection is done, selecting unique jobs with complete details will begin the process and when the process is done, the jobs will be submitted to google
         */
        GetJobsWithCompleteDetailsForGoogleJob::dispatch()->delay(now()->addSeconds(10));
    }
}
