<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt');
    }

    public function index(){
        $jobs = Job::latest()->paginate(20);
        return view('admin/index',[
            'jobs' => $jobs
        ]);
    }
}
