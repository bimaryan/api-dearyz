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
        ]);

        $file = $request->file('image');
        $filePath = $file->store('foto', 'public');

        $imageUrl = asset('storage/' . $filePath);

        $photo = Photo::create([
            'nama' => $request->nama,
            'image' => $filePath,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan foto',
            'photo' => [
                'id' => $photo->id,
                'nama' => $photo->nama,
                'deskripsi' => $photo->deskripsi,
                'image_url' => $imageUrl,
            ],
        ]);
    }

    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'nama' => 'required|string',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:10542',
            'deskripsi' => 'nullable|string',
        ]);

        $photo->nama = $request->input('nama');
        $photo->deskripsi = $request->input('deskripsi');

        if ($request->hasFile('image')) {
            if ($photo->image) {
                Storage::disk('public')->delete($photo->image);
            }

            $file = $request->file('image');
            $photo->image = $file->store('foto', 'public');
        }

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
