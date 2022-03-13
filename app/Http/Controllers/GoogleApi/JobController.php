<?php

namespace App\Http\Controllers\GoogleApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Models\PostedGoogleJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(){
        // $this->middleware('jwt');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = 15;
        $validator = validator()->make($request->all(), [
            'q' => 'nullable|string',
            'date' => 'nullable|date',
            'is_indexed' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            /**
             * if validation fails return the default job collections
             */
            // return JobResource::collection(GoogleJob::latest()->paginate($paginate));
            return JobResource::collection(PostedGoogleJob::leftJoin('received_jobs','received_jobs.id','posted_google_jobs.received_job_id')
                ->select('received_jobs.*','posted_google_jobs.created_at as date_submitted')
                ->orderBy('posted_google_jobs.created_at','desc')->paginate($paginate));
        }
        /**
         * if the validation is true
         */
        $exploded_string = explode(" ",$request->q);
        $new_string = "";
        foreach($exploded_string as $key => $string){ $new_string .= " {$string}% "; }
        $date = $request->date ? date("Y-m-d",strtotime($request->date)) : "";
        $is_indexed = $request->is_indexed ? $request->is_indexed : "";

        $Jobs = new PostedGoogleJob();

        $Jobs = $Jobs->leftJoin('received_jobs','received_jobs.id','posted_google_jobs.received_job_id')
                    ->select('received_jobs.*','posted_google_jobs.created_at as date_submitted');

        if($string != ""){ $Jobs = $Jobs->where('received_jobs.title','like','%'.$string.'%'); }

        if($date != ""){  $Jobs = $Jobs->whereRaw("date(posted_google_jobs.created_at) = '{$date}'"); }

        // if($is_indexed != ""){  $Jobs = $Jobs->where('is_indexed',$is_indexed); }
        // return $Jobs->toSql();
        // return JobResource::collection($Jobs->latest()->paginate($paginate));
        return JobResource::collection($Jobs->orderBy('posted_google_jobs.created_at','desc')->paginate($paginate));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // job form
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GoogleJob $job)
    {
        $validator = validator()->make($request->all(), [
            'title' => 'required|string',
            'url' => 'required|string',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job = $job->create($request->only(['title','url','date']));
        return response(new JobResource($job),Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoogleJob  $job
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = GoogleJob::where('id',$id)->first();
        return new JobResource($job);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoogleJob  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(GoogleJob $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoogleJob  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoogleJob $job)
    {
        $validator = validator()->make($request->all(),[
            'title' => 'required|string',
            'url' => 'required|string',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job = $job->update($request->all(['title','url','date']));
        return response('Job updated',Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoogleJob  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoogleJob $job)
    {
        $job = $job->delete();
        return response('Job removed',Response::HTTP_NO_CONTENT);
    }
}
