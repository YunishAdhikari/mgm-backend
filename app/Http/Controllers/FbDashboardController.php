<?php

namespace App\Http\Controllers;

class FbDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.fb.index');
    }
}