<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\ViewLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function index(): JsonResponse
    {
        try {
            $totalBanners = Banner::count();
            $totalCategories = Category::count();
            $totalRecipes = Recipe::count();

            $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
            
            $dayMappings = [
                'Monday' => 'Sen',
                'Tuesday' => 'Sel',
                'Wednesday' => 'Rab',
                'Thursday' => 'Kam',
                'Friday' => 'Jum',
                'Saturday' => 'Sab',
                'Sunday' => 'Min',
            ];

            $visitorChartData = [];
            $totalVisitors7Days = 0;
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateString = $date->format('Y-m-d');
                $dayName = $date->format('l');
                
                $categoryCount = ViewLog::where('viewable_type', 'category')
                    ->whereDate('created_at', $dateString)
                    ->count();
                    
                $recipeCount = ViewLog::where('viewable_type', 'recipe')
                    ->whereDate('created_at', $dateString)
                    ->count();
                
                $totalVisitors7Days += ($categoryCount + $recipeCount);
                
                $visitorChartData[] = [
                    'date' => $dateString,
                    'day' => $dayMappings[$dayName] ?? $dayName,
                    'categoryCount' => $categoryCount,
                    'recipeCount' => $recipeCount,
                    'total' => $categoryCount + $recipeCount,
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data dashboard berhasil diambil.',
                'data' => [
                    'totalBanners' => $totalBanners,
                    'totalCategories' => $totalCategories,
                    'totalRecipes' => $totalRecipes,
                    'totalVisitors7Days' => $totalVisitors7Days,
                    'visitorChartData' => $visitorChartData,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data dashboard.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
