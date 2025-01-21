<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $photoCount = Photo::count();
        $videoCount = Video::count();

        return response()->json([
            'user' => $user,
            'photo' => $photoCount,
            'video' => $videoCount,
        ]);
    }
}
