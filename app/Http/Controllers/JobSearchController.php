<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\GoogleJob;
use Illuminate\Http\Request;

class JobSearchController extends Controller
{
    public function __invoke(GoogleJob $googleJobs,Request $request)
    {
        /**
         * not used anymore see the JobController@index
         */
        $string = $request->string;
        $date = $request->date != "" || $request->date != null ? date("Y-m-d",strtotime($request->date)) : "";

        $jobs = $googleJobs;

        if($string != ""){
            $jobs = $jobs->where('title','like',$string.'%');
        }

        if($date != ""){
            $jobs = $jobs->where('date',$date);
        }

        return JobResource::collection($jobs->paginate(50));
    }
}
