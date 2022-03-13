<?php

namespace App\Http\Controllers\ArbeitsagenturApi;

use App\Http\Controllers\Controller;
use App\Jobs\GetJobsWithCompleteDetailsForArbeitsagenturJob;
use App\Models\ReceivedJob;
use Carbon\Carbon;
use FluidXml\FluidXml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SoapClient;

class SendJobsToArbeitsagenturController extends Controller
{
    public function __invoke(Request $get)
    {
        GetJobsWithCompleteDetailsForArbeitsagenturJob::dispatch();
    }
}
