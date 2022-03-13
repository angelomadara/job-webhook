<?php

namespace App\Jobs;

use App\Models\RawReceivedJobs;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveReceivedRawJobsJob implements ShouldQueue
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
        $received_jobs = $this->data;
        $raw_jobs = [];

        // foreach($received_jobs as $key => $job){

            $raw_jobs[] = [
                'title' => $received_jobs['title'],
                'url' => $received_jobs['url'],
                'date' => date("Y-m-d",strtotime($received_jobs['date'])),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        // }
        Log::info(['raw_jobs' => $raw_jobs]);
        return false;
        /**
         * jobs received from jobtensor api
         */
        // RawReceivedJobs::insert($raw_jobs); // Raw Jobs received (duplicate jobs inside)

    }
}
