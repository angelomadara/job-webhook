<?php

namespace App\Http\Controllers\GoogleApi;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteSentJobsToGoogleJob;
use App\Models\GoogleJob;
use App\Models\GoogleJobsResponse;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Google\Client;
use Google\Http\Batch;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;
use Google\Service\ServiceUsage\GoogleApiService;
use Google_Client;
use Google_Http_Batch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GoogleApiClientController extends Controller
{
    public function index(){}

    /**
     * Update a URL
     * To notify Google of a new URL to crawl or that content at a previously-submitted URL has been updated
     */
    public function updateUrl($job){
        return 'false';
        // $validator = validator()->make($request->all(), [
        //     'url' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // return $request->get();

        $client = new Google_Client();

        $client->setAuthConfig(storage_path()."/key/service_account_file.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');

        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';


        /**
         * to avoid reponse 400
         * $content must be in a form of string
         * and the value of the json must a in a form of string also
         */
        $content = "{
            'url': '{$job}',
            'type': 'URL_UPDATED'
        }";

        $response = $httpClient->post($endpoint, [ 'body' => $content ]);
        // return $response;
        if($response->getStatusCode() == 200){
            return GoogleJob::where('id',$request->id)->update([
                'update_url_response' => $response
            ]);
        }
    }

    /**
     * Remove a URL
     * notify Google to remove the URL from the index
     * Note: Before requesting a removal, URL must return 404 or 410 status code or the page contains <meta name="robots" content="noindex" /> meta tag.
     */
    public function removeUrl(Request $request){
        $validator = validator()->make($request->all(), [
            'date' => 'required',
        ]);
        return get_headers("https://jobtensor.com/job/Product-Owner-mwd-Webshop-76c6e77b9e7f");
        // DeleteSentJobsToGoogleJob::dispatch($request->date);
    }

    /**
     * Get notification status
     * You can use the Indexing API to check the last time Google received each kind of notification for a given URL.
     * The request doesn't tell you when Google indexes or removes a URL; it only returns whether you successfully submitted a request.
     */
    public function getNotification(Request $request){
        // return $request->all();
        $validator = validator()->make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            /**
             * if validation fails return the default job collections
             */
            return response()->json($validator->errors(), 422);
        }

        $job =  ReceivedJob::where('id',$request->id)->with('metaData')->first(); // get the job
        // $date_less_than_30_days =  strtotime(date("Y-m-d",strtotime("-30 day"))); // get the last 30 days date

        /**
         * stop if the job is not yet indexed
         */
        // if($job->is_indexed == 0){
        //     return response()->json([
        //         'job' => $job,
        //         'response' => [
        //             'status' => 'not_indexed',
        //             'message' => 'The URL is not indexed yet.',
        //             'created_at' => null,
        //             'notify_time' => null,
        //             'response' => json_encode([null]),
        //             'type' => "NOT YET INDEXED",
        //             'updated_at' => null
        //         ],
        //         'type' => "NOT YET INDEXED",
        //     ],200);
        // }

        /**
         * if the job metaData is not empty and the job date is not 30 days ago
         */
        // if(count($job->metaData) >= 1){
        //     $metaData = array_reverse($job->metaData->toArray());
        //     return [
        //         'type' => $metaData[0]['type'],
        //         'response' => $metaData,
        //     ];
        // }
        /**
         * google script to get url notification
         */
        $client = new Google_Client();
        $client->setAuthConfig(storage_path()."/key/service_account_file.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications/metadata?url='.urlencode($job->url);
        $response = $httpClient->get($endpoint);
        // end of google script

        // get the response body
        $body = json_decode($response->getBody(),true);

        if($response){
            $type = "";
            $tbl_response = [];

            if(array_key_exists('latestUpdate',$body)){
                // if the notification is update
                $tbl_response = GoogleJobsResponse::create([
                    'received_job_id' => $job->id,
                    'type' => $body['latestUpdate']['type'],
                    'notify_time' => str_replace("T"," ",explode('.',$body["latestUpdate"]['notifyTime'])[0]),
                    'response' => json_encode($body['latestUpdate']),
                ]);
                $type = $body['latestUpdate']['type'];
            }

            if(array_key_exists('latestRemove',$body)){
                // if the notification is delete
                $tbl_response = GoogleJobsResponse::create([
                    'received_job_id' => $job->id,
                    'type' => $body['latestRemove']['type'],
                    'notify_time' => str_replace("T"," ",explode('.',$body["latestRemove"]['notifyTime'])[0]),
                    'response' => json_encode($body['latestRemove']),
                ]);
                $type = $body['latestRemove']['type'];
            }

            return [
                'job' => ReceivedJob::where('id',$request->id)->with('metaData')->first(),
                'type' => $type,
                'response' => json_decode($tbl_response)
            ];
        }

        return response("There was a problem while getting the updates for this `URL`. You can try again later if this message appears again.",Response::HTTP_PARTIAL_CONTENT);
    }

    /**
     * bulk sending of jobs to google api job posting
     * @param data payload in json format
     * @param setType choose between URL_UPDATED or URL_DELETED
     */
    public function batchUrlUpdated($data,$setType){

        //init google client
        $client = new Client();
        $client->setAuthConfig(storage_path()."/key/service_account_file.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $client->setUseBatch(true);

        //init google batch and set root URL
        $batch = new Batch($client, false, 'https://indexing.googleapis.com');

        //init service Notification to sent request
        $postBody = new UrlNotification();

        foreach ($data as $key => $job) {
            $postBody->setType($setType);
            $postBody->setUrl($job['url']);

            //init service Indexing ( like updateJobPosting )
            $service = new Indexing($client);
            //create request
            //$service->urlNotifications->createRequestUri('https://indexing.googleapis.com/batch');
            $return_service = $service->urlNotifications->publish($postBody);
            //add request to batch
            $batch->add($return_service);
        }

        $response = $batch->execute();

        return $response;
    }

    /**
     * analyze google URL_UPDATE response
     */
    public function analyzeUrlUpdateResponse(Request $request){



        return 'false';

        /**
         * temporary stopping the process because the response of google is changed from the artisan command
         */

        $payload = $request->responses;
        $jobCount = 0;
        $_jobs = [];
        // return $payload;
        foreach ($payload as $key => $send_jobs_to_google_response_key) {
            $jobCount += count($send_jobs_to_google_response_key['send_jobs_to_google_response']);

            foreach($send_jobs_to_google_response_key['send_jobs_to_google_response'] as $key2 => $metaData){
                $_jobs[] = $metaData;
            }
        }

        $jobs = [];
        foreach ($_jobs as $key => $value) {
            $job = GoogleJob::where('url',$value["urlNotificationMetadata"]['url']);
            $date_time = str_replace("T"," ",explode('.',$value["urlNotificationMetadata"]["latestUpdate"]['notifyTime'])[0]);

            // $jobs[] = [
            //     'id' => $job->first()->id,
            //     'update' => $job->update(['is_indexed'=>1]),
            // ];

            /**
             * update the GoogleJob is_indexed field
             */
            $job->update(['is_indexed'=>1]);
            /**
             * save the response from google
             */
            GoogleJobsResponse::create([
                'google_job_id' => $job->first()->id,
                'type' => $value["urlNotificationMetadata"]["latestUpdate"]['type'],
                'notify_time' => $date_time,
                'response' => json_encode($value)
            ]);
        }

        return [
          'succeeded_job_sent_count' => $jobCount,
        //   'jobs' => $jobs,
        ];

    }
}
