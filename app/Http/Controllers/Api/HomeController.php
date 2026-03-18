<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\ViewLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Aggregated Discovery API (Banners, Categories, and Restaurants/Recipes).
     */
    public function index(): JsonResponse
    {
        try {
            $banners = Banner::where('status', 1)->orderByDesc('created_at')->limit(10)->get()->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'imageUrl' => $banner->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $banner->image_url : null,
                    'offerText' => $banner->offer_text,
                ];
            });

            $categories = Category::where('status', 1)->orderByDesc('created_at')->limit(10)->get()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'iconUrl' => $category->icon_url ? rtrim(config('app.url'), '/') . '/storage/' . $category->icon_url : null,
                    'viewsCount' => $category->views_count,
                ];
            });

            $restaurants = Recipe::with('categories')->where('status', 1)->orderByDesc('created_at')->limit(10)->get()->map(function ($recipe) {
                return $this->formatRecipe($recipe);
            });

            // Popular section
            $popular = [
                'today' => $this->getPopularRecipes(Carbon::now()->startOfDay()),
                'thisWeek' => $this->getPopularRecipes(Carbon::now()->startOfWeek()),
                'thisMonth' => $this->getPopularRecipes(Carbon::now()->startOfMonth()),
                'thisYear' => $this->getPopularRecipes(Carbon::now()->startOfYear()),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Data discovery berhasil diambil. ',
                'data' => [
                    'banners' => $banners,
                    'categories' => $categories,
                    'restaurants' => $restaurants,
                    'popular' => $popular,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data discovery.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper to fetch popular recipes based on view_logs in a period.
     */
    private function getPopularRecipes(Carbon $startDate)
    {
        $popularLogs = ViewLog::where('viewable_type', 'recipe')
            ->where('created_at', '>=', $startDate)
            ->select('viewable_id', \Illuminate\Support\Facades\DB::raw('count(*) as period_views'))
            ->groupBy('viewable_id')
            ->orderByDesc('period_views')
            ->limit(7)
            ->get();

        return $popularLogs->map(function ($log) {
            $recipe = Recipe::where('status', 1)->find($log->viewable_id);
            if (!$recipe)
                return null;

            return [
                'name' => $recipe->name,
                'period_views' => $log->period_views,
                'imageUrl' => $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null,
            ];
        })->filter()->values();
    }

    /**
     * Helper to format recipe for response.
     */
    private function formatRecipe($recipe)
    {
        return [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'categories' => $recipe->categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            }),
            'imageUrl' => $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null,
            'isExclusive' => (bool) $recipe->is_exclusive,
            'isTrending' => (bool) $recipe->is_trending,
            'description' => $recipe->description,
            'ingredients' => $recipe->ingredients,
            'instructions' => $recipe->instructions,
            'viewsCount' => $recipe->views_count,
        ];
    }

    public function categories(): JsonResponse
    {
        try {
            $categories = Category::where('status', 1)->simplePaginate(25)->through(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'iconUrl' => $category->icon_url ? rtrim(config('app.url'), '/') . '/storage/' . $category->icon_url : null,
                    'viewsCount' => $category->views_count,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data kategori berhasil diambil.',
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data kategori.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function category($id): JsonResponse
    {
        try {
            $category = Category::with(['recipes' => function ($q) {
                $q->where('status', 1);
            }, 'recipes.categories'])->where('status', 1)->find($id);
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kategori tidak ditemukan.',
                ], 404);
            }

            // Increment views_count and log it
            $category->increment('views_count');
            ViewLog::create([
                'viewable_type' => 'category',
                'viewable_id' => $category->id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data kategori berhasil diambil.',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'iconUrl' => $category->icon_url ? rtrim(config('app.url'), '/') . '/storage/' . $category->icon_url : null,
                    'viewsCount' => $category->views_count,
                    'restaurants' => $category->recipes->map(function ($recipe) {
                        return [
                            'id' => $recipe->id,
                            'name' => $recipe->name,
                            'categories' => $recipe->categories->map(function ($cat) {
                                return [
                                    'id' => $cat->id,
                                    'name' => $cat->name,
                                ];
                            }),
                            'imageUrl' => $recipe->image_url ? rtrim(config('app.url'), '/') . '/storage/' . $recipe->image_url : null,
                            'isExclusive' => (bool) $recipe->is_exclusive,
                            'isTrending' => (bool) $recipe->is_trending,
                            'viewsCount' => $recipe->views_count,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data kategori.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function recipes(): JsonResponse
    {
        try {
            $recipes = Recipe::with('categories')->where('status', 1)->simplePaginate(25)->through(function ($recipe) {
                return $this->formatRecipe($recipe);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data resep berhasil diambil.',
                'data' => $recipes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data resep.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function recipe($id): JsonResponse
    {
        try {
            $recipe = Recipe::with('categories')->where('status', 1)->find($id);
            if (!$recipe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resep tidak ditemukan.',
                ], 404);
            }

            // Increment views_count and log it
            $recipe->increment('views_count');
            ViewLog::create([
                'viewable_type' => 'recipe',
                'viewable_id' => $recipe->id,
            ]);

            $data = $this->formatRecipe($recipe);
            $data['createdAt'] = $recipe->created_at;
            $data['updatedAt'] = $recipe->updated_at;

            return response()->json([
                'status' => 'success',
                'message' => 'Data resep berhasil diambil.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data resep.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function search(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $query = $request->input('query');

            $recipes = Recipe::with('categories')
                ->where('status', 1)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhereHas('categories', function ($q) use ($query) {
                            $q->where('name', 'like', "%{$query}%");
                        });
                })
                ->orderByDesc('created_at')
                ->simplePaginate(10);

            $recipes->through(function ($recipe) {
                return $this->formatRecipe($recipe);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Hasil pencarian resep.',
                'data' => $recipes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan pencarian resep.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
