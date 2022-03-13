<?php

namespace App\Http\Controllers\Plotly;

use App\Http\Controllers\Controller;
use App\Models\GoogleJob;
use App\Models\PostedGoogleJob;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoogleJobsPlotlyPastSevenDaysJobSentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // get the past 7 days from today
        $dates = [];
        for($i=0;$i<7;$i++){
            $dates[] = date('Y-m-d',strtotime('-'.$i.' days'));
        }
        $dates = array_reverse($dates);
        $sentJobs = $receivedJobs = $pendingJobs = $totalSentJobs = $totalPendingJobs = $totalReceived = [];
        foreach($dates as $key => $date){
            $sentJobs[] = PostedGoogleJob::whereRaw("date(created_at) = '{$date}'")->count();
            $pendingJobs[] = DB::select("SELECT count(received_jobs.id) as pendingJobs from `received_jobs`
                                        left join `posted_google_jobs` on `posted_google_jobs`.`received_job_id` = `received_jobs`.`id`
                                        where posted_google_jobs.received_job_id is null
                                        and date(received_jobs.created_at) = ?",[$date])[0]->pendingJobs;
            $receivedJobs[] = ReceivedJob::whereRaw("date(created_at) = '{$date}'")->count();

            // $totalSentJobs[] = GoogleJob::whereRaw("date(updated_at) <= '{$date}'")->where('is_indexed',1)->count();
            // $totalPendingJobs[] = GoogleJob::whereRaw("date(created_at) <= '{$date}'")->where('is_indexed',0)->count();
            // $totalReceived[] = GoogleJob::whereRaw("date(created_at) <= '{$date}'")->count();
            // $totalSentJobs[] = PostedGoogleJob::whereRaw("date(created_at) <= '{$date}'")->count();
            // $totalPendingJobs[] = GoogleJob::whereRaw("date(created_at) <= '{$date}'")->where('is_indexed',0)->count();
            // $totalReceived[] = GoogleJob::whereRaw("date(created_at) <= '{$date}'")->count();
        }

        // return [$totalSentJobs,$sentJobs];
        return response()->json([
            'trace1'=>[
                'x'=>$dates,
                'y'=>$sentJobs,
                'name'=>'Sent Jobs',
                'type'=>'scatter',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            'trace2'=>[
                'x'=>$dates,
                'y'=>$pendingJobs,
                'name'=>'Pending Jobs',
                'type'=>'scatter',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            'trace3'=>[
                'x'=>$dates,
                'y'=>$receivedJobs,
                'name'=>'Received Jobs',
                'type'=>'scatter',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            // 'trace4'=>[
            //     'x'=>$dates,
            //     'y'=>$totalSentJobs,
            //     'name'=>'Total Sent Jobs',
            //     'type'=>'scatter',
            //     "line"=> [
            //         // "shape" => 'spline',
            //         "dash" => 'dot',
            //         "width" => 2
            //     ],
            // ],
            // 'trace5'=>[
            //     'x'=>$dates,
            //     'y'=>$totalPendingJobs,
            //     'name'=>'Total Pending Jobs',
            //     'type'=>'scatter',
            //     "line"=> [
            //         // "shape" => 'spline',
            //         "dash" => 'dot',
            //         "width" => 2
            //     ],
            // ],
            // 'trace6'=>[
            //     'x'=>$dates,
            //     'y'=>$totalReceived,
            //     'name'=>'Total Received Jobs',
            //     'type'=>'scatter',
            //     "line"=> [
            //         // "shape" => 'spline',
            //         "dash" => 'dot',
            //         "width" => 2
            //     ],
            // ],
            'layout'=>[
                'title'=>'Jobs Sent and Received per Day',
            ]
        ]);
    }
}
