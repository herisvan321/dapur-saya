<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'message' => 'Data profil berhasil diambil.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Update user profile (name only).
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $user = $request->user();
            $user->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => ['required', 'current_password:sanctum'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            $user = $request->user();
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password berhasil diubah.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
