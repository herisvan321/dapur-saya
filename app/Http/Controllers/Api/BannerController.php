<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\UploadsWebpImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use UploadsWebpImage;

    /**
     * List semua banner.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::orderByDesc('created_at')->simplePaginate(25)->through(function ($banner) {
            $banner->image_url = $banner->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $banner->image_url : null;
            return $banner;
        });

        return response()->json([
            'status' => 'success',
            'data' => $banners,
        ]);
    }

    /**
     * Simpan banner baru.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'offer_text' => 'required|string|max:255',
        ]);

        $imageUrl = $this->uploadWebp($request->file('image'), 'banners');

        $banner = Banner::create([
            'image_url' => $imageUrl,
            'offer_text' => $request->offer_text,
        ]);

        $banner->image_url = rtrim(config('app.url'), '/') . '/storage/' . $banner->image_url;

        return response()->json([
            'status' => 'success',
            'message' => 'Banner berhasil ditambahkan.',
            'data' => $banner,
        ], 201);
    }

    /**
     * Detail banner.
     */
    public function show(Banner $banner): JsonResponse
    {
        $banner->image_url = $banner->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $banner->image_url : null;

        return response()->json([
            'status' => 'success',
            'data' => $banner,
        ]);
    }

    /**
     * Update banner.
     */
    public function update(Request $request, Banner $banner): JsonResponse
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'offer_text' => 'sometimes|required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($banner->image_url);
            $banner->image_url = $this->uploadWebp($request->file('image'), 'banners');
        }

        if ($request->has('offer_text')) {
            $banner->offer_text = $request->offer_text;
        }

        $banner->save();
        $banner->image_url = $banner->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $banner->image_url : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Banner berhasil diupdate.',
            'data' => $banner,
        ]);
    }

    /**
     * Hapus banner.
     */
    public function destroy(Banner $banner): JsonResponse
    {
        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Banner berhasil dihapus.',
        ]);
    }
}
