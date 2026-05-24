<?php

namespace App\Http\Controllers;

use App\Support\MockData;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('admin.blogs.index', ['posts' => MockData::recentPosts()]);
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:200',
            'category' => 'required|in:News,Blog,Policy,Announcement,Report',
            'body'     => 'required|string|min:10',
            'status'   => 'required|in:Draft,Published',
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('status', 'Post \"' . $request->title . '\" saved as ' . $request->status . '.');
    }
}
