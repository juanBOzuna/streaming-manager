<?php

use App\Http\Controllers\ScreensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Route::get('',function(){return redirect('/admin');});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route::put('/screensupdate/{id}', [ScreensController::class, 'update']);


// Route::resource('screens_edit', ScreensController::class)->parameters([
//     'screens_edit' => 'id'
// ]);

// Route::controller(ScreensController::class)->group(function () {
//     Route::get('admin/screens_edit/{id}', 'update');
// });
