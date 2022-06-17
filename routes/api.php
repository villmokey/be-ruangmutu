<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Utils\FileUploadController;
use App\Http\Controllers\Api\Master\Document\DocumentTagController;
use App\Http\Controllers\Api\Master\Document\DocumentTypeController;
use App\Http\Controllers\Api\Master\Service\ServiceUnitController;
use App\Http\Controllers\Api\Master\Program\ProgramController;
use App\Http\Controllers\Api\Master\Program\SubProgramController;
use App\Http\Controllers\Api\Master\Program\IndicatorProfileController;
use App\Http\Controllers\Api\Indicator\IndicatorController;
use App\Http\Controllers\Api\Master\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/health/check', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('api')->prefix('v1')->group(function(){
    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Document Type
    Route::apiResource('document-type', DocumentTypeController::class);
    Route::put('document-type/{id}/status', [DocumentTypeController::class,'updatePublish']);

    // Document Tag
    Route::apiResource('document-tag', DocumentTagController::class);
    Route::put('document-tag/{id}/status', [DocumentTagController::class,'updatePublish']);

    // Program
    Route::apiResource('program', ProgramController::class);
    Route::put('program/{id}/status', [ProgramController::class,'updatePublish']);

    // Sub Program
    Route::apiResource('sub-program', SubProgramController::class);
    Route::put('sub-program/{id}/status', [SubProgramController::class,'updatePublish']);

    // Quality Indicator Profile
    Route::apiResource('indicator-profile', IndicatorProfileController::class);

    // Quality Goal
    Route::get('quality-goal', [IndicatorProfileController::class,'qualityGoal']);

    // Quality Indicator
    Route::apiResource('indicator', IndicatorController::class);

    // User
    Route::apiResource('user', UserController::class);

    // File Upload
    Route::post('/upload/image',[FileUploadController::class,'uploadImage']);
    Route::post('/upload/file',[FileUploadController::class,'uploadFile']);
});
