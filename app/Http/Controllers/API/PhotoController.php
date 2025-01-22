<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    private $spotifyClientId = "db4b41d16d6a4975842f1201a1205091";
    private $spotifyClientSecret = "8570bd1325694349af68ecb6d3594ad6";
    private $spotifyTokenUrl = "https://accounts.spotify.com/api/token";
    private $spotifySearchUrl = "https://api.spotify.com/v1/search";

    private function getSpotifyToken()
    {
        $response = Http::asForm()->post($this->spotifyTokenUrl, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->spotifyClientId,
            'client_secret' => $this->spotifyClientSecret,
        ]);

        return $response->json()['access_token'];
    }

    private function fetchSpotifyTrack($query)
    {
        $token = $this->getSpotifyToken();

        $response = Http::withToken($token)
            ->get($this->spotifySearchUrl, [
                'q' => $query,
                'type' => 'track',
                'limit' => 1
            ]);

        return $response->json();
    }

    public function index()
    {
        $photos = Photo::paginate(5);

        $photos->getCollection()->transform(function ($photo) {
            $photo->image_url = asset('storage/' . $photo->image);
            return $photo;
        });

        return response()->json([
            'photos' => $photos->items(),
            'current_page' => $photos->currentPage(),
            'total_pages' => $photos->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg|max:10542',
            'deskripsi' => 'nullable|string',
            'spotify_track' => 'nullable|string',
        ]);

        $spotifyTrack = null;
        if ($request->spotify_track) {
            $spotifyTrack = $this->fetchSpotifyTrack($request->spotify_track);
        }

        $file = $request->file('image');
        $filePath = $file->store('foto', 'public');

        $imageUrl = asset('storage/' . $filePath);

        $photo = Photo::create([
            'nama' => $request->nama,
            'image' => $filePath,
            'deskripsi' => $request->deskripsi,
            'spotify_track_id' => $spotifyTrack['tracks']['items'][0]['id'] ?? null,
            'spotify_track_name' => $spotifyTrack['tracks']['items'][0]['name'] ?? null,
            'spotify_track_url' => $spotifyTrack['tracks']['items'][0]['external_urls']['spotify'] ?? null,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan foto',
            'photo' => [
                'id' => $photo->id,
                'nama' => $photo->nama,
                'deskripsi' => $photo->deskripsi,
                'image_url' => $imageUrl,
                'spotify_track_name' => $photo->spotify_track_name,
                'spotify_track_url' => $photo->spotify_track_url,
            ],
        ]);
    }

    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'nama' => 'required|string',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:10542',
            'deskripsi' => 'nullable|string',
            'spotify_track' => 'nullable|string',
        ]);

        $spotifyTrack = null;
        if ($request->spotify_track) {
            $spotifyTrack = $this->fetchSpotifyTrack($request->spotify_track);
        }

        $photo->nama = $request->input('nama');
        $photo->deskripsi = $request->input('deskripsi');

        if ($request->hasFile('image')) {
            if ($photo->image) {
                Storage::disk('public')->delete($photo->image);
            }

            $file = $request->file('image');
            $photo->image = $file->store('foto', 'public');
        }

        $photo->spotify_track_id = $spotifyTrack['tracks']['items'][0]['id'] ?? $photo->spotify_track_id;
        $photo->spotify_track_name = $spotifyTrack['tracks']['items'][0]['name'] ?? $photo->spotify_track_name;
        $photo->spotify_track_url = $spotifyTrack['tracks']['items'][0]['external_urls']['spotify'] ?? $photo->spotify_track_url;

        $photo->save();

        return response()->json([
            'message' => 'Photo updated successfully.',
            'photo' => $photo,
        ]);
    }

    public function show($allphoto)
    {
        $photo = Photo::where('nama', $allphoto)->first();

        if (!$photo) {
            return response()->json([
                'message' => 'Photo not found',
            ], 404);
        }

        return response()->json([
            'photo' => [
                'nama' => $photo->nama,
                'deskripsi' => $photo->deskripsi,
                'image_url' => asset('storage/' . $photo->image),
                'spotify_track_name' => $photo->spotify_track_name,
                'spotify_track_url' => $photo->spotify_track_url,
            ],
        ]);
    }

    public function destroy(Photo $photo)
    {
        try {
            if ($photo->image && Storage::exists('public/' . $photo->image)) {
                Storage::delete('public/' . $photo->image);
            }

            $photo->delete();

            return response()->json([
                'message' => 'Photo deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete photo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
