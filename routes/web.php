<?php

use App\Http\Controllers\DataController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('admin'));

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('api/v1')->group(function () {
        Route::post('token', function(Request $request) {
            if (! auth()->attempt(request(['email', 'password']))) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = User::where('email', request('email'))->first();
            return $user->createToken('auth-token')->plainTextToken;
        })->withoutMiddleware('auth:sanctum');

        Route::post('filter', [DataController::class, 'filter']);
        Route::post('store', [DataController::class, 'store']);
        Route::delete('delete', [DataController::class, 'delete']);
    });
});
