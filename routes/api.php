<?php

use App\Http\Controllers\Api\TimetableController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TTElementsController;
use App\Http\Controllers\Api\StyleController;
use App\Http\Controllers\AuthController;
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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('users', UserController::class);

    Route::resource('notes', NoteController::class);
    
    Route::resource('styles', StyleController::class);
    
    Route::resource('timetables', TimetableController::class);

    Route::resource('ttelements', TTElementsController::class);
    
    Route::get('/users/{id}/notes', [NoteController::class, 'getRelatedNotes']);
    
    Route::get('/users/{id}/timetables', [TimetableController::class, 'getTimetable']);

    //  id = timeTableId
    Route::get('/timetables/{id}/selectedtimetable', [TTElementsController::class, 'getAllElements']);

    Route::get('/users/{id}/fulltimetables', [TTElementsController::class, 'getFullTimetables']);
    
    Route::get('/users/{id}/styles', [StyleController::class, 'getRelated']);
});
