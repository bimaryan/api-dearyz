<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'video' => 'required|mimes:mp4,mov|max:10542',
            'deskripsi' => 'nullable|string',
        ]);

        $file = $request->file('video');
        $filePath = $file->store('video', 'public');

        Video::create([
            'nama' => $request->nama,
            'video' => $filePath,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan video',
        ]);
    }
}
