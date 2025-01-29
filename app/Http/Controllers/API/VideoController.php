<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::paginate(6);

        $videos->getCollection()->transform(function ($video) {
            $video->video_url = asset('storage/' . $video->video);
            return $video;
        });

        return response()->json([
            'videos' => $videos->items(),
            'current_page' => $videos->currentPage(),
            'total_pages' => $videos->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'video' => 'required|mimes:mp4,mov|max:10542',
            'deskripsi' => 'nullable|string',
        ]);

        $file = $request->file('video');
        $filePath = $file->store('video', 'public');

        $videoUrl = asset('storage/' . $filePath);

        $video = Video::create([
            'nama' => $request->nama,
            'video' => $filePath,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan video',
            'video' => [
                'id' => $video->id,
                'nama' => $video->nama,
                'video_url' => $videoUrl,
            ]
        ]);
    }
}
