<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Traits\UploadsWebpImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use UploadsWebpImage;

    /**
     * List semua resep (dengan filter optional).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Recipe::with('categories');

        // Filter berdasarkan kategori (UUID)
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter berdasarkan nama kategori
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter exclusive
        if ($request->has('is_exclusive')) {
            $query->where('is_exclusive', filter_var($request->is_exclusive, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter trending
        if ($request->has('is_trending')) {
            $query->where('is_trending', filter_var($request->is_trending, FILTER_VALIDATE_BOOLEAN));
        }

        // Search berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $recipes = $query->orderByDesc('created_at')->simplePaginate(25)->through(function ($recipe) {
            $recipe->image_url = $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null;
            return $recipe;
        });

        return response()->json([
            'status' => 'success',
            'data' => $recipes,
        ]);
    }

    /**
     * Simpan resep baru.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_exclusive' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'instructions' => 'required|array',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->uploadWebp($request->file('image'), 'recipes');
        }

        $recipe = Recipe::create([
            'name' => $request->name,
            'image_url' => $imageUrl,
            'is_exclusive' => $request->boolean('is_exclusive', false),
            'is_trending' => $request->boolean('is_trending', false),
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
        ]);

        $recipe->categories()->attach($request->category_ids);

        $recipe->load('categories');
        $recipe->image_url = $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Resep berhasil ditambahkan.',
            'data' => $recipe,
        ], 201);
    }

    /**
     * Detail resep.
     */
    public function show(Recipe $recipe): JsonResponse
    {
        $recipe->load('categories');

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'categories' => $recipe->categories,
                'imageUrl' => $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null,
                'isExclusive' => (bool) $recipe->is_exclusive,
                'isTrending' => (bool) $recipe->is_trending,
                'description' => $recipe->description,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'createdAt' => $recipe->created_at,
                'updatedAt' => $recipe->updated_at,
            ],
        ]);
    }

    /**
     * Update resep.
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_ids' => 'sometimes|required|array',
            'category_ids.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_exclusive' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'description' => 'sometimes|required|string',
            'ingredients' => 'sometimes|required|array',
            'instructions' => 'sometimes|required|array',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($recipe->image_url);
            $recipe->image_url = $this->uploadWebp($request->file('image'), 'recipes');
        }

        $recipe->fill($request->only([
            'name',
            'is_exclusive',
            'is_trending',
            'description',
            'ingredients',
            'instructions',
        ]));

        $recipe->save();

        if ($request->has('category_ids')) {
            $recipe->categories()->sync($request->category_ids);
        }

        $recipe->load('categories');
        $recipe->image_url = $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null;

        return response()->json([
            'status' => 'success',
            'message' => 'Resep berhasil diupdate.',
            'data' => $recipe,
        ]);
    }

    /**
     * Hapus resep.
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        $recipe->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Resep berhasil dihapus.',
        ]);
    }
}
