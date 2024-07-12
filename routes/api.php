<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApproverController;
use App\Http\Controllers\Api\ApprovalStageController;
use App\Http\Controllers\Api\ExpenseController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'approver', 'name'=>'approver.', 'middleware'=>[]],function () {
        Route::post('/', [ApproverController::class, 'store'])->name('store');        
});

Route::group(['prefix' => 'approval-stages', 'name'=>'approval-stages.', 'middleware'=>[]],function () {
        Route::post('/', [ApprovalStageController::class, 'store'])->name('store');
        Route::put('/{id}', [ApprovalStageController::class ,'update'])->name('update');        
});

Route::group(['prefix' => 'expense', 'name'=>'expense.', 'middleware'=>[]],function () {
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::patch('/{id}/approve', [ExpenseController::class,'approve'])->name('approve');  
        Route::get('/{id}', [ExpenseController::class,'detail'])->name('detail');      
});