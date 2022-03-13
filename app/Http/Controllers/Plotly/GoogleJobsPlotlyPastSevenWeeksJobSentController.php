<?php

namespace App\Http\Controllers\Plotly;

use App\Http\Controllers\Controller;
use App\Models\GoogleJob;
use App\Models\PostedGoogleJob;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoogleJobsPlotlyPastSevenWeeksJobSentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // get the past 7 weeks from today

        $weekNumber = Carbon::now()->weekOfYear; // get the week number of the year
        $year = date("Y");
        $dates = [];

        for($i=( $weekNumber - 6);$i<=$weekNumber;$i++){
            $date = Carbon::now();
            $date->setISODate($year,$i);
            $dates[] = [
                date("Y-m-d",strtotime($date)),
                date("Y-m-d",strtotime($date."+6 days"))
            ];
        }

        // $dates = array_reverse($dates);
        $x_dates = $sentJobs = $pendingJobs = $receivedJobs = $totalSentJobs = $totalPendingJobs = $totalReceivedJobs = [];

        foreach($dates as $key=>$date){
            $sentJobs[] = PostedGoogleJob::whereBetween('updated_at',[$date[0],$date[1]])->count();
            // $pendingJobs[] = GoogleJob::whereBetween('created_at',[$date[0],$date[1]])->where('is_indexed',0)->count();
            $receivedJobs[] = ReceivedJob::whereBetween('created_at',[$date[0],$date[1]])->count();

            $totalSentJobs[] = PostedGoogleJob::whereRaw("date(updated_at) <= '{$date[1]}'")->count();
            // $totalPendingJobs[] = GoogleJob::whereRaw("date(created_at) <= '{$date[1]}'")->where('is_indexed',0)->count();
            $totalReceivedJobs[] = ReceivedJob::whereRaw("date(created_at) <= '{$date[1]}'")->count();

            $x_dates[] = $date[0]."<br>".$date[1];
        }

        return response()->json([
            'trace1'=>[
                'x'=>$x_dates,
                'y'=>$receivedJobs,
                'name'=>'Received',
                'type'=>'bar',
                'mode'=>'lines+markers',
                'text'=>'',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            'trace2' => [
                'x'=>$x_dates,
                'y'=>$sentJobs,
                'name'=>'Sent',
                'type'=>'bar',
                'mode'=>'lines+markers',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            'trace3'=>[
                'x'=>$x_dates,
                'y'=>$pendingJobs,
                'name'=>'Pending',
                'type'=>'bar',
                'mode'=>'lines+markers',
                "line"=> [
                    // "shape" => 'spline'
                ],
            ],
            'trace4'=>[
                'x'=>$x_dates,
                'y'=>$totalSentJobs,
                'name'=>'Total Sent',
                'type'=>'scatter',
                'mode'=>'lines+markers',
                "line"=> [
                    // "shape" => 'spline',
                    "dash" => 'dot',
                    "width" => 2
                ],
            ],
            'trace5'=>[
                'x'=>$x_dates,
                'y'=>$totalPendingJobs,
                'name'=>'Total Pending',
                'type'=>'scatter',
                'mode'=>'lines+markers',
                "line"=> [
                    // "shape" => 'spline',
                    "dash" => 'dot',
                    "width" => 2
                ],
            ],
            'trace6'=>[
                'x'=>$x_dates,
                'y'=>$totalReceivedJobs,
                'name'=>'Total Received',
                'type'=>'scatter',
                'mode'=>'lines+markers',
                "line"=> [
                    // "shape" => 'spline',
                    "dash" => 'dot',
                    "width" => 2
                ],
            ],
            'layout'=>[
                'title'=>'Jobs Sent and Received per Week',
            ]
        ]);
    }
}
