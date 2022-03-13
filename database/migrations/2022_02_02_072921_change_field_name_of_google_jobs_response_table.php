<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldNameOfGoogleJobsResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_jobs_responses', function (Blueprint $table) {
            $table->renameColumn('google_job_id', 'received_job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_jobs_responses', function (Blueprint $table) {
            $table->renameColumn('received_job_id', 'google_job_id');
        });
    }
}
