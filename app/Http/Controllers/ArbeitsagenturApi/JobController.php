<?php

namespace App\Http\Controllers\ArbeitsagenturApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Jobs\CheckUrlStatusJob;
use App\Models\PostedArbeitsagenturJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(){
        $this->middleware('jwt');
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
        ]);

        if ($validator->fails()) {
            return JobResource::collection(PostedArbeitsagenturJob::leftJoin('received_jobs','received_jobs.id','posted_arbeitsagentur_jobs.received_job_id')
                ->select('received_jobs.*','posted_arbeitsagentur_jobs.created_at as date_submitted')
                ->orderBy('posted_arbeitsagentur_jobs.created_at','desc')->paginate($paginate));
        }

        /**
         * if the validation is true
         */
        $exploded_string = explode(" ",$request->q);
        $new_string = "";
        foreach($exploded_string as $key => $string){ $new_string .= " {$string}% "; }
        $date = $request->date ? date("Y-m-d",strtotime($request->date)) : "";

        $Jobs = new PostedArbeitsagenturJob();

        $Jobs = $Jobs->leftJoin('received_jobs','received_jobs.id','posted_arbeitsagentur_jobs.received_job_id')
                    ->select('received_jobs.*','posted_arbeitsagentur_jobs.created_at as date_submitted');

        if($string != ""){ $Jobs = $Jobs->where('received_jobs.title','like','%'.$string.'%'); }

        if($date != ""){  $Jobs = $Jobs->whereRaw("date(posted_arbeitsagentur_jobs.created_at) = '{$date}'"); }

        return JobResource::collection($Jobs->orderBy('posted_arbeitsagentur_jobs.created_at','desc')->paginate($paginate));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
