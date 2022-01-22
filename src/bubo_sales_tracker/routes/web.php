<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\SmaregiController;
use App\Http\Controllers\DataBaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect("/","/sale");

Route::middleware(['auth'])->group(function () {
    Route::resource('sale', SaleController::class);
    Route::resource('shop', ShopController::class)->except(['show'])->middleware('adminAuth');
    Route::resource('user', UserController::class)->except(['show'])->middleware('adminAuth');
    Route::resource('category', CategoryController::class)->except(['show'])->middleware('adminAuth');
    Route::resource('item', ItemController::class)->only(['index']);
    Route::resource('item', ItemController::class)->except(['index','show'])->middleware('adminAuth');
    Route::post('csv/import', [CsvController::class, 'import'])->name('csv.import')->middleware('adminAuth');
    Route::get('/smaregi/update-stock/{sale}', [SmaregiController::class, 'updateStock'])->name('smaregi.updateStock');
    Route::get('/smaregi/update-stock/', [SmaregiController::class, 'updateStockAll'])->name('smaregi.updateStockAll')->middleware('adminAuth');
    Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs')->middleware('adminAuth');
    Route::resource('database', DataBaseController::class)->only(['index'])->middleware('adminAuth');;
    Route::get('database/download-backup', [DataBaseController::class, 'downloadBackup'])->name('database.downloadBackup')->middleware('adminAuth');
});

require __DIR__.'/auth.php';
