<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\TopikController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route Home Page
Route::get('/', function () {
    return view('home');
})->name('home');

//Route Profile Page
// Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//Route Course,Topik,Materi
Route::get('/course', [KategoriController::class, 'read']);
Route::get('/course/{id_kategori}', [KategoriController::class, 'showTopik']);
Route::get('/course/{id_kategori}/{id_topik}', [TopikController::class, 'showMateri']);

//Route About Page
Route::get('/about', function () {
    return view('page.about');
});


//CRUD Kategori
Route::resource('/kategori', KategoriController::class);

//CRUD Topik
Route::resource('/topik', TopikController::class);

//CRUD Materi
Route::resource('/materi', MateriController::class);

//Register
Route::post('/auth/register', [AuthController::class, 'register']);

//Login;
Route::post('/auth/login', [AuthController::class, 'login']);
