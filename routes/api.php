<?php

use App\Http\Controllers\Api\V1\LocationApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->controller(LocationApiController::class)->name('api.v1.location.')->prefix('v1')->group(function () {
    Route::get('get-country', 'getCountry')->name('get.country');
    Route::get('get-state', 'getState')->name('get.state');
    Route::get('get-city', 'getCity')->name('get.city');
});
