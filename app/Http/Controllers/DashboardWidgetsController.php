<?php

namespace App\Http\Controllers;

use App\Models\PostedGoogleJob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardWidgetsController extends Controller
{
    public function getWidgetsJobs(){

        $GoogleJobs = new PostedGoogleJob();

        $this_month_date_start = date('Y-m-01');
        $now = date('Y-m-d');

        $weekNumber = Carbon::now()->weekOfYear;
        $date = Carbon::now();
        $date->setISODate(date("Y"),$weekNumber);

        $this_week_date_start = date("Y-m-d",strtotime($date));
        $this_week_date_end = date("Y-m-d",strtotime($date."+6 days"));

        return response()->json([
            'g_jobs_today' => $GoogleJobs->whereRaw("date(created_at) = '{$now}'")->count(),
            'g_jobs_week' => $GoogleJobs->whereRaw("date(created_at) BETWEEN '".$this_week_date_start."' AND '".$this_week_date_end."'")->count(),
            'g_jobs_month' => $GoogleJobs->whereRaw("date(created_at) BETWEEN '".$this_month_date_start."' AND '".date("Y-m-t",strtotime($this_month_date_start))."'")->count(),
            'g_jobs_year' => $GoogleJobs->whereRaw("date(created_at) BETWEEN '".date("Y-01-01")."' AND '".date("Y-12-t")."'")->count(),
        ]);
    }
}
