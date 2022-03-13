<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleJobsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_jobs_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('google_job_id')->nullable();
            $table->string('type')->nullable();
            $table->text('response')->nullable();
            $table->dateTime('notify_time')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('google_job_id')->references('id')->on('google_jobs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_jobs_responses');
    }
}
