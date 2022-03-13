<?php

namespace App\Http\Controllers;

use App\Jobs\CheckUrlStatusQueueJob;
use App\Models\GoogleJob;
use App\Models\ReceivedJob;
use App\Models\UrlStatus;
use Illuminate\Http\Request;

class UrlStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $get)
    {
        $start_date = $get->start_date;
        $end_date = $get->end_date;
        $jobs = ReceivedJob::select('id','url')->whereRaw("date(created_at) between '{$start_date}' and '{$end_date}'")->get();

        CheckUrlStatusQueueJob::dispatch($jobs);
    }
}
