<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsOnReceivedJobsForArbeitsagenturSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('received_jobs', function (Blueprint $table) {
            /**
             * job position posting id = <Alliance partner number>-<any unique character string>-S
             * example: 12345-abc-S
             */
            $table->string('job_position_posting_id')->nullable()->after('ldjson')->comment('arbeitsagentur settings');
            /**
             * ID of the supervised employer account in the JOBSTOCK EXCHANGE (Customer number)
             */
            $table->string('hiring_org')->nullable()->after('job_position_posting_id')->comment('arbeitsagentur settings');
            /**
             * PartnerID of the cooperation partner
             */
            $table->string('supplier_id')->nullable()->after('hiring_org')->comment('arbeitsagentur settings');
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
                'job_position_posting_id',
                'hiring_org',
                'supplier_id'
            ]);
        });
    }
}
