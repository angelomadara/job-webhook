<?php

namespace App\Jobs;

use App\Models\ReceivedJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckUrlStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job = $this->data;
        if($job){
            $headers = get_headers($job->url);
            // Log::info(json_encode([
            //     'id' => $job->id,
            //      'url' => $job->url,
            //      'status' => $headers[0],
            //  ]));
            ReceivedJob::where('id',$job->id)->update(['url_status'=>$headers[0]]);
        }
    }
}
