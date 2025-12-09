<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('hello',function(Request $request){
    return response()->json('You are adult and indian');
})->middleware('custom_group1');

//Route::post('/login',[AuthController::class,'loginCheck']);


Route::post('/login',[LoginController::class,'loginCheck']);
//Route::get('/dashboard',DashboardController::class)->middleware('auth:sanctum');
Route::post('/logout',[LoginController::class,'logout'])->middleware('auth:sanctum');

Route::get('/', function () {
//        return response()->json('Hello world from web.php');
echo url('');
});

Route::get('/allcoockies',function(Request $request){
       return $request->cookies->all();
});