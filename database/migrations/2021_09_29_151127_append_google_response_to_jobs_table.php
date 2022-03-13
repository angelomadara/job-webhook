<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendGoogleResponseToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_jobs', function (Blueprint $table) {
            $table->text('update_url_response')->after('is_indexed')->nullable();
            $table->text('delete_url_response')->after('update_url_response')->nullable();
            $table->text('status_url_response')->after('delete_url_response')->nullable();
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
                'update_url_response',
                'delete_url_response',
                'status_url_response',
            ]);
        });
    }
}
