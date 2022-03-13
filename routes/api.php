<?php

use App\Http\Controllers\DashboardWidgetsController;
use App\Http\Controllers\GoogleApi\GoogleApiClientController;
use App\Http\Controllers\JobBulkController;
use App\Http\Controllers\Plotly\GoogleJobsPlotlyController;
use App\Jobs\GetJobsWithCompleteDetailsForGoogleJob;
// use App\Http\Controllers\Plotly\GoogleJobsPlotlyPieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * This api is using the JWT Token see the documentation for more details on how to use/implement
 * JWT Token documentation
 * https://jwt-auth.readthedocs.io/en/develop/auth-guard/
 */

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {

    /**
     * authentication routes
     */
    Route::post('login', 'AuthController@login')->name('api.login'); // only `email` and `password`
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me'); // show information about the logged user
    Route::post('payload','AuthController@payload'); // show payload


    /**
     * Download file from storage
     */
    Route::get('download',DownloadFileController::class);

    Route::group([
        'middleware' => 'jwt'
    ], function ($router) {
        /**
         * job routes
         * you can refactor this one because it is only for google jobs table, you can extent/expand the limitation of this logic and use it to arbeitsgentur jobs
         */
        Route::get('job/search', JobSearchController::class)->name('job.search'); // job search jobs sent on google
        Route::post('job/export', ExportJobController::class)->name('job.export'); // export jobs on the table
        Route::apiResource('job','JobController'); // job api resource
        /**
         * bulk send
         */
        Route::post('job/bulk', [JobBulkController::class,'bulkPosting']); // job bulk posting in google, can refactor also and put it on the google routh group
        /**
         * testing route for jeff- bulk sending
         */
        Route::post('job/bulk/jeff', [JobBulkController::class,'bulkPostingJeffTesting']); // only for testing can be remove in the future
        // Route::post('job/bulk/v2', [JobBulkController::class,'bulkPostingV2']);
        // Route::post('google-api/update-url',[GoogleApiClientController::class,'updateUrl']); // disabled

        /**
         * manual deletion of job posted on google for jobs
         */
        // Route::post('google-api/manual/remove-url',JobManualDeletionController::class);

        // Route::post('google-api/manual/url-status',[GoogleApiClientController::class,'manualNotificationChecker']);

        /**
         * analyze raw data google URL_UPDATED response
         */
        // Route::get('google-api-analyze-url-response',[GoogleApiClientController::class,'analyzeUrlUpdateResponse']); // disabled

        /**
         * check url status
         * example responses: 200, 300, 404, 500 etc
         */
        Route::get('url/status',UrlStatusController::class); // checking url status


        /**
         * dashboard routes
         */
        Route::group(['prefix'=>'dashboard'],function(){
            Route::get('widgets/jobs',[DashboardWidgetsController::class,'getWidgetsJobs']);
        });

        /**
         * get job ld json
         */
        Route::post('job/ld-json',GetJobLDJsonController::class);

        /**
         * google jobs plotly
         */
        Route::group(['prefix'=>'g-plotly','namespace'=>'Plotly'],function(){
            Route::get("past-seven-days-jobs-sent",GoogleJobsPlotlyPastSevenDaysJobSentController::class);
            Route::get("past-seven-weeks-jobs-sent",GoogleJobsPlotlyPastSevenWeeksJobSentController::class);
            Route::get("jobs-total-pie-chart",GoogleJobsPlotlyPieController::class);
        });


        /**
         * remove duplicate jobs on google jobs
         */
        Route::group(['prefix'=>'google-api','namespace'=>'GoogleApi'],function(){
            /**
             * job status checker on google for jobs
             */
            Route::post('url-status',[GoogleApiClientController::class,'getNotification']);
            /**
             * remove duplicate jobs on google for jobs
             */
            Route::post('remove-duplicate-jobs',RemoveDuplicateJobsController::class);

            // manual job trigger
            Route::post('manual-job-trigger',function(){
                GetJobsWithCompleteDetailsForGoogleJob::dispatch(); // manual job trigger if everything fails to send a job in a day you can trigger this url
            });

            Route::post('job/delete_url',[GoogleApiClientController::class,'removeUrl']);

            Route::resource('job','JobController');
        });

        Route::group(['prefix'=>'arbeitsagentur','namespace'=>'ArbeitsagenturApi'],function(){
            Route::get('job/post', SendJobsToArbeitsagenturController::class);
            Route::get('/job/prepare/xml',GenerateArbeitsagenturJobXmlController::class); // test route

            // statistics
            Route::get('statistics',StatistikenController::class);
            Route::get('statistics/download',DownloadController::class);

            Route::resource('job', JobController::class);
        });

    }); // route group jwt

}); // route group api
