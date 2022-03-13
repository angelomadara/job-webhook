<?php

namespace App\Http\Controllers\ArbeitsagenturApi;

use App\Http\Controllers\Controller;
use DOMDocument;
use Illuminate\Http\Request;

class StatistikenController extends Controller
{

    public function __invoke()
    {
        $key = storage_path("key/Zertifikat-268fb5.pem");
        // $password = ":".env('ARBEITSAGENTUR_PEM_PASS');
        $password = "";

        $response = shell_exec("curl --cert {$key}{$password} https://hrbaxml.arbeitsagentur.de/Statistiken/");

        // get all <a> tags in $response
        $doc = new DOMDocument();
        $doc->loadHTML($response);

        $file = [];
        $max_file_count = 50;
        $file_count = 0;
        foreach($doc->getElementsByTagName('a') as $key => $value){
            if(str_ends_with($value->getAttribute('href'),'xlsx')){
                if($max_file_count == $file_count) break;
                $file[] =  $value->getAttribute('href');
                $file_count++;
            }
        }

        return $file;
    }
}
