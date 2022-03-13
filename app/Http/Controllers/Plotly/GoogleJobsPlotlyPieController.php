<?php

namespace App\Http\Controllers\Plotly;

use App\Http\Controllers\Controller;
use App\Models\GoogleJob;
use App\Models\PostedGoogleJob;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoogleJobsPlotlyPieController extends Controller
{
    public function __invoke(Request $request)
    {
        // $totalSentJobs = GoogleJob::where('is_indexed',1)->count();
        $totalSentJobs = PostedGoogleJob::count();
        // $totalPendingJobs = ReceivedJob::leftJoin('posted_google_jobs','posted_google_jobs.received_job_id','=','received_jobs.id')
        //     ->whereNull('posted_google_jobs.received_job_id')
        //     ->select('received_jobs.id')
        //     ->count();
        $totalPendingJobs = ReceivedJob::where('valid_through','>=',Carbon::now())->count();

        $totalJobs = number_format($totalSentJobs+$totalPendingJobs,0);

        return response()->json([
            'pie'=> [
                'values' => [$totalSentJobs,$totalPendingJobs],
                'labels' => ['Sent Jobs','Pending Valid Jobs'],
                'type' => 'pie',
            ],
            'layout'=>[
                'title' =>"Job Sent vs Pending Jobs",
                // 'height' => 400,
                // 'width' => 500
            ]
        ]);
    }
}
