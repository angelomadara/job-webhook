<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseTheSizeOfUrlAndOtherFieldsOnGoogleJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_jobs', function (Blueprint $table) {
            $table->string('url', 2000)->change();
            $table->string('title', 2000)->change();
            $table->string('location', 2000)->change();
            $table->string('company', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_jobs', function (Blueprint $table) {
            $table->string('url', 255)->change();
            $table->string('title', 255)->change();
            $table->string('location', 255)->change();
            $table->string('company', 255)->change();
        });
    }
}
