<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlStatus extends Model
{
    protected $guarded = [];

    public function job(){
        return $this->hasOne(GoogleJob::class);
    }
}
