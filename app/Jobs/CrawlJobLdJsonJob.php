<?php

namespace App\Jobs;

use App\Models\ReceivedJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrawlJobLdJsonJob implements ShouldQueue
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
        try {
            $a = $this->data;
            // get the ldjson from the keywords_crawler database
            $result = DB::connection('mysql_crawler')->table('indeed_jobs')
                ->where('title',$a->title)
                ->where('company_cluster',$a->company)
                ->where('city',$a->location)
                ->whereNull('deleted_at')
                ->orderBy('id','desc')
                ->limit(1)
                ->get();

            $ldjson = json_decode($result[0]->json_tld_data, true);

            ReceivedJob::where('id',$a->id)->update([
                'company' => $ldjson['hiringOrganization']['name'],
                'employment_type' => $ldjson['employmentType'],
                'date_posted' =>  $ldjson['datePosted'],
                'valid_through' => $ldjson['validThrough'],
                'street_address' => $ldjson['jobLocation']['address']['streetAddress'],
                'local_address' => $ldjson['jobLocation']['address']['addressLocality'],
                'region_address' => $ldjson['jobLocation']['address']['addressRegion'],
                'postal_code_address' => $ldjson['jobLocation']['address']['postalCode'],
                'country_address' => $ldjson['jobLocation']['address']['addressCountry'],
                'salary_currency' => $ldjson['baseSalary']['currency'],
                'salary_min_value' => $ldjson['baseSalary']['value']['minValue'],
                'salary_max_value' => $ldjson['baseSalary']['value']['maxValue'],
                'unit_text' => $ldjson['baseSalary']['value']['unitText'],
                'direct_apply' => $ldjson['directApply'],
                'description' => $ldjson['description'],
                'ldjson' => $ldjson,
            ]);

        } catch (\Exception $th) {}
    }
}
