<?php

namespace App\Jobs;

use App\Http\Controllers\GoogleApi\BulkJobsClientController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteSentJobsToGoogleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $date;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->date = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $date = $this->date;

            $jobs = DB::select("SELECT
                rj.id, rj.url
            from received_jobs as rj
            left join posted_google_jobs as pgj on pgj.received_job_id = rj.id
            where pgj.received_job_id is not null
            and date(pgj.created_at) = ?
            and rj.url_status = ?
            limit 200",[$date,"HTTP/1.1 404 Not Found"]);

            $urls = [];
            foreach($jobs as $job){
                $urls[] = ['url' => $job->url];
            }
            Log::info($urls);
            $chunk = 100;
            $chunk_count = count(array_chunk($urls,$chunk));

            // for($i = 0; $i < $chunk_count; ++$i){
            //     $sent_jobs_to_google_response = array_chunk($urls,$chunk)[$i];

            //     // send jobs to google
            //     $google_api_client = new BulkJobsClientController();
            //     $google_api_client->post($sent_jobs_to_google_response,'URL_DELETED');

            //     sleep(2); // halt for 2 secs

            // }

        } catch (\Exception $th) {
            Log::info($th->getMessage());
        }
    }
}
