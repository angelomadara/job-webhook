<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivedJob extends Model
{
    protected $guarded = [];

    public function details(){
        return $this->hasOne(PostedGoogleJob::class);
    }

    public function metaData(){
        return $this->hasMany(GoogleJobsResponse::class);
    }
}
