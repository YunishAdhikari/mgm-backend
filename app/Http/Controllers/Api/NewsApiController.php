<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsApiController extends Controller
{

public function index()
{
    $news = News::where('status', 'active')
        ->latest()
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'image' => $item->image,
                'image_url' => $item->image
                    ? asset('uploads/news/' . $item->image)
                    : null,
                'created_at' => $item->created_at,
            ];
        });

    return response()->json([
        'success' => true,
        'news' => $news,
    ]);
}
    // public function index()
    // {
    //     $news = News::where('status', 'active')
    //         ->latest()
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'news' => $news,
    //     ]);
    // }

    public function show($id)
    {
        $news = News::where('status', 'active')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'news' => $news,
        ]);
    }
}