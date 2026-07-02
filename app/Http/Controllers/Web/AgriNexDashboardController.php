<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgriNexDashboardController extends Controller
{
    /**
     * Display the AgriNex Dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome-modular-fix', [
            'pageTitle' => 'AgriNex Dashboard - IoT Smart Agriculture System',
            'pageDescription' => 'Real-time monitoring and control for smart irrigation system'
        ]);
    }
}
