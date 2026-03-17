<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\UploadsWebpImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use UploadsWebpImage;

    /**
     * List semua kategori.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('recipes')->simplePaginate(10)->through(function ($category) {
            $category->icon_url = $category->icon_url ? url('storage/' . $category->icon_url) : null;
            return $category;
        });

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $iconUrl = null;
        if ($request->hasFile('icon')) {
            $iconUrl = $this->uploadWebp($request->file('icon'), 'categories');
        }

        $category = Category::create([
            'name' => $request->name,
            'icon_url' => $iconUrl,
        ]);

        $category->icon_url = $category->icon_url ? url('storage/' . $category->icon_url) : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $category,
        ], 201);
    }

    /**
     * Detail kategori beserta resep-resepnya.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('recipes');
        $category->icon_url = $category->icon_url ? url('storage/' . $category->icon_url) : null;

        // Map image URLs pada recipes
        $category->recipes->transform(function ($recipe) {
            $recipe->image_url = $recipe->image_url ? url('storage/' . $recipe->image_url) : null;
            return $recipe;
        });

        return response()->json([
            'status' => 'success',
            'data' => $category,
        ]);
    }

    /**
     * Update kategori.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $this->deleteImage($category->icon_url);
            $category->icon_url = $this->uploadWebp($request->file('icon'), 'categories');
        }

        if ($request->has('name')) {
            $category->name = $request->name;
        }

        $category->save();
        $category->icon_url = $category->icon_url ? url('storage/' . $category->icon_url) : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate.',
            'data' => $category,
        ]);
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
