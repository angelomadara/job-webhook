<?php

namespace App\Http\Controllers\GoogleApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RemoveDuplicateJobsController extends Controller
{
    public function __invoke(Request $request)
    {
        $duplicated = \DB::table('google_jobs')
            ->select(
                'title',
                'url',
                'company',
                'location',
                'experience',
                'employment_type',
                \DB::raw('count(*) as occurrences')
            )
            ->where('is_indexed',0)
            ->groupBy('title','url','company','location','experience','employment_type')
            ->having('occurrences', '>', 1)
            ->get();

        $job = [];
        $total_dupes = 0;
        foreach($duplicated as $job){
            $total_dupes += $job->occurrences;
            $dupe_jobs = \DB::table('google_jobs')
                ->select(
                    'id',
                    // 'title',
                    // 'url',
                    // 'company',
                    // 'location',
                    // 'experience',
                    // 'employment_type',
                    // 'is_indexed'
                )
                ->where('title',$job->title)
                ->where('url',$job->url)
                ->where('company',$job->company)
                ->where('location',$job->location)
                ->where('experience',$job->experience)
                ->where('employment_type',$job->employment_type)
                ->get()->toArray();

            $dupes = array_slice($dupe_jobs,1);

            foreach($dupes as $dupe){
                $dupe_id = $dupe->id;
                \DB::table('google_jobs')->where('id',$dupe_id)->delete();
            }

        }

        return response()->json([
            'total_dupes' => $total_dupes,
            'status' => 'success',
            'message' => 'Duplicates removed'
        ]);
    }
}
