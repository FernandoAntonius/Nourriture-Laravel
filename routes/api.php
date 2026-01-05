<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AgeClassificationController;
use App\Http\Controllers\API\PersonController;
use App\Http\Controllers\API\PredictController;
use App\Http\Controllers\API\HistoryController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ContactUsController;

// Auth routes (public)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('age-classifications', AgeClassificationController::class);
Route::apiResource('persons', PersonController::class);
Route::apiResource('predict', PredictController::class);

Route::apiResource('histories', HistoryController::class)->middleware('auth:sanctum');
Route::delete('/histories-clear', [HistoryController::class, 'clearAll'])->middleware('auth:sanctum');

Route::apiResource('contact-us', ContactUsController::class);

Route::post('/classify-age', [AgeClassificationController::class, 'classifyAge']);
Route::post('/predict/classify', [PredictController::class, 'predictClassification']);
Route::get('/age-classifications/{ageClassification}/persons', [PersonController::class, 'getByAgeClassification']);