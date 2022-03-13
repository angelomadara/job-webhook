<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendNewFieldsOnReceivedJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('received_jobs', function (Blueprint $table) {
            $table->text("description")->after('direct_apply')->nullable();
            $table->string('url_status')->after('description')->nullable();
            $table->text('ldjson')->after('url_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('received_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'url_status',
                'ldjson'
            ]);
        });
    }
}
