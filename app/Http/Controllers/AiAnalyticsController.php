<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiAnalyticsController extends Controller
{
    public function index()
    {
        return view('modules.ai-analytics-wrapper');
    }
}
