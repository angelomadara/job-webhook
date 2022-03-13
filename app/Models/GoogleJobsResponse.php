<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleJobsResponse extends Model
{
    protected $guarded = [];

    public function data(){
        return $this->belongsTo(ReceivedJob::class);
    }
}
