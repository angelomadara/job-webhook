<?php

namespace App\Http\Controllers\ArbeitsagenturApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function __construct(){
        // $this->middleware('jwt');
    }

    public function __invoke(Request $request)
    {
        // $request->file;
        $validator = validator()->make($request->all(), [
            'file' => 'required|string',
        ]);

        if ($validator->fails()) {
            return "Invalid file name";
        }

        $key = storage_path("key/Zertifikat-268fb5.pem");
        // $password = ":".env('ARBEITSAGENTUR_PEM_PASS');
        $password = "";

        /**
         * create Statistics folder if not exists
         */
        Storage::disk('local')->makeDirectory('public/arbeitsagentur/Statistiken');

        $destination_folder = storage_path("app/public/arbeitsagentur");
        $destination_file = $destination_folder."/".$request->file;

        $response = shell_exec("curl --cert {$key}{$password} -o {$destination_file} https://hrbaxml.arbeitsagentur.de/{$request->file}");

        return [

        ];
    }
}
