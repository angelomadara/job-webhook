<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoogleApiTest extends TestCase
{
    public function testDeleteUrl(){
        $this->call('post','google-api/job/delete_url');
    }
}
