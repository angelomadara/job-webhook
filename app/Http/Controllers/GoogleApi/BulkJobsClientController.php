<?php

namespace App\Http\Controllers\GoogleApi;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Http\Batch;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkJobsClientController extends Controller
{
    /**
     * bulk sending of jobs to google api job posting
     * @param data payload in json format
     * @param setType choose between URL_UPDATED or URL_DELETED
     */
    public function post($data,$setType){

        // Log::info(['type'=>$setType,'data'=>$data]);

        //init google client
        $client = new Client();
        $client->setAuthConfig(storage_path()."/key/service_account_file.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $client->setUseBatch(true);

        //init google batch and set root URL
        $batch = new Batch($client, false, 'https://indexing.googleapis.com');

        //init service Notification to sent request
        $postBody = new UrlNotification();

        /**
         * this $data is an array of url to be sent to google api
         *  [
         *      {"url":"https://url-1.com"},
         *      {"url":"https://url-2.com"}
         *  ]
         */

        foreach ($data as $key => $job) {
            $postBody->setType($setType);
            $postBody->setUrl($job['url']);
            //init service Indexing ( like updateJobPosting )
            $service = new Indexing($client);
            //create request
            $return_service = $service->urlNotifications->publish($postBody);
            //add request to batch
            $batch->add($return_service);
        }

        $response = $batch->execute();

        return $response;
    }
}
