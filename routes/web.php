<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
   
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Page Routes
    Route::get('/post',[PostController::class,'page']);

    //API Routes
    Route::get('/list-post',[PostController::class,'listPost'])->name('post.list');
    Route::post('/create-post',[PostController::class,'createPost'])->name('post.create');
    Route::post('/update-post',[PostController::class,'updatePost'])->name('post.update');
    Route::post('/delete-post',[PostController::class,'deletePost'])->name('post.delete');
});

require __DIR__.'/auth.php';


