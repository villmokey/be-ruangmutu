<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Utils\FileUploadController;
use App\Http\Controllers\Api\Master\Document\DocumentTagController;
use App\Http\Controllers\Api\Master\Document\DocumentTypeController;
use App\Http\Controllers\Api\Master\ServiceUnit\ServiceUnitController;
use App\Http\Controllers\Api\Master\HealthService\HealthServiceController;
use App\Http\Controllers\Api\Master\Program\ProgramController;
use App\Http\Controllers\Api\Master\Program\SubProgramController;
use App\Http\Controllers\Api\Indicator\IndicatorProfileController;
use App\Http\Controllers\Api\Indicator\IndicatorController;
use App\Http\Controllers\Api\Master\User\UserController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\OperationalStandard\OperationalStandardController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\Satisfaction\SatisfactionController;
use App\Http\Controllers\Api\CustomerComplaint\CustomerComplaintController;
use App\Http\Controllers\Api\Master\Position\PositionController;
use App\Http\Controllers\Api\Master\User\RoleController;

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
    Route::get('indicator-aprroval/info', [IndicatorProfileController::class,'getApprovalInformation'])->name('getApprovalInformation');
    Route::apiResource('indicator-profile', IndicatorProfileController::class);
    Route::get('indicator-profile/generate/{id}', [IndicatorProfileController::class,'generateProfileIndicator'])->name('generateProfileIndicator');
    Route::get('indicator-profile/chart/{id}', [IndicatorProfileController::class,'getChartDataById']);
    Route::get('indicator-profile/{id}/signature', [IndicatorProfileController::class,'getSignature']);
    Route::post('indicator-profile/{id}/status', [IndicatorProfileController::class,'changeStatus']);

    // Quality Goal
    Route::get('quality-goal', [IndicatorProfileController::class,'qualityGoal']);

    // Quality Indicator
    Route::apiResource('indicator', IndicatorController::class);
    Route::get('indicator/{id}/signature', [IndicatorController::class,'getSignature']);
    Route::get('indicator/{id}/{chartFileId}/generate', [IndicatorController::class,'generateIndicator']);
    Route::post('indicator/{id}/status', [IndicatorController::class,'changeStatus']);

    // User
    Route::apiResource('user', UserController::class);
    Route::get('role', [RoleController::class, 'index']);

    // Document
    Route::apiResource('document', DocumentController::class);

    // Health Service
    Route::apiResource('health-service', HealthServiceController::class);
    Route::post('health-service/assign-unit', [HealthServiceController::class, 'assignServiceUnit']);

    //  Unit
    Route::apiResource('service-unit', ServiceUnitController::class);
    
    //  Position
    Route::apiResource('position', PositionController::class);
    
    //  Position
    Route::get('satisfaction/chart', [SatisfactionController::class, 'chart']);
    Route::get('satisfaction/info', [SatisfactionController::class, 'information']);
    Route::apiResource('satisfaction', SatisfactionController::class);
    
    //  Complaint
    Route::put('complaint/update-info/{id}', [CustomerComplaintController::class, 'updateInfo']);
    Route::apiResource('complaint', CustomerComplaintController::class);
    
    // Event
    Route::apiResource('event', EventController::class);
    Route::put('event/realize/{id}', [EventController::class, 'realized']);
    
    // OperationalStandard
    Route::apiResource('operational-standard', OperationalStandardController::class);

    // Dashboard
    Route::get('dashboard/recap/indicator', [DashboardController::class,'recapIndicator']);
    Route::get('dashboard/recap/performance', [DashboardController::class,'recapPerformance']);
    Route::get('dashboard/recap/satisfaction', [DashboardController::class,'recapSatisfaction']);
    Route::get('dashboard/recap/complaint', [DashboardController::class,'recapComplaint']);
    Route::get('dashboard/event/information', [DashboardController::class,'eventInfo']);
    Route::get('dashboard/document/information', [DashboardController::class,'documentInfo']);
    Route::get('dashboard/indicator', [DashboardController::class,'indicator']);
    Route::get('dashboard/indicator/cardlist', [DashboardController::class,'cardlist']);

    // File Upload
    Route::post('/upload/image',[FileUploadController::class,'uploadImage']);
    Route::post('/upload/file',[FileUploadController::class,'uploadFile']);
    Route::delete('/upload/{id}',[FileUploadController::class,'destroy']);
});
