<?php

namespace App\Console\Commands;

use App\Jobs\GetJobsWithCompleteDetailsForGoogleJob;
use App\Jobs\GoogleJobUrlUpdate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PostJobsToGoogle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postjob:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'post jobs to google';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        GetJobsWithCompleteDetailsForGoogleJob::dispatch()->delay(now()->addSeconds(10));
    }
}
