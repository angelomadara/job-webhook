<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title',2000);
            $table->string('url',2000);
            $table->string('company',500)->nullable();
            $table->string('location',2000)->nullable();
            $table->string('experience')->nullable();
            $table->string('employment_type')->nullable();
            $table->date('date')->nullable();
            $table->date('date_posted')->nullable();
            $table->date('valid_through')->nullable();

            $table->string('street_address')->nullable();
            $table->string('local_address')->nullable();
            $table->string('region_address')->nullable();
            $table->string('postal_code_address')->nullable();
            $table->string('country_address')->nullable();
            $table->string('salary_currency')->nullable();
            $table->string('salary_min_value')->nullable();
            $table->string('salary_max_value')->nullable();
            $table->string('unit_text')->nullable();
            $table->string('direct_apply')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_jobs');
    }
}
