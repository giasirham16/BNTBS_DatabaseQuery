<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index()
    {
        $logActivities = LogActivity::get();
        return view('supervisor.LogActivity')->with('data', $logActivities);
    }
}
