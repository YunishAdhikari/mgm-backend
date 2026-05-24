<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function create()
    {
        return view('dashboard.admin.news.add');
    }

    public function store(Request $request)
    {
       $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/news'), $imageName);
    }

    News::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title) . '-' . time(),
        'description' => $request->description,
        'image' => $imageName,
        'status' => 'active',
    ]);

    return redirect()->route('admin.news.index')->with('success', 'News added successfully.');
    }

    public function index()
{
    $news = News::select('id', 'title', 'slug', 'image', 'description', 'status', 'created_at')
                ->latest()
                ->get();

    return view('dashboard.admin.News.index', compact('news'));
}
//     public function index()
// {
//     $news = News::latest()->get();

//     return view('dashboard.admin.news.index', compact('news'));
// }

// public function changeStatus(News $news)
// {
//     $news->status = $news->status === 'active' ? 'inactive' : 'active';
//     $news->save();

//     return redirect()->back()->with('success', 'News status updated successfully.');
// }
public function changeStatus($id)
{
    $news = News::findOrFail($id);

    $news->status = $news->status == 'active'
        ? 'inactive'
        : 'active';

    $news->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}
}