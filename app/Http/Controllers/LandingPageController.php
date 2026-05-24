<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceJob;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Department;
use App\Models\Complaints;
use App\Models\News;
// use app\Models BlogPost;

class LandingPageController extends Controller
{
    public function index()
    {
        $activeNews = News::where('status', 'active')
        ->latest()
        ->take(3)
        ->get();


        $totalUsers = User::count();
        $totalDepartments = Department::count();
        // $totalComplaints = Complaints::count();
            // $activeComplaints = Complaints::where('status', '!=', 'resolved')->count();
            $activemaintanance = MaintenanceJob::where('status', '!=', 'resolved')->count();

        // $latestPosts = class_exists(\App\Models\BlogPost::class)
        //     ? BlogPost::latest()->take(3)->get()
        //     : collect();

        return view('LandingPage.landing', compact(
            'activeNews',
            'totalUsers',
            'totalDepartments',
            // 'totalComplaints',
            'activemaintanance',
            // 'activeComplaints'
            // 'latestPosts'
        ));
    }
}
