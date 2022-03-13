<?php

namespace App\Console\Commands;

use App\Http\Controllers\UrlStatusController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UrlStatusChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the url status of the jobs save in the database';

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
        $UrlStatusController = new UrlStatusController();
        $UrlStatusController->__invoke();
    }
}
