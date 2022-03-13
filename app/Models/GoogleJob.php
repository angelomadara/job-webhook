<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleJob extends Model
{
    protected $guarded = [];

    public function metaData(){
        return $this->hasMany(GoogleJobsResponse::class);
    }

    public function urlStatus(){
        return $this->hasOne(UrlStatus::class);
    }
}
