<?php

use App\Http\Controllers\SpendController;
use Illuminate\Support\Facades\Route;

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

//index
Route::resource('/', SpendController::class);

//create
Route::view('/create', 'spend.create')->name('create');
Route::post('/create/spend', [SpendController::class, 'store'])->name('create.spending');

//edit
Route::get('/edit/{id}', [SpendController::class, 'edit'])->name('get.edit.spend');
Route::post('/edit/spend', [SpendController::class, 'update'])->name('post.edit.spend');
