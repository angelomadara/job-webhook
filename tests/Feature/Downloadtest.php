<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class Downloadtest extends TestCase
{
    public function testArbeitsagenturDownloadFile(){
        $download = new \App\Http\Controllers\ArbeitsagenturApi\DownloadController();

        print_r($download->__invoke(new Request(['file'=>'Statistiken/Arbeitsagentur-Statistik-2020-01-01.xlsx'])));
    }
}
