<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagementController;

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

// Route::get('/', function () {
//     return view('management.manager');
// });

Route::prefix('cm')->name('cm.')->group(function () {
    Route::get('{any?}', [ManagementController::class, 'show'])
        ->where('any', '.*')
        ->name('employeedata');
    Route::post('employee-save', [ManagementController::class, 'cmEmployeesave'])->name('employe.save');

});
