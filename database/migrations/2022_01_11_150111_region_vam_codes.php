<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegionVamCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region_vam_codes', function (Blueprint $table) {
            $table->id();
            $table->string('land');
            $table->string('iso_code');
            $table->string('iso_31662_code')->nullable();
            $table->string('region')->comment('state');
            $table->string('capital')->nullable();
            $table->string('vam_code');
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
        Schema::dropIfExists('region_vam_codes');
    }
}
