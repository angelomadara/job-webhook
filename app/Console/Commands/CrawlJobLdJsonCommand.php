<?php

namespace App\Console\Commands;

use App\Models\ReceivedJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrawlJobLdJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:ldjson {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the ld+json of the job';

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
        $job = $this->argument('data');

        try {

            // Log::info($job);

            /**
             * get the ld+json
             */
            // $result = DB::connection('mysql_crawler')->table('indeed_jobs')
            //     ->where('title',$job['title'])
            //     ->where('company_cluster',$job['company'])
            //     ->where('city',$job['location'])
            //     ->whereNull('deleted_at')
            //     ->orderBy('id','desc')
            //     ->limit(1)
            //     ->toSql();

            // Log::info($result);

            // return json_decode($result[0]->json_tld_data, true);

            // $out = file_get_contents($job['url']);
            // $start = "<script type=\"application/ld+json\">";
            // $end = "</script>";
            // $startsAt = strpos($out, $start) + strlen($start);
            // $endsAt = strpos($out, $end, $startsAt);
            // $result = substr($out, $startsAt, $endsAt - $startsAt);

            // $query = "SELECT json_tld_data FROM indeed_jobs WHERE title = ?
            // AND company_cluster = ? AND city = ?
            // AND deleted_at IS NULL AND json_tld_data IS NULL
            // ORDER BY id DESC LIMIT 1";

            // $result = DB::connection('mysql_crawler')->select($query,[]);

            // $ldjson = json_decode($result[0]->json_tld_data, true);

            // ReceivedJob::where('id',$job['id'])->update([
            //     'company' => $ldjson['hiringOrganization']['name'],
            //     'employment_type' => $ldjson['employmentType'],
            //     'date_posted' =>  $ldjson['datePosted'],
            //     'valid_through' => $ldjson['validThrough'],
            //     'street_address' => $ldjson['jobLocation']['address']['streetAddress'],
            //     'local_address' => $ldjson['jobLocation']['address']['addressLocality'],
            //     'region_address' => $ldjson['jobLocation']['address']['addressRegion'],
            //     'postal_code_address' => $ldjson['jobLocation']['address']['postalCode'],
            //     'country_address' => $ldjson['jobLocation']['address']['addressCountry'],
            //     'salary_currency' => $ldjson['baseSalary']['currency'],
            //     'salary_min_value' => $ldjson['baseSalary']['value']['minValue'],
            //     'salary_max_value' => $ldjson['baseSalary']['value']['maxValue'],
            //     'unit_text' => $ldjson['baseSalary']['value']['unitText'],
            //     'direct_apply' => $ldjson['directApply'],
            //     'description' => $ldjson['description'],
            //     'ldjson' => $ldjson,
            //     'url_status' => 'HTTP/1.1 200 OK'
            // ]);

        } catch (\Exception $th) {
            // $headers = @get_headers($job['url']);
            // ReceivedJob::where('id',$job['id'])->update(['url_status' => $headers[0]]);
            // Log::ino("Error: " . $th->getMessage());
        }

    }
}
