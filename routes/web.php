<?php

use App\Http\Controllers\CanteenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::redirect('/', '/panel');
Route::redirect('/home', '/panel');

Auth::routes();

Route::prefix('panel')->middleware('auth')->name('panel.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // User Manager
    Route::get('Userman', [DashboardController::class, 'userman'])->name('userman');
    Route::get('Userman/add', [DashboardController::class, 'usermanAdd'])->name('userman.add');
    Route::post('Userman/add', [DashboardController::class, 'usermanStore'])->name('userman.store');
    Route::get('Userman/topup', [DashboardController::class, 'usermanTopup'])->name('userman.topup');
    Route::post('Userman/topup', [DashboardController::class, 'usermanTopuppost'])->name('userman.topuppost');
    Route::get('Userman/{user}', [DashboardController::class, 'usermanShow'])->name('userman.show');
    Route::patch('Userman/{user}', [DashboardController::class, 'usermanUpdate'])->name('userman.update');
    Route::delete('Userman/{user}', [DashboardController::class, 'usermanDestroy'])->name('userman.delete');
    // Route::get('Aktivitas',[AboutController::class,'aktivitas'])->name('aktv');
    // Route::get('Prestasi',[AboutController::class,'prestasi'])->name('prestasi');
    // Wallet 
    Route::get('Wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('Wallet/{user}', [WalletController::class, 'setup'])->name('wallet.setup');
    // Canteen 
    Route::get('Canteen', [CanteenController::class, 'index'])->name('canteen');
    Route::get('Canteen/detail/{order}', [CanteenController::class, 'detail'])->name('canteen.detail');
    Route::post('Canteen/detail/{order}', [CanteenController::class, 'cancel'])->name("canteen.cancel");
    Route::post('Canteen/add/{user}', [CanteenController::class, 'store'])->name('canteen.add');
    // web.php
    Route::put('/canteen/update', [CanteenController::class, 'update'])->name('canteen.update');
    Route::get('Canteen/explore', [CanteenController::class, 'explore'])->name('canteen.explore');
    Route::post('/canteen/toggle-status', [CanteenController::class, 'toggleStatus'])->name('canteen.toggleStatus');
    Route::post('Canteen/{user}', [CanteenController::class, 'setup'])->name('canteen.setup');
    Route::get('Canteen/orders', [CanteenController::class, 'order'])->name('canteen.order');
    Route::get('Canteen/{canteen}', [CanteenController::class, 'show'])->name('canteen.show');
    Route::post('Canteen', [CanteenController::class, 'beli'])->name('canteen.beli');
    Route::delete('Canteen/{canteen}', [CanteenController::class, 'destroy'])->name('canteen.destroy');
    Route::put('/canteen/edit', [CanteenController::class, 'edit'])->name('canteen.edit');



    //    Route::get('Canteen/api/{rfid:rfid}',[CanteenController::class,'pay']);

});

Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('recipient@example.com')
                ->subject('Test Email');
    });
    
    return 'Email sent successfully';
});
