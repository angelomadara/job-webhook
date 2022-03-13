<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendNewFiltersToGoogleJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_jobs', function (Blueprint $table) {
            $table->string('company')->after('url')->nullable();
            $table->string('location')->after('company')->nullable();
            $table->string('experience')->after('location')->nullable();
            $table->string('employment_type')->after('experience')->nullable();
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
            $table->dropColumn([
                'company',
                'location',
                'experience',
                'employment_type',
            ]);
        });
    }
}
