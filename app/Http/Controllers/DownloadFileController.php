<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadFileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $file = $request->file;
        $path = $request->path;
        // return $file;
        $file_path = storage_path("app/public/{$path}/{$file}");
        if(file_exists($file_path)){
            // return 'yes';
            return response()->download($file_path,$file);
            // Storage::download($request->file,'lsdjkfl');
        }
        // return 'no';
        // return Storage::download("/app/public/arbeitsagentur/Statistiken/".$request->file);
    }
}
