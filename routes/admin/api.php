<?php

use App\Http\Controllers\Admin\Api\OrderController;
use App\Http\Controllers\Admin\AttributeValuesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders','middleware' => ['auth:admin']], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/approved', [OrderController::class, 'approvedOrder']);
    Route::post('/rejectOrder', [OrderController::class, 'rejectOrder']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::patch('/{order}/update', [OrderController::class, 'update']);
});
Route::group(['prefix' => 'attributeValue','middleware' => ['auth:admin']], function () {
    Route::post('{attributeValue}/is_show/{is_show}', [AttributeValuesController::class, 'activeAttributeValue']);
});