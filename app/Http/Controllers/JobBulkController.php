<?php

namespace App\Http\Controllers;

use App\Jobs\CleanReceivedJobsBulkJob;
use App\Jobs\CleanReceivedJobsJob;
use App\Jobs\CrawlJobLdJsonJob;
use App\Jobs\GetJobsWithCompleteDetailsForGoogleJob;
use App\Jobs\GoogleJobUrlDelete;
use App\Jobs\GoogleJobUrlUpdate;
use App\Jobs\PostToArbeitsagenturJob;
use App\Jobs\PostToGoogleJob;
use App\Jobs\SaveReceivedRawJobsJob;
use App\Models\GoogleJob;
use App\Models\RawReceivedJobs;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Google\Service\Bigquery\Resource\Jobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

ini_set('max_execution_time', 180); //3 minutes temporary

class JobBulkController extends Controller
{
    /**
     * new job posting
     */
    public function bulkPosting(Request $request){
        $received_jobs = $request->all();

        if(count($received_jobs)){
            sendResponseToSlack(count($received_jobs)." jobs were received from jobtensor api");
        }

        return $this->bulkPostingV2($received_jobs);
    }

    /**
     * old version of bulk posting
     */
    public function bulkPostingOld(Request $request){
        $received_jobs = $request->all();

        if(count($received_jobs)){
            sendResponseToSlack(count($received_jobs)." jobs were received from jobtensor api");
        }

        /**
         * temporary code injection
         */
        return $this->bulkPostingV2($received_jobs);


        /**
         * sanitize data
         */
        $jobs = [];
        $jobs_only_url = [];
        foreach ($received_jobs as $key => $job) {
            /**
             * clean the data remove jobs that is already been sent to google
             */

            $query_params = [];

            $job_exist_query = "SELECT `title`, `url`, `company`, `location`, `experience`, `employment_type` FROM `google_jobs` WHERE";

            if(array_key_exists('title', $job)){
                $job_exist_query .= " `title` = ?";
                $query_params[] = $job['title'];
            }

            if(array_key_exists('url', $job)){
                $job_exist_query .= " AND `url` = ?";
                $query_params[] = $job['url'];
            }

            if(array_key_exists('company', $job)){
                $job_exist_query .= " AND `company` = ?";
                $query_params[] = $job['company'];
            }

            if(array_key_exists('location', $job)){
                $job_exist_query .= " AND `location` = ?";
                $query_params[] = $job['location'];
            }

            if(array_key_exists('experience', $job)){
                $job_exist_query .= " AND `experience` = ?";
                $query_params[] = $job['experience'];
            }

            if(array_key_exists('employmentType', $job)){
                $job_exist_query .= " AND `employment_type` = ?";
                $query_params[] = $job['employmentType'];
            }

            $job_exist_query .= " LIMIT 1";

            $job_exist = DB::select($job_exist_query, $query_params);
            // return $job_exist; // for testing

            // if job is not listed on the table append to array
            if(!$job_exist){
                $jobs_only_url[] = [ 'url' => $job['url'] ];
                $jobs[] = [
                    'title' => $job['title'],
                    'url' => $job['url'],
                    'date' => date("Y-m-d",strtotime($job['date'])),
                    'company' => array_key_exists('company', $job) ? $job['company'] : null,
                    'location' => array_key_exists('location',$job) ? $job['location'] : null,
                    'experience' => array_key_exists('experience',$job) ? $job['experience'] : null,
                    'employment_type' => array_key_exists('employmentType',$job) ? $job['employmentType'] : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_indexed' => 1
                ];
            }
        }

        if(count($jobs_only_url) && count($received_jobs)){
            sendResponseToSlack(count($jobs_only_url)." unique jobs selected out of ".count($received_jobs)." jobs received");
        }

        $cap = 200;
        // get the remaining slots on the daily cap
        $available_slots_on_daily_cap =  $cap - count($jobs_only_url);
        $unindexed_jobs = [];
        if($available_slots_on_daily_cap >= 1){
            // get the unindexed jobs from yesterday or in the past few days limit by the $available_slots_on_daily_cap
            $unindexed_jobs = GoogleJob::select('url')->where('is_indexed',0)->limit($available_slots_on_daily_cap)->get()->toArray();
            // merged the two arrays
            $jobs_only_url = array_merge($jobs_only_url, $unindexed_jobs);
        }

        $__jobs = [
            'cap_jobs' => array_slice($jobs_only_url,0,$cap),
            'indexed_jobs'=> array_slice($jobs,0,$cap),
            'unindexed_jobs' => array_slice($jobs,$cap,count($jobs))
        ];

        // return $__jobs;
        $_chunk = 100;
        // return array_chunk($__jobs['cap_jobs'],$_chunk);
        $_chunk_count = count(array_chunk($__jobs['cap_jobs'],$_chunk));
        $responses = [];
        $save_response = [];

        // maximum loop x2
        for($i = 0; $i < $_chunk_count; ++$i){
            $sent_jobs_to_google_response = array_chunk($__jobs['cap_jobs'],$_chunk)[$i];

            $g = (new GoogleJobUrlUpdate($sent_jobs_to_google_response))->delay(now()->addSeconds(3));
            dispatch($g);

            if(count($__jobs['indexed_jobs'])){
                $save_response = GoogleJob::insert(array_chunk($__jobs['indexed_jobs'],$_chunk)[$i]);
            }

            $responses[] = [
                'sent_jobs_to_google_response'=>$sent_jobs_to_google_response,
                'save_response'=>$save_response
            ];
        }

        if($responses){
            sendResponseToSlack(count($jobs)." new job URLs successfully saved to database");
        }

        /**
         * bulk saving of extra jobs which is not sent to google yet
         */
        if(count($__jobs['unindexed_jobs'])){
            $extra_jobs = [];
            foreach($__jobs['unindexed_jobs'] as $key => $job){
                $extra_jobs[] = [
                    'title' => $job['title'],
                    'url' => $job['url'],
                    'date' => date("Y-m-d",strtotime($job['date'])),
                    'company' => array_key_exists('company', $job) ? $job['company'] : null,
                    'location' => array_key_exists('location',$job) ? $job['location'] : null,
                    'experience' => array_key_exists('experience',$job) ? $job['experience'] : null,
                    'employment_type' => array_key_exists('employmentType',$job) ? $job['employmentType'] : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_indexed' => 0
                ];
            }
            GoogleJob::insert($extra_jobs);
        }

        /**
         * update the jobs that is not indexed yet
         * see $unindexed_jobs above
         */
        if(count($unindexed_jobs)){
            foreach($unindexed_jobs as $key => $job){
                GoogleJob::where('url',$job['url'])->update(['is_indexed'=>1]);
            }
        }

        return response([
            'message' => count($__jobs['cap_jobs'])." new job URLs successfully saved to database and ".count($__jobs['unindexed_jobs']),
            'responses' => $responses
        ],Response::HTTP_OK);

    }

    /**
     * special method for Jeff Testing purpose only
     * can be remove in the future or can keep for future tests
     */
    public function bulkPostingJeffTesting(Request $request){
        $received_jobs = $request->all();

        /**
         * sanitize data
         */
        $jobs = [];
        $jobs_only_url = [];
        foreach ($received_jobs as $key => $job) {
            /**
             * clean the data remove jobs that is already been sent to google
             */
            // $job_exist = GoogleJob::where('url',$job['url'])
            // ->where('title',$job['title'])
            // ->where('location',$job['location'])
            // ->where('company',$job['company'])
            // ->where('experience',$job['experience'])
            // ->where('employment_type',$job['employmentType'])
            // ->first();

            // $job_exist = DB::select("SELECT title, url FROM google_jobs
            //     WHERE title = \"{$job['title']}\"
            //     AND location = \"{$job['location']}\"
            //     AND company = \"{$job['company']}\"
            //     AND experience = \"{$job['experience']}\"
            //     AND employment_type = \"{$job['employment_type']}\" LIMIT 1");

            $query_params = [];

            $job_exist_query = "SELECT `title`, `url`, `company`, `location`, `experience`, `employment_type` FROM `google_jobs` WHERE";

            if(array_key_exists('title', $job)){
                $job_exist_query .= " `title` = ?";
                $query_params[] = $job['title'];
            }

            if(array_key_exists('url', $job)){
                $job_exist_query .= " AND `url` = ?";
                $query_params[] = $job['url'];
            }

            if(array_key_exists('company', $job)){
                $job_exist_query .= " AND `company` = ?";
                $query_params[] = $job['company'];
            }

            if(array_key_exists('location', $job)){
                $job_exist_query .= " AND `location` = ?";
                $query_params[] = $job['location'];
            }

            if(array_key_exists('experience', $job)){
                $job_exist_query .= " AND `experience` = ?";
                $query_params[] = $job['experience'];
            }

            if(array_key_exists('employmentType', $job)){
                $job_exist_query .= " AND `employment_type` = ?";
                $query_params[] = $job['employmentType'];
            }

            $job_exist_query .= " LIMIT 1";
            // return [$job_exist_query,$query_params];
            $job_exist = DB::select($job_exist_query, $query_params);
            // return $job_exist;

            // $title = $job['title'] ? $job['title'] : '';
            // $url = $job['url'] ? $job['url'] : '';

            // $company = array_key_exists('company', $job) ? $job['company'] : '';
            // $location = array_key_exists('location',$job) ? $job['location'] : '';
            // $experience = array_key_exists('experience',$job) ? $job['experience'] : '';
            // $employmentType = array_key_exists('employmentType',$job) ? $job['employmentType'] : '';

            // $job_exist = DB::select("SELECT `title`, `url`, `company`, `location`, `experience`, `employment_type` FROM `google_jobs`
            //     WHERE `title` = ? AND `url` = ?
            //     AND (CASE WHEN `company` IS NOT NULL THEN `company` = ? END)
            //     AND (CASE WHEN `location` IS NOT NULL THEN `location` = ? END)
            //     AND (CASE WHEN `experience` IS NOT NULL THEN `experience` = ? END)
            //     AND (CASE WHEN `employment_type` IS NOT NULL THEN `employment_type` = ? END) LIMIT 1",[
            //         $title,
            //         $url,
            //         $company,
            //         $location,
            //         $experience,
            //         $employmentType
            //     ]);

            // if job is not listed on the table append to array
            if(!$job_exist){
                $jobs_only_url[] = [ 'url' => $job['url'] ];
                $jobs[] = [
                    'title' => $job['title'],
                    'url' => $job['url'],
                    'date' => date("Y-m-d",strtotime($job['date'])),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_indexed' => 1
                ];
            }
        }
        // return $jobs;
        $cap = 200; // capped jobs Jeff testing endpoint
        // get the remaining slots on the daily cap
        $available_slots_on_daily_cap =  $cap - count($jobs_only_url);
        $unindexed_jobs = [];
        if($available_slots_on_daily_cap >= 1){
            // get the unindexed jobs from yesterday or in the past few days limit by the $available_slots_on_daily_cap
            $unindexed_jobs = GoogleJob::select('url')->where('is_indexed',0)->limit($available_slots_on_daily_cap)->get()->toArray();
            // merged the two arrays
            $jobs_only_url = array_merge($jobs_only_url, $unindexed_jobs);
        }

        $__jobs = [
            'cap_jobs' => array_slice($jobs_only_url,0,$cap),
            'indexed_jobs'=> array_slice($jobs,0,$cap),
            'unindexed_jobs' => array_slice($jobs,$cap,count($jobs))
        ];
        return $__jobs;

        $_chunk = 100;
        // return array_chunk($__jobs['cap_jobs'],$_chunk);
        $_chunk_count = count(array_chunk($__jobs['cap_jobs'],$_chunk));
        $responses = [];
        $save_response = [];

        // maximum loop x2
        for($i = 0; $i < $_chunk_count; ++$i){
            $sent_jobs_to_google_response = array_chunk($__jobs['cap_jobs'],$_chunk)[$i];

            if(count($__jobs['indexed_jobs'])){
                $save_response = array_chunk($__jobs['indexed_jobs'],$_chunk)[$i];
            }

            $responses[] = [
                'sent_jobs_to_google_response'=>$sent_jobs_to_google_response,
                'save_response'=>$save_response
            ];
        }

        /**
         * bulk saving of extra jobs which is not sent to google yet
         */
        $extra_jobs = [];
        if(count($__jobs['unindexed_jobs'])){
            foreach($__jobs['unindexed_jobs'] as $key => $job){
                $extra_jobs[] = [
                    'title' => $job['title'],
                    'url' => $job['url'],
                    'date' => date("Y-m-d",strtotime($job['date'])),
                    'company' => array_key_exists('company', $job) ? $job['company'] : null,
                    'location' => array_key_exists('location',$job) ? $job['location'] : null,
                    'experience' => array_key_exists('experience',$job) ? $job['experience'] : null,
                    'employment_type' => array_key_exists('employmentType',$job) ? $job['employmentType'] : null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_indexed' => 0
                ];
            }
            $extra_jobs;
        }

        /**
         * update the jobs that is not indexed yet
         * see $unindexed_jobs above
         */
        $__unindexed_jobs = [];
        if(count($unindexed_jobs)){
            foreach($unindexed_jobs as $key => $job){
                $__unindexed_jobs[] = [
                    'url'=>$job['url'],
                    'is_indexed' => 1
                ];
            }
        }

        return response([
            'message' => count($__jobs['cap_jobs'])." new job URLs successfully saved to database, and ".count($__jobs['unindexed_jobs'])."/".count($__jobs['cap_jobs'])." unindexed jobs",
            'responses' => $responses,
            'extra_jobs' => $extra_jobs,
            'unindex_jobs'=> $__unindexed_jobs,
        ],Response::HTTP_OK);

    }

    public function bulkPostingV2($data){

        $received_jobs = $data;

        $raw_jobs = [];
        // $jobs = [];
        foreach($received_jobs as $key => $job){

            $title = $job['title'];
            $url = $job['url'];
            $date = date("Y-m-d",strtotime($job['date']));
            $company = isset($job['company']) ? $job['company'] : null;
            $location = isset($job['company']) ? $job['location'] : null;

            // // create job for every job received
            // CleanReceivedJobsJob::dispatch([
            //     'title' => $title,
            //     'url' => $url,
            //     'date' => $date,
            //     'company' => $company,
            //     'location' => $location,
            // ])->delay(Carbon::now()->addSeconds(100));

            $raw_jobs[] = [
                'title' => $title,
                'url' => $url,
                'date' => $date,
                'company' => $company,
                'location' => $location,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ];

        }

        /**
         * jobs received from jobtensor api
         */
        $a = RawReceivedJobs::insert($raw_jobs); // Raw Jobs received (duplicate jobs inside)
        if($a){
            CleanReceivedJobsBulkJob::dispatch($raw_jobs)->delay(Carbon::now()->addSeconds(10));
        }

        return response([
            'message' => "jobs received from jobtensor api",
            'jobs' => count($raw_jobs)." jobs successfully saved to database",
        ],Response::HTTP_OK);

        // migration script
        // select title, url, company, location, experience, employment_type, date, date_posted, valid_through, created_at, updated_at from google_jobs
        // select id as received_job_id, created_at, updated_at from google_jobs where is_indexed = 1

        // scp -i ~/inqbyte/angelo_key.ppk /home/angelo/raw_google_jobs_data-2021-12-09.sql angelo@139.59.135.119:/home/angelo/
    }
}
