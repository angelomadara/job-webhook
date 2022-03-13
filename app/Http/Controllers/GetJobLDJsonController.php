<?php

namespace App\Http\Controllers;

use App\Jobs\CrawlJobLdJsonJob;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetJobLDJsonController extends Controller
{
    public function __invoke(Request $request){

        $start = $request->start_date." 00:00:00";
        $end = $request->end_date." 23:59:59";
        $limit = $request->limit;

        $jobs = DB::select("select
            `id`, `title`, `company`, `location`, `ldjson`
        from `received_jobs`
        where `date` between ? and ?
        and `ldjson` is null
        limit ?",[$start,$end,$limit]);

        // return $jobs;
        foreach($jobs as $job){
            // dispatch(new CrawlJobLdJsonJob($job));
            CrawlJobLdJsonJob::dispatch($job)->delay(Carbon::now()->addSeconds(20));
        }

    }
}
