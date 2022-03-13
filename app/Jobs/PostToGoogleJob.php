<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PostToGoogleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($_data)
    {
        $this->data = $_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Artisan::call('postjob:google',[
        //     'data' => $this->data
        // ]);
        // Log::info($this->data);
    }
}
