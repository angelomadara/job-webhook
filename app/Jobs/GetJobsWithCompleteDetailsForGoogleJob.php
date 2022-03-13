<?php

namespace App\Jobs;

use App\Http\Controllers\GoogleApi\BulkJobsClientController;
use App\Http\Controllers\GoogleApiClientController;
use App\Models\PostedGoogleJob;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetJobsWithCompleteDetailsForGoogleJob implements ShouldQueue
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
        try {
            $date = date("Y-m-d"); // date today
            $limit = 200; // capped jobs per day
            $status = "HTTP/1.1 200 OK";

            $jobs = DB::select("SELECT
                `rj`.`id`,`rj`.`url`
            FROM `received_jobs` AS rj
            LEFT JOIN `posted_google_jobs` AS pgj ON pgj.`received_job_id` = rj.`id`
            WHERE `pgj`.`received_job_id` IS NULL
            AND `rj`.`date_posted` <= ?
            AND `rj`.`postal_code_address` IS NOT NULL
            AND `rj`.`postal_code_address` != ''
            AND `rj`.`employment_type` IS NOT NULL
            AND (`rj`.`salary_min_value` IS NOT NULL OR `rj`.`salary_max_value` IS NOT NULL)
            AND `rj`.`url_status` = ?
            OR `rj`.`is_premium` = ?
            ORDER BY `rj`.`date_posted` DESC
            LIMIT ?",[1,$date,$status,$limit]);

            // return $jobs;
            Log::info($jobs);
            return $jobs;

            $urls = [];
            $posted_google_jobs = [];
            foreach($jobs as $job){
                $urls[] = ['url' => $job->url];
                $posted_google_jobs[] = [
                    'received_job_id' => $job->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
            }
            // Log::info($posted_google_jobs);
            // return false;

            $chunk = 100;
            $chunk_count = count(array_chunk($urls,$chunk));

            for($i = 0; $i < $chunk_count; ++$i){
                $sent_jobs_to_google_response = array_chunk($urls,$chunk)[$i];

                // Log::info($sent_jobs_to_google_response);
                // send jobs to google
                $google_api_client = new BulkJobsClientController();
                $google_api_client->post($sent_jobs_to_google_response,'URL_UPDATED');

                sendResponseToSlack(count($sent_jobs_to_google_response)." new job URLs successfully sent to Google");

                sleep(2); // halt for 2 secs

            }

            PostedGoogleJob::insert($posted_google_jobs); // insert bulk job in the database

        } catch (\Exception $th) {
            Log::info($th->getMessage());
        }
    }
}
