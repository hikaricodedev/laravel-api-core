<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/test-api', function(){
    return response()->json([
        "status" => "success"
    ]);
});
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/test-token' , function(){
        return response()->json([
            "status" => "auth sukses"
        ],200);
    });
});

