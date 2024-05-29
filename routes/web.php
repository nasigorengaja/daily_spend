<?php

use App\Http\Controllers\SpendController;
use App\Http\Controllers\UserController;
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

//login
Route::get('/', function () {
    return view('user.login');
})->name('login');
Route::post('/login', [UserController::class, 'index'])->name('user.login');

//register
Route::get('/register', function () {
    return view('user.register');
})->name('register');
Route::post('/register', [UserController::class, 'store'])->name('user.register');

Route::post('/logout', [UserController::class, 'logOut'])->name('user.logout');

Route::middleware('auth')->group(function () {
    //index dashboard
    Route::get('/spend', [SpendController::class, 'index'])->name('spend.index');
    Route::get('spend/export/', [SpendController::class, 'export'])->name('spends.export');
    Route::post('spend/import/', [SpendController::class, 'import'])->name('spends.import');

    //create
    Route::view('/create', 'spend.create')->name('create');
    Route::post('/create/spend', [SpendController::class, 'store'])->name('create.spending');

    //edit
    Route::get('/edit/{id}', [SpendController::class, 'edit'])->name('get.edit.spend');
    Route::post('/edit/spend', [SpendController::class, 'update'])->name('post.edit.spend');
    Route::get('/get-spend-data', [SpendController::class, 'getSpendData'])->name('get.spend.data');

    //delete
    Route::post('/spends/delete', [SpendController::class, 'deleteSpendData'])->name('delete.spend.data');
});
