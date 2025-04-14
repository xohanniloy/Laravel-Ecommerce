<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardPage(){
        return Inertia::render('Dashboard');
    }
}
