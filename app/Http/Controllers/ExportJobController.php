<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobExportResource;
use App\Models\GoogleJob;
use Illuminate\Http\Request;

class ExportJobController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'q' => 'nullable|string',
            'date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            /**
             * if validation fails return the default job collections
             */
            return JobExportResource::collection(GoogleJob::latest()->get());
        }

        /**
         * if the validation is true
         */
        $exploded_string = explode(" ",$request->q);
        $new_string = "";
        foreach($exploded_string as $key => $string){ $new_string .= " {$string}% "; }
        $date = $request->date ? date("Y-m-d",strtotime($request->date)) : "";

        $Jobs = new GoogleJob();

        if($string != ""){ $Jobs = $Jobs->where('title','like','%'.$string.'%'); }

        if($date != ""){  $Jobs = $Jobs->where('date',$date); }

        return JobExportResource::collection($Jobs->latest()->get());
    }
}
